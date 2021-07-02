<?php
class UserMessageDialog extends CMyActiveRecord {
	public $email;
	public $new;
	
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	
	public function tableName() {
		return 'user_message_dialog';
	}
	
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('theme, user_id, date_start', 'required'),
				array('user_id, date_start, date_last_answer, closed', 'numerical', 'integerOnly' => true),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('id, user_id, theme, email, date_start, date_last_answer, closed', 'safe', 'on' => 'search'),
		);
	}
	
	public function attributeLabels() {
		return array(
			'id' => 'ID',
			'user_id' => Yii::t('messages', 'Sender'),
			'theme' => Yii::t('messages', 'Theme'),
			'email' => Yii::t('messages', 'E-mail'),
			'new' => Yii::t('messages', 'New messages'),
			'date_start' => Yii::t('messages', 'Dialog start date'),
			'date_last_answer' => Yii::t('messages', 'Dialog last answer date'),
			'closed' => Yii::t('messages', 'Dialog is closed'),
		);
	}
	
	public function getUserName() {
		$userName = '';
		
		if ($this->user_id) {
			$profile = UserProfile::model()->findByAttributes(array("uid" => $this->user_id));
			
			if (is_object($profile)) $userName = $profile->getFullName();
		}
		
		return $userName;
	}
	
	public function getEmail() {
		$email = '';
		
		if ($this->user_id) {
			$profile = UserProfile::model()->findByAttributes(array("uid" => $this->user_id));
				
			if (is_object($profile)) $email = $profile->email;
		}
		
		return $email;
	}
	
	public function getDateLastAnswer() {
		if ($this->date_last_answer) return date('d.m.Y H:i:s', $this->date_last_answer);
		
		return '';
	}
	
	public function getNewAnswer() {
		if (Yii::app()->user->checkAccess('admin')) {
			$count = UserMessage::model()->countByAttributes(array('user_dialog_id' => $this->primaryKey, 'user_id' => $this->user_id, 'readed_admin' => 0));
		} else {
			$count = Yii::app()->db->createCommand()
	    	->select('count(*) as count')
	    	->from('user_message u1')
	    	->join('user_message_dialog u2', 'u1.user_dialog_id = u2.id')
	    	->where('u1.user_dialog_id = '.$this->primaryKey.' AND u2.user_id = '.$this->user_id.' AND u1.user_id != u2.user_id AND readed_user = 0')
	    	->queryScalar();
		}
		
		return intval($count) > 0 ? '<span style="color: red;">'.Yii::t('messages', 'Yes').'</span>' : '<span style="color: green;">'.Yii::t('messages', 'No').'</span>';
	}
	
	public function search() {
		$criteria = new CDbCriteria;
	
		$criteria->compare('id', $this->id);
		
		$criteria->compare('theme', $this->theme, true);
		
		if ($this->email) {
			//file_put_contents('/var/www/partexpert_beta/data/www/beta.partexpert.ru/filter.txt', $this->email);
			
			$uid = array();
			
			$q = new CDbCriteria();
			$q->addSearchCondition('email', $this->email, true);
			
			$profiles = UserProfile::model()->findAll($q);
			$count = count($profiles);
			for ($i = 0; $i < $count; $i ++) {
				$uid[] = $profiles[$i]->uid;
			}
			
			if (count($uid)) $criteria->addInCondition('user_id', $uid);
		}
		
		if ($this->user_id) {
			$criteria->addCondition('user_id = '.$this->user_id);
		}
		
		if ($this->date_start) {
			$date_start = @strtotime($this->date_start);
			$date_end = $date_start + 3600 * 24;
			
			$criteria->compare('date_start', '>='.$date_start, true);
			$criteria->compare('date_start', '<='.$date_end, true);
		}
		
		if ($this->date_last_answer) {
			$date_last_answer = @strtotime($this->date_last_answer);
			$date_end = $date_last_answer + 3600 * 24;
			
			$criteria->compare('date_last_answer', '>='.$date_last_answer, true);
			$criteria->compare('date_last_answer', '<='.$date_end, true);
		}
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'IF(date_last_answer IS NULL, date_start, date_last_answer) DESC',
			),
		));
	}
}