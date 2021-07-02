<?php

class SmsSend {

    public $_text;
    public $_phone_number;

    public function send() {
        $this->_phone_number=  str_replace(array('(','-',')',' '), '', $this->_phone_number);
//        Yii::log($this->_phone_number.'qqq');
        if (Yii::app()->config->get('SendSMS.Active')) {
            return true;
        }
        return false;
    }

    public function setData($data) {
        $this->_text = $data['_text'];
        $this->_phone_number = $data['_phone_number'];
    }

}

?>
