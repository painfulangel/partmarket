<?php
	class ReCaptcha {
		public static function isGoodCaptcha($response) {
			$save = true;
			 
			if (Yii::app()->user->isGuest) {
				$url = Yii::app()->config->get('Site.recaptchaurl');
				$secret = Yii::app()->config->get('Site.recaptchasecretkey');
		
				$remoteip = CHttpRequest::getUserHostAddress();
				 
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $secret, 'response' => $response, 'remoteip' => $remoteip)));
				$out = curl_exec($ch);
				 
				$resp = json_decode($out, true);
				 
				$save = is_array($resp) && array_key_exists('success', $resp) && (((bool) $resp['success']) == true);
			}
			 
			return $save;
		}
	}