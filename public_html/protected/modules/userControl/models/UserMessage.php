<?php
class UserMessage extends CActiveRecord {
	public $_attachment;
    public $theme;
    
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'user_message';
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message', 'required'),
			array('user_id, root, lft, rgt, level, parent_id, user_dialog_id, readed_admin, readed_user, date', 'numerical', 'integerOnly' => true),
			array('message, attachment', 'safe'),
			array('_attachment', 'file', 'types' => Yii::app()->config->get('Site.MessagesFilesExtensions'), 'allowEmpty' => true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, message, attachment, date, root, lft, rgt, level, parent_id, user_dialog_id, readed_admin, readed_user', 'safe', 'on' => 'search'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'user_id' => Yii::t('messages', 'User'),
			'theme' => Yii::t('messages', 'Theme'),
			'message' => Yii::t('messages', 'Message'),
			'attachment' => Yii::t('messages', 'Attachment'),
			'_attachment' => Yii::t('messages', 'Attachment'),
			'date' => Yii::t('messages', 'Date'),
			'root' => 'Root',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'level' => 'Level',
			'parent_id' => 'Parent id',
			'user_dialog_id' => 'User dialog id',
		);
	}
	
	public function behaviors() {
		return array(
			'nestedSetBehavior' => array(
				'class' => 'ext.nested-set.NestedSetBehavior',
				'leftAttribute' => 'lft',
				'rightAttribute' => 'rgt',
				'levelAttribute' => 'level',
				'rootAttribute' => 'root',
				'hasManyRoots' => true,
			),
		);
	}
	
	public function beforeSave() {
		if (parent::beforeSave()) {
			if ($this->isNewRecord) {
				$temp = CUploadedFile::getInstance($this, '_attachment');
				
				//echo '<pre>'; print_r($temp); echo '</pre>'; exit;
				
				if (is_object($temp)) {
					//Название файла
					$filename = pathinfo($temp->getName());
					$this->attachment = md5(time()).'.'.$filename['extension'];
				}
			}
			
			$this->user_id = Yii::app()->user->id;
			$this->date = time();
			
			if ($this->scenario == 'new') {
				$dialog = new UserMessageDialog();
				
				$um = Yii::app()->request->getPost('UserMessage');
				$dialog->theme = $um['theme'];
				
				$dialog->user_id = Yii::app()->user->id;
				$dialog->date_start = time();
				if ($dialog->save()) {
					$this->user_dialog_id = $dialog->primaryKey;
				} else {
					//echo '<pre>'; print_r($dialog->getErrors()); echo '</pre>'; exit;
					return false;
				}
			}
			
			return true;
		}
		
		return false;
	}
	
	public function afterSave() {
		parent::afterSave();
		
		if ($this->isNewRecord && $this->attachment) {
			$_attachment = CUploadedFile::getInstance($this, '_attachment');
			
			$filename = $this->attachment; //md5(time()).'.'.$filename['extension'];
			
			$_attachment->saveAs($this->getPathFiles().$filename);
		}
		
		$dialog = UserMessageDialog::model()->findByPk($this->user_dialog_id);
		if (is_object($dialog)) {
			if ($this->parent_id) {
				$dialog->date_last_answer = time();
				$dialog->save();
			}
			$profile = UserProfile::model()->findByAttributes(array('uid' => $dialog->user_id));
			
			if (is_object($profile)) {
				if ($dialog->user_id == $this->user_id) {
					//Send message to admin
					$message = new YiiMailMessage();
					
					$name = array();
					if ($profile->first_name)  $name[] = $profile->first_name;
					if ($profile->father_name) $name[] = $profile->father_name;
					if ($profile->second_name) $name[] = $profile->second_name;
					
					$address = array();
					if ($profile->delivery_zipcode) $address[] = $profile->delivery_zipcode;
					if ($profile->delivery_country) $address[] = $profile->delivery_country;
					if ($profile->delivery_city)    $address[] = $profile->delivery_city;
					if ($profile->delivery_street)  $address[] = $profile->delivery_street;
					if ($profile->delivery_house)   $address[] = $profile->delivery_house;
					
					$message->setSubject(Yii::t('messages', 'New message from ').implode(' ', $name).', '.Yii::app()->config->get('Site.SiteName'));
					$message->setBody(Yii::t('messages', 'You can answer in your <a href="{siteUrl}">register of messages</a>.<br />User data:<br />Phone: {userPhone}<br />E-mail: {userEmail}<br />Name: {userName}<br />Address: {userAddress}<br /><br />Yours respectfully,<br />administration of {siteName}.', array('{siteUrl}' => Yii::app()->createAbsoluteUrl(Yii::app()->homeUrl).Yii::app()->createUrl('/userControl/adminUserMessages/messages', array('id_dialog' => $this->user_dialog_id)), 
																																																																															'{siteName}' => Yii::app()->config->get('Site.SiteName'),
																																																																															'{userPhone}' => $profile->phone,
																																																																															'{userEmail}' => $profile->email,
																																																																															'{userName}' => implode(' ', $name),
																																																																															'{userAddress}' => implode(', ', $address),
					)), 'text/html');
					
					$list = explode(',', Yii::app()->config->get('Site.MessagesEmails'));
					foreach ($list as $email) {
						$message->addTo(trim($email));
					}
					
					$message->from = Yii::app()->config->get('Site.NoreplyEmail');
					$recipient_count = Yii::app()->mail->send($message);
						
					if ($recipient_count > 0)
						Yii::log('E-mail to '.$profile->email.' was sent.', CLogger::LEVEL_INFO, 'messages');
					else
						Yii::log('Failed sending e-mail to '.$profile->email.'.', CLogger::LEVEL_WARNING, 'messages');
				} else {
					//Send message to user
					$message = new YiiMailMessage();
					$message->setSubject(Yii::t('messages', 'You have new message from ').Yii::app()->config->get('Site.SiteName'));
					$message->setBody(Yii::t('messages', 'You can answer in your <a href="{siteUrl}">personal account</a>.<br /><br />Yours respectfully,<br />administration of {siteName}.', array('{siteUrl}' => Yii::app()->createAbsoluteUrl(Yii::app()->homeUrl).Yii::app()->createUrl('/userControl/userMessages/messages', array('id_dialog' => $this->user_dialog_id)), '{siteName}' => Yii::app()->config->get('Site.SiteName'))), 'text/html');
					$message->addTo($profile->email);
					$message->from = Yii::app()->config->get('Site.NoreplyEmail');
					$recipient_count = Yii::app()->mail->send($message);
					
					if ($recipient_count > 0)
						Yii::log('E-mail to '.$profile->email.' was sent.', CLogger::LEVEL_INFO, 'messages');
					else
						Yii::log('Failed sending e-mail to '.$profile->email.'.', CLogger::LEVEL_WARNING, 'messages');
				}
			}
		}
	}
	
	private function getPathFiles() {
		return realpath(Yii::app()->basePath.'/..'.'/uploads/messages/').'/';
	}
	
	public function getImage() {
		if ($this->attachment)
			return '<a href="/uploads/messages/'.$this->attachment.'">'.$this->attachment.'</a>';
		
		return '';
	}
	
	public function search() {
		$criteria = new CDbCriteria;
	
		$criteria->compare('id', $this->id);
		$criteria->compare('user_dialog_id', $this->user_dialog_id);
		$criteria->compare('root', $this->root, true);
		$criteria->compare('lft', $this->lft, true);
		$criteria->compare('rgt', $this->rgt, true);
		$criteria->compare('level', $this->level, true);
		$criteria->compare('parent_id', $this->parent_id, true);
		
		if ($this->date) {
			$date_start = @strtotime($this->date);
			$date_end = $date_start + 3600 * 24;
				
			$criteria->compare('date', '>='.$date_start, true);
			$criteria->compare('date', '<='.$date_end, true);
		}
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'date ASC',
			),
		));
	}
	
	public function getUserName() {
		$userName = '';
	
		if ($this->user_id) {
			$profile = UserProfile::model()->findByAttributes(array("uid" => $this->user_id));
				
			if (is_object($profile)) $userName = $profile->getFullName();
		}
	
		return $userName;
	}
}