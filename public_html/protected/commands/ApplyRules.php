<?php

class ApplyRules extends CConsoleCommand
{


    public function run($args)
    {
        $mailboxes = PricesFtpAutoloadMailboxes::model()->findAll();
        foreach ($mailboxes as $mailbox) {
            $rules = PricesFtpAutoloadRules::model()->findAllByAttributes(array(
                'mail_id'=>$mailbox->id
            ));

            if($rules){
                foreach ($rules as $rule) {
                    $source = new PricesFtpSourcesRules();
                    $source->setAttributes($rule->attributes);
                    if(!$source->save()){
                        print_r($source->getErrors());
                        echo '==========source============'.PHP_EOL;
                    }
                }
            }
        }
        return 0;
    }

}
