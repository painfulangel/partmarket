<?php

class HandlerQueuePrice extends CConsoleCommand
{
    /**
     * Запуск консольной команды.
     * @param array $args
     * @return int
     * @throws CHttpException
     */
    public function run($args)
    {
        /**
         * Проверка на наличие файлов на диске
         * И удаление битых записей
         */
        $this->checkFile();

        $model = PricesAutoloadQueue::model()->getFirstRecord();

        if($model){

            $this->processPrice($model);

        }else{
            echo 'Очередь не найдена'.PHP_EOL;
        }
    }

    /**
     * @param $model
     */
    protected function processPrice($model)
    {
        $rule = PricesFtpSourcesRules::model()->findByPk($model->rule_id);

        if ($rule) {
            $mailBox = $rule->mailBox;

            echo $model->path.$model->filename;
            echo PHP_EOL;

            try{
                $new = $rule->convert($model->path, $model->filename);
                if ($new != FALSE) {
                    file_put_contents($model->path . $model->filename, $new);
                    echo 'convert file' . PHP_EOL;
                }

                $download_count = $rule->savePrice($model->path . $model->filename, $model->filename);
                echo 'download count=' . $download_count . PHP_EOL;
                $rule->download_time = time();
                $rule->download_count = $download_count;
                $mailBox->scenario = 'runCronTab';
                if ($mailBox->save()) {
                    $rule->save();
                    $price = Prices::model()->findByAttributes(array(
                        'rule_id' => $rule->id
                    ));
                    if ($price) {
                        $price->scenario = 'queue';
                        $price->count_position = $download_count;
                        $price->save();
                    }
                    echo 'mail box save' . PHP_EOL;
                    if ($model->delete()) {
                        echo 'model deleted' . PHP_EOL;
                    }
                }

            }catch(Exception $e){
                print_r($e,10,true);
                echo PHP_EOL;
                @unlink($model->path.$model->filename);
                $model->delete();
                echo 'exception read file! deleted'.PHP_EOL;
            }

        } else {
            if ($model->delete()) {
                echo 'model deleted' . PHP_EOL;
            }
            echo 'правило не найдено' . PHP_EOL;
        }
    }

    protected function checkFile()
    {
        $models = PricesAutoloadQueue::model()->findAll();
        foreach ($models as $model) {
            if(!file_exists($model->path . $model->filename)){
                $model->delete();
            }
        }
    }
}