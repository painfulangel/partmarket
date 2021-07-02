<?php

/**
 * Description of OrderAction
 *
 * @author Nr Aziz
 */
class OrderAction extends CAction {

    public $modelClass;
    public $pkName = 'id';

    public function run($pk, $name, $value, $move) {
        $model = CActiveRecord::model($this->modelClass)->findByPk($pk);
        $table = $model->tableName();
        if ($move === 'up') {
            $op = '<=';
            $inOrder = 'DESC';
        } else if ($move === 'down') {
            $op = '>=';
            $inOrder = 'ASC';
        }

        $sql = "SELECT {$table}.{$name} FROM $table WHERE $table.$name $op " . $model->{$name} . " AND $table.$this->pkName!=$pk ORDER BY $table.$name $inOrder LIMIT 1";
        $order = Yii::app()->db->createCommand($sql)->queryScalar();
        $sql = "SELECT {$table}.{$this->pkName} FROM $table WHERE $table.$name $op " . $model->{$name} . " AND $table.$this->pkName!=$pk ORDER BY $table.$name $inOrder LIMIT 1";
        $id_to = Yii::app()->db->createCommand($sql)->queryScalar();
        if (empty($id_to))
            return;

        $highestOrder = Yii::app()
                ->db
                ->createCommand("SELECT {$table}.{$name} FROM {$table} ORDER BY {$table}.{$name} DESC LIMIT 1")
                ->queryScalar();


//        if ($move === 'up' && $model->{$name} != 0)
//            $order -= 1;
//        else if ($move === 'down' && $order != $highestOrder + 1)
//            $order += 1;
        $model_to = CActiveRecord::model($this->modelClass)->findByPk($id_to);
        $model_to->{$name} = $model->{$name};
        $model_to->save(false);
        $model->{$name} = $order;
        $model->save(false);
    }

}

?>
