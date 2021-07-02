<?php

class VipSmsSmsSend extends SmsSend {

    private $login;
    private $password;
    private $sign;

    public function setData($data) {
        parent::setData($data);
        $this->password = Yii::app()->config->get('VipSMS.Password');
        $this->login = Yii::app()->config->get('VipSMS.Login');
        $this->sign = Yii::app()->config->get('VipSMS.Sign');
    }

    public function log($soap_res) {
        Yii::log("Warning, problem: code   : {$soap_res->code} message: {$soap_res->message}");
        $enc_terminal = 'utf-8';
        if ($soap_res->extend && is_array($soap_res->extend)) {
            Yii::log(" explain: " . iconv('utf-8', $enc_terminal, var_export($soap_res->extend, true)) . "\n");
        }
    }

    public function send() {
        if (parent::send()) {
            $sms = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $this->_text);
            Yii::log($sms . 'qqq');
//            $sms = $this->_text;
            $client = new SoapClient('http://vipsms.net/api/soap.html');

            $res = $client->auth($this->login, $this->password);
            if ($res->code != 0) {
                $this->log($res);
                return;
            }

            $sessid = $res->message;

//            die;
            $res = $client->sendSmsOne($sessid, $this->_phone_number, $this->sign, $sms);
            if ($res->code != 0) {
                $this->log($res);
                return;
            }
            Yii::log("VipSMS Message send success. ID is {$res->message}\n");
        }
        return false;
    }

}

?>
