<?php
/**
 * This is the model class for table "user_profile".
 *
 * The followings are the available columns in table 'user_profile':
 * @property integer $id
 * @property integer $uid
 * @property integer $merged
 * @property string $first_name
 * @property string $second_name
 * @property string $father_name
 * @property integer $legal_entity
 * @property string $phone
 * @property string $extra_phone
 * @property string $organization_name
 * @property string $organization_inn
 * @property string $bank_kpp
 * @property string $bank_bik
 * @property string $bank
 * @property string $bank_rc
 * @property string $bank_ks
 * @property string $organization_director
 * @property string $delivery_zipcode
 * @property string $delivery_city
 * @property string $delivery_country
 * @property string $delivery_street
 * @property string $delivery_house
 * @property string $legal_zipcode
 * @property string $legal_city
 * @property string $legal_country
 * @property string $legal_street
 * @property string $legal_house
 * @property string $email
 * @property float $balance
 * @property float $price_group
 * @property float $comment
 * @property string $organization_type
 * @property string $skype
 * @property STRING $organization_ogrn
 * @property string $ogrnip
 * @property string $okpo
 * @property boolean $stop_list_state
 * @property string $stop_list_period
 * @property string $1c_id
 * @property integer $update_status
 */
class UserProfile extends LUserModel {
	public $admin_email = '';
	public $admin_password = '';
	public $orders_count = '';
	public $cars_count = '';
	public $orders_done_count = '';
	public $items_count = '';
	public $reg_password = '';
	public $reg_password2 = '';
	public $fio = '';
	public $ic_work_rules_state = 0;
	public $messages;
	public function afterSave() {
		parent::afterSave();
		
		Yii::app()->db->createCommand(" UPDATE `lily_account` SET id='$this->email' WHERE id like '%@%' AND `service`='email' AND uid='$this->uid' LIMIT 1")->query();
	}
	public function beforeSave() {
		if(parent::beforeSave()) {
			if($this->isNewRecord) {
				if($this->legal_entity == 0)
					$this->price_group = Yii::app()->config->get('Site.Reg.FizPrice');
				if($this->legal_entity == 1)
					$this->price_group = Yii::app()->config->get('Site.Reg.UrPrice');
				if($this->legal_entity == 2)
					$this->price_group = Yii::app()->config->get('Site.Reg.IpPrice');
			}
			$this->update_status = 1;
			
			return true;
		}
		return false;
	}
	public static function getName(LUser $user = null) {
		if($user != NULL)
			return isset($user->profile->first_name) && isset($user->profile->second_name) ? $user->profile->second_name.' '.$user->profile->first_name : Yii::t("userControl", 'Name not set');
		else
			return 'not set';
	}
	public function getFullNameId() {
		return $this->second_name.' '.$this->first_name.' ID='.$this->id.'('.$this->email.')';
	}
	
	/**
	 *
	 * @return type
	 */
	public function getFullName() {
		return $this->second_name.' '.$this->first_name;
	}
	public function getFullNameOrg() {
		return $this->second_name.' '.$this->first_name .(! empty($this->organization_name) ? '('.$this->organization_name.')' : '').' '.$this->uid;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function checkStopList() {
		if(Yii::app()->config->get('StopList.Active') == '1' && $this->stop_list_state == 1) {
			return false;
		}
		return true;
	}
	public function getStatusOrders() {
		$data = Yii::app()->db->createCommand('SELECT count(*) AS `orders_count`, `status` FROM `'.Orders::model()->tableName()."` WHERE user_id='$this->uid' GROUP BY `status`")->queryAll();
		$result = array();
		foreach($data as $value) {
			$result [$value ['status']] = $value ['orders_count'];
		}
		return $result;
	}
	
	/**
	 *
	 * @param type $value        	
	 * @return boolean
	 */
	public function isCanPay($value) {
		if($this->balance - $value >= 0) {
			return true;
		}
		return false;
	}
	
	/**
	 *
	 * @param type $data        	
	 */
	public function importStopListData($data, $ic_data = array()) {
		$ids = array(
				0 
		);
		foreach($data as $k => $v) {
			$ids [] = ' `uid`='.$k;
		}
		foreach($ic_data as $k => $v) {
			$ids [] = ' `1c_id`=\''.$k.'\' ';
		}
		
		$users = Yii::app()->db->createCommand('SELECT `uid`, `1c_id` FROM `'.$this->tableName().'` WHERE('.implode(' OR ', $ids).')')->queryAll();
		$ids = array();
		
		// print_r($users);
		foreach($users as $k) {
			if(! isset($data [$k ['uid']]))
				$data [$k ['uid']] = $ic_data [$k ['1c_id']];
			Yii::app()->db->createCommand('UPDATE `'.$this->tableName().'` SET `stop_list_period`=\''.$data [$k ['uid']].'\', `stop_list_state`=\'' .($data [$k ['uid']] > 0 ? 1 : 0).'\'  WHERE `uid`='.$k ['uid'].' LIMIT 1')->query();
		}
	}
	public function import1CBalanceOperations($file) {
		$file = file_get_contents($file);
		$dom = new DOMDocument();
		$dom->loadXML($file);
		$elements = $dom->getElementsByTagName('Контрагент');
		$elemements_types = array(
				'comment' => 'МетодОплаты',
				'sum' => 'Сумма',
				'date' => 'Дата' 
		);
		
		foreach($elements as $element) {
			$elem = XmlWork::getArray($element);
			$data = array();
			// print_r()
			if(! isset($elem ['СайтИД'])) {
				continue;
			}
			// print_r($elem);
			$model = UserProfile::model()->findByAttributes(array(
					'uid' => $elem ['СайтИД'] [0] ['#text'] 
			));
			if($model != NULL && isset($elem ['Операции'] [0] ['Операция'])) {
				foreach($elem ['Операции'] [0] ['Операция'] as $operation) {
					foreach($elemements_types as $key => $value) {
						$data [$key] =(isset($operation [$value] [0] ['#text']) ? $operation [$value] [0] ['#text'] : $operation [$value] [0] ['#cdata-section']);
					}
					$data ['sum'] = str_replace(array(
							',' 
					), array(
							'.' 
					), $data ['sum']);
					$data ['sum'] = preg_replace("/[^\-\.0-9]/", "", $data ['sum']);
					$model->addMoneyOperation(floatval($data ['sum']), $data ['comment'], 0, $data ['date']);
				}
			}
		}
	}
	
	/**
	 *
	 * @param type $file        	
	 * @return type
	 */
	public function import1CStopList($file) {
		$file = file_get_contents($file);
		$dom = new DOMDocument();
		$dom->loadXML($file);
		$elements = $dom->getElementsByTagName('Контрагент');
		$elemements_types = array(
				'id' => 'СайтИД',
				'1c_id' => 'ИД',
				'statusDays' => 'ДнейОтсрочкиОстаток' 
		);
		$search = array(
				"\\",
				"\x00",
				"\n",
				"\r",
				"'",
				'"',
				"\x1a" 
		);
		$replace = array(
				"\\\\",
				"\\0",
				"\\n",
				"\\r",
				"\'",
				'\"',
				"\\Z" 
		);
		$ids = array();
		$ids_ic = array();
		foreach($elements as $element) {
			$elem = XmlWork::getArray($element);
			$data = array();
			foreach($elemements_types as $key => $value) {
				$data [$key] = str_replace($search, $replace,(isset($elem [$value] [0] ['#text']) ? $elem [$value] [0] ['#text'] : $elem [$value] [0] ['#cdata-section']));
			}
			if(! empty($data ['id']))
				$ids [$data ['id']] = $data ['statusDays'];
			if(! empty($data ['1c_id']))
				$ids_ic [$data ['1c_id']] = $data ['statusDays'];
		}
		// print_r($ids);
		// print_r($ids_ic);
		UserProfile::model()->importStopListData($ids, $ids_ic);
	}
	public function get1cOperationsFieldList() {
		return array(
				'comment' => 'МетодОплаты',
				'value' => 'Сумма',
				'create_time' => 'Дата',
				'order_id' => 'ЗаказИД' 
		);
	}
	public function get1cFieldList() {
		return array(
				'uid' => 'СайтИд',
				'organization_name' => 'Наименование',
				'organization_type' => 'ОфициальноеНаименование',
				'organization_inn' => 'ИНН',
				'balance' => 'Баланс',
				'fio' => array(
						'second_name' => 'Фамилия',
						'first_name' => 'Имя',
						'father_name' => 'Отчество' 
				),
				'legal_entity' => 'ТипКонтрагента',
				'legal_adress' => array(
						'legal_house' => 'Дом',
						'legal_street' => 'Улица',
						'legal_city' => 'Город',
						'legal_zipcode' => 'Индекс' 
				),
				'delivery_adress' => array(
						'delivery_house' => 'Дом',
						'delivery_street' => 'Улица',
						'delivery_city' => 'Город',
						'delivery_zipcode' => 'Индекс' 
				),
				'contact' => array(
						'email' => 'Почта',
						'phone' => 'Телефон' 
				),
				'bank' => array(
						'bank' => 'Наименование',
						'bank_bik' => 'БИК',
						'bank_ks' => 'СчетКорреспондентский' 
				),
				'okpo' => 'ОКПО',
				'bank_kpp' => 'КПП',
				'1c_id' => 'ИД' 
			// '' => '',
		);
	}
	
	/**
	 *
	 * @param type $file        	
	 */
	public function import1CUsers($file) {
		$file = file_get_contents($file);
		$dom = new DOMDocument();
		$dom->loadXML($file);
		$elements = $dom->getElementsByTagName('Контрагент');
		$elemements_types = $this->get1cFieldList();
		$search = array(
				"\\",
				"\x00",
				"\n",
				"\r",
				"'",
				'"',
				"\x1a" 
		);
		$replace = array(
				"\\\\",
				"\\0",
				"\\n",
				"\\r",
				"\'",
				'\"',
				"\\Z" 
		);
		$new_ids = array();
		foreach($elements as $element) {
			$elem = XmlWork::getArray($element);
			// print_r($elem);
			// die;
			$data = array();
			foreach($elemements_types as $key => $value) {
				
				if($key == 'contact') {
					if(isset($elem ['Контакты'] [0] ['Контакт'])) {
						$temp2 = $elem ['Контакты'] [0] ['Контакт'];
						$keys = array_flip($value);
						
						foreach($temp2 as $key3 => $temp) {
							if(isset($keys [trim($temp ['Тип'] [0] ['#text'])]))
								$data [$keys [trim($temp ['Тип'] [0] ['#text'])]] =(isset($temp ['Значение'] [0] ['#text']) ? $temp ['Значение'] [0] ['#text'] : $temp ['Значение'] [0] ['#cdata-section']);
						}
					}
				} else if($key == 'bank') {
					if(isset($elem ['РасчетныеСчета'])) {
						// if(!isset($elem['РасчетныеСчета'][0]['РасчетныйСчет'][0]['Банк'][0]))
						// print_r($elem);
						
						$temp = $elem ['РасчетныеСчета'] [0] ['РасчетныйСчет'] [0] ['Банк'] [0];
						
						foreach($value as $key2 => $value2) {
							if(! isset($temp [$value2] [0] ['#text']))
								continue;
							$data [$key2] =(isset($temp [$value2] [0] ['#text']) ? $temp [$value2] [0] ['#text'] : $temp [$value2] [0] ['#cdata-section']);
						}
						$temp = $elem ['РасчетныеСчета'] [0] ['РасчетныйСчет'] [0];
						
						$data ['bank_rc'] =(isset($temp ['НомерСчета'] [0] ['#text']) ? $temp ['НомерСчета'] [0] ['#text'] : $temp ['НомерСчета'] [0] ['#cdata-section']);
						// print_r($temp);
						// die;
					}
				} else if($key == 'legal_adress') {
					if(! isset($elem ['ЮридическийАдрес']))
						continue;
					$temp2 = $elem ['ЮридическийАдрес'] [0] ['АдресноеПоле'];
					// print_r($temp2);
					$keys = array_flip($value);
					foreach($temp2 as $temp) {
						// print_r($temp);
						if(isset($keys [trim($temp ['Тип'] [0] ['#text'])]) && isset($temp ['Значение'] [0] ['#text']))
							$data [$keys [trim(isset($temp ['Тип'] [0] ['#text']) ? $temp ['Тип'] [0] ['#text'] : $temp ['Тип'] [0] ['#cdata-section'])]] =(isset($temp ['Значение'] [0] ['#text']) ? $temp ['Значение'] [0] ['#text'] : $temp ['Значение'] [0] ['#cdata-section']);
					}
				} else if($key == 'delivery_adress') {
					if(! isset($elem ['ФактическийАдрес']))
						continue;
					$temp2 = $elem ['ФактическийАдрес'] [0] ['АдресноеПоле'];
					$keys = array_flip($value);
					foreach($temp2 as $key3 => $temp) {
						if(isset($keys [trim($temp ['Тип'] [0] ['#text'])]) && isset($temp ['Значение'] [0] ['#text']))
							$data [$keys [trim(isset($temp ['Тип'] [0] ['#text']) ? $temp ['Тип'] [0] ['#text'] : $temp ['Тип'] [0] ['#cdata-section'])]] =(isset($temp ['Значение'] [0] ['#text']) ? $temp ['Значение'] [0] ['#text'] : $temp ['Значение'] [0] ['#cdata-section']);
					}
				} else if($key == 'fio') {
					$temp = $elem ['Представители'] [0] ['Представитель'] [0] ['КонтактКонтрагента'] [0];
					foreach($value as $key2 => $value2) {
						if(! isset($temp [$value2] [0])) {
							continue;
						}
						$data [$key2] =(isset($temp [$value2] [0] ['#text']) ? $temp [$value2] [0] ['#text'] : $temp [$value2] [0] ['#cdata-section']);
					}
				} else if($value == 'ТипКонтрагента') {
					$data [$key] =(isset($elem [$value] [0] ['#text']) ? $elem [$value] [0] ['#text'] : $elem [$value] [0] ['#cdata-section']);
					// $data[$key] = str_replace($search, $replace,(isset($elem[$value][0]['#text']) ? $elem[$value][0]['#text'] : $elem[$value][0]['#cdata-section']));
					
					if($data [$key] == 'Юр. лицо') {
						$data [$key] = 1;
					} else if($data [$key] == 'ИП') {
						$data [$key] = 2;
					} else
						$data [$key] = 0;
				} else {
					if(! isset($elem [$value]))
						continue;
					if(empty($elem [$value]))
						continue;
					$data [$key] =(isset($elem [$value] [0] ['#text']) ? $elem [$value] [0] ['#text'] : $elem [$value] [0] ['#cdata-section']);
				}
			}
			;
			if(! isset($data ['uid']))
				$data ['uid'] = 0;
			$model = UserProfile::model()->findByAttributes(array(
					'uid' => $data ['uid'] 
			));
			if($model == NULL) {
				$model = UserProfile::model()->findByAttributes(array(
						'1c_id' => $data ['1c_id'] 
				));
				if($model == NULL) {
					$new_ids [] = $data;
					continue;
				}
			} else {
				
				$model->ic_work_rules_state = 1;
				$model->attributes = $data;
				$model->save();
			}
		}
		$result = UserProfile::model()->insertNewUsers($new_ids);
		// return UserProfile::model()->exportUsers1c($result, $elemements_types);
	}
	
	/**
	 *
	 * @param type $data        	
	 * @return array
	 */
	public function insertNewUsers($data) {
		$result = array();
		foreach($data as $key => $value) {
			$model_profile = new UserProfile();
			$model_profile->ic_work_rules_state = 1;
			if($model_profile->legal_entity != '1' && $model_profile->legal_entity != '2' && $model_profile->legal_entity != '0')
				$model_profile->legal_entity = '0';
			$model_profile->attributes = $value;
			if(empty($model_profile->email)) {
				$email = substr(md5(time().$model_profile->first_name.$model_profile->organization_name.$model_profile->second_name), 0, 12).'@autouser.net';
				$model_profile->email = $email;
			}
			$password = md5($model_profile->email.$model_profile->first_name.time().$model_profile->organization_name);
			if($model_profile->validate()) {
				$authIdentity = new LEmailService();
				$authIdentity->email = $model_profile->email;
				$authIdentity->password = $password;
				$authIdentity->user = null;
				$authIdentity->rememberMe = false;
				if($authIdentity->authenticate(true, true, true)) {
					$identity = new LUserIdentity($authIdentity);
					$identity->authenticate();
					$model_profile->uid = $identity->account->uid;
					$model_profile->save();
					Yii::app()->db->createCommand("UPDATE `lily_user` SET inited='1' WHERE uid='$model_profile->uid' LIMIT 1")->query();
					$result [] = $model_profile->uid;
				}
				// Special redirect to fire popup window closing
				// $authIdentity->cancel();
			}
		}
		return $result;
	}
	
	/**
	 *
	 * @param type $ids        	
	 * @param type $elements        	
	 * @return string
	 */
	public function exportUsers1c($ids, $elements) {
		$result = '<?xml version="1.0" encoding="UTF-8"?><КоммерческаяИнформация ВерсияСхемы="2.05" ДатаФормирования="'.date('Y-m-d\TH:i:s').'">'.'<Документ><Номер>0</Номер><Контрагенты>';
		$implode = array(
				0 
		);
		foreach($ids as $v) {
			$implode [$v] = " `uid` = '$v' ";
		}
		$data = null;
		if(! empty($ids)) {
			$data = Yii::app()->db->createCommand("SELECT * FROM `".$this->tableName()."` WHERE ".implode(' OR ', $implode))->queryAll();
			Yii::app()->db->createCommand("UPDATE `".$this->tableName()."` SET `update_status`='0' WHERE ".implode(' OR ', $implode))->query();
		} else {
			$data = Yii::app()->db->createCommand("SELECT * FROM `".$this->tableName()."` WHERE update_status='1' ")->queryAll();
			Yii::app()->db->createCommand("UPDATE `".$this->tableName()."` SET `update_status`='0' WHERE update_status='1'")->query();
		}
		foreach($data as $value) {
			$result .= '<Контрагент>';
			foreach($elements as $k => $v) {
				
				if($k == 'organization_name' || $k == 'organization_type') {
					if($value ['legal_entity'] == '0') {
						$result .= "<$v>$value[second_name] $value[first_name] $value[father_name]</$v>";
					} else {
						$result .= "<$v>$value[$k]</$v>";
					}
				} else if($k == 'contact') {
					$result .= "<Контакты>";
					foreach($v as $k2 => $v2) {
						$result .= "<Контакт>
						<Тип>$v2</Тип>
						<Значение>$value[$k2]</Значение> 
					</Контакт>";
					}
					$result .= "</Контакты>";
				} else if($k == 'bank') {
					$result .= "<РасчетныеСчета>
					<РасчетныйСчет>
						<НомерСчета>$value[bank_rc]</НомерСчета>
						<Банк>
														";
					foreach($v as $k2 => $v2) {
						$result .= "<$v2>$value[$k2]</$v2>";
					}
					$result .= "</Банк>
					</РасчетныйСчет>
				</РасчетныеСчета>";
				} else if($k == 'delivery_adress') {
					$result .= "<ФактическийАдрес>";
					foreach($v as $k2 => $v2) {
						$result .= "<АдресноеПоле>
						<Тип>$v2</Тип>
						<Значение>$value[$k2]</Значение> 
					</АдресноеПоле>";
					}
					$result .= "</ФактическийАдрес>";
				} else if($k == 'legal_adress') {
					$result .= "<ЮридическийАдрес>";
					foreach($v as $k2 => $v2) {
						$result .= "<АдресноеПоле>
						<Тип>$v2</Тип>
						<Значение>$value[$k2]</Значение> 
					</АдресноеПоле>";
					}
					$result .= "</ЮридическийАдрес>";
				} else if($k == 'fio') {
					$result .= "<Представители>
					<Представитель>
						<КонтактКонтрагента>";
					foreach($v as $k2 => $v2) {
						$result .= "<$v2>$value[$k2]</$v2>";
					}
					$result .= "</КонтактКонтрагента>
					</Представитель>
				</Представители>";
				} else if($k == 'legal_entity') {
					if($value [$k] == '0') {
						$value [$k] = 'Физ. лицо';
					} elseif($value [$k] == '1') {
						$value [$k] = 'Юр. лицо';
					} else {
						$value [$k] = '';
					}
					$result .= "<$v>$value[$k]</$v>";
				} else {
					$result .= "<$v>$value[$k]</$v>";
				}
			}
			$result .= '</Контрагент>';
		}
		$result .= '</Контрагенты>
	</Документ></КоммерческаяИнформация>';
		return $result;
	}
	
	/**
	 *
	 * @param type $elements        	
	 * @param type $last_date        	
	 * @return string
	 */
	public function exportUsersOperations1c($elements, $last_date = '') {
		$result = '<?xml version="1.0" encoding="UTF-8"?><КоммерческаяИнформация ВерсияСхемы="2.05" ДатаФормирования="'.date('Y-m-d\TH:i:s').'">';
		if(! empty($last_date))
			$data = Yii::app()->db->createCommand("SELECT * FROM `".UserBalanceOperations::model()->tableName()."` WHERE `create_time`>'$last_date' ORDER BY `user_id`")->queryAll();
		else {
			// $data = Yii::app()->db->createCommand("SELECT * FROM `".UserBalanceOperations::model()->tableName()."` WHERE `create_time`>'$last_date' ORDER BY `user_id`")->queryAll();
			
			$data = Yii::app()->db->createCommand("SELECT * FROM `".UserBalanceOperations::model()->tableName()."`  WHERE update_status='1' ORDER BY `user_id` ")->queryAll();
			Yii::app()->db->createCommand("UPDATE `".UserBalanceOperations::model()->tableName()."` SET `update_status`='0' WHERE update_status='1'")->query();
		}
		$first = false;
		$prev_id = - 1;
		foreach($data as $value) {
			// print_r($value);
			if($prev_id == - 1) {
				$prev_id = $value ['user_id'];
			}
			if($prev_id != $value ['user_id']) {
				$first = FALSE;
				$result .= "</Операции></Контрагент>\n";
			}
			if((! $first)) {
				$result .= "<Контрагент><СайтИД>$value[user_id]</СайтИД><Операции>";
				$first = TRUE;
			}
			// $result.='</Контрагент>';
			$result .= '<Операция>';
			foreach($elements as $k => $v) {
				if($k == 'create_time') {
					$result .= "<$v>".date('YmdHis', $value [$k])."</$v>\n";
				} else
					$result .= "<$v>$value[$k]</$v>";
			}
			$result .= '</Операция>';
		}
		if($first)
			$result .= "</Операции></Контрагент>\n";
		$result .= '</КоммерческаяИнформация>';
		return $result;
	}
	
	/**
	 */
	public function afterConstruct() {
		parent::afterConstruct();
		$model = LAccount::model()->findByAttributes(array(
				'uid' => Yii::app()->user->id,
				'service' => 'email' 
		));
		if(empty($this->email) && $model != NULL)
			$this->email = $model->id;
		if(empty($this->legal_entity))
			$this->legal_entity = 0;
	}
	
	/**
	 *
	 * @param LMergeEvent $event        	
	 */
	public function onUserMerge(LMergeEvent $event) {
		parent::onUserMerge($event);
		$this->merged = 1;
		$this->save();
	}
	
	public static function getUserOrderInfo($id = '') {
		if(empty($id)) {
			return '';
		}
		$info = '';
		// if(empty($id)) {
		// $info.=(!empty($this->organization_name) ? $this->organization_name.';<br/>' : '');
		// $info.=" $this->second_name $this->first_name;
		// <br/> $this->email;
		// <br/> $this->phone;
		// <br/> ";
		// $info.=(!empty($this->extra_phone) ? $this->extra_phone.';<br/>' : '');
		// $info.=Yii::t('userControl', 'Balance user').': '.$this->balance;
		// } else
		{
			$model = UserProfile::model()->findByAttributes(array(
					'uid' => $id 
			));
			if($model != NULL) {
				$info .=(! empty($model->organization_name) ? $model->organization_name.';<br/>' : '');
				$info .= " $model->second_name $model->first_name;
<br/> $model->email;
<br/> $model->phone;
<br/> ";
				$info .=(! empty($model->extra_phone) ? $model->extra_phone.';<br/>' : '');
				$info .=($model->balance < 0 ? '<span style="color:red">' : '').Yii::t('userControl', 'Balance user').': ' .(! empty($model->balance) ? $model->balance : 0) .($model->balance < 0 ? '</span>' : '');
			}
		}
		return $info;
	}
	
	public function getEmailText() {
		$txt = Yii::t('userControl', 'Information about client').'<br>';
		
		$txt .= "<strong>".Yii::t('userControl', 'Telephone number')."</strong> - $this->phone<br>\n".'<strong>'.Yii::t('userControl', 'FULL NAME').'</strong> - '.$this->getFullName()."<br>\n";
		return $txt;
	}
	
	public function getUserBlockInfo() {
		$info = '';
		$info .=(! empty($this->organization_name) ? $this->organization_name.';<br/>' : '');
		$info .= " $this->second_name $this->first_name;
<br/> $this->email;
<br/> $this->phone;
<br/> ";
		$info .=(! empty($this->extra_phone) ? $this->extra_phone.';<br/>' : '');
		$info .= Yii::t('userControl', 'Balance user').': '.$this->balance;
		return $info;
	}
	
	/**
	 *
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'user_profile';
	}
	public function checkuniqueemail($attribute) {
		$cmd = Yii::app()->db->createCommand();
		
		if(! $this->isNewRecord && $this->uid) {
			$row = $cmd->select('uid')->from('lily_account')->where('id=:id AND uid!=:uid', array(
					':id' => $this->email,
					':uid' => $this->uid 
			))->queryRow();
		} else {
			$row = $cmd->select('uid')->from('lily_account')->where('id=:id', array(
					':id' => $this->email 
			))->queryRow();
		}
		
		if(is_array($row) && array_key_exists('uid', $row) &&($row ['uid'] != $this->uid)) {
			$this->addError($attribute, Yii::t('userControl', 'This email address is already registered in the system'));
		} else {
			$cmd = Yii::app()->db->createCommand();
			
			if(! $this->isNewRecord && $this->uid) {
				$row = $cmd->select('uid')->from('user_profile')->where('email=:email AND uid!=:uid', array(
						':email' => $this->email,
						':uid' => $this->uid 
				))->queryRow();
			} else {
				$row = $cmd->select('uid')->from('user_profile')->where('email=:email', array(
						':email' => $this->email 
				))->queryRow();
			}
			
			if(is_array($row) && array_key_exists('uid', $row) &&($row ['uid'] != $this->uid)) {
				$this->addError($attribute, Yii::t('userControl', 'This email address is already registered in the system'));
			}
		}
	}
	
	/**
	 *
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array(
						'first_name, second_name, phone, email',
						'required',
						'on' => 'main' 
				),
				array(
						'first_name, second_name, phone, email,reg_password',
						'required',
						'on' => 'main_regs, fast_regs' 
				),
				
				// array('email', 'checkuniqueemail', 'on' => 'fast_regs'),
				// array('email', 'checkuniqueemail', 'on' => 'main_regs'),
				// array('email', 'unique'),
				array(
						'email',
						'checkuniqueemail' 
				),
				
				array(
						'email',
						'email' 
				),
				array(
						'legal_entity',
						'ext.YiiConditionalValidator.YiiConditionalValidator',
						'if' => array(
								array(
										'legal_entity',
										'compare',
										'compareValue' => '1' 
								),
								array(
										'ic_work_rules_state',
										'compare',
										'compareValue' => '0' 
								),
								array(
										'scenario',
										'compare',
										'compareValue' => 'main' 
								) 
						),
						'then' => array(
								array(
										'organization_inn, organization_type, organization_name, legal_street, legal_city, legal_house, bank_bik, bank_rc, organization_director',
										'required' 
								) 
						) 
				),
				array(
						'legal_entity',
						'ext.YiiConditionalValidator.YiiConditionalValidator',
						'if' => array(
								array(
										'legal_entity',
										'compare',
										'compareValue' => '1' 
								),
								array(
										'ic_work_rules_state',
										'compare',
										'compareValue' => '0' 
								),
								array(
										'scenario',
										'compare',
										'compareValue' => 'main_regs' 
								) 
						),
						'then' => array(
								array(
										'organization_inn, organization_type, organization_name, legal_street, legal_city, legal_house, bank_bik, bank_rc, organization_director',
										'required' 
								) 
						) 
				),
				array(
						'first_name, second_name, phone, email',
						'safe' 
				),
				array(
						'organization_inn, organization_type, organization_name, legal_street, legal_city, legal_house, bank_bik, bank_rc, organization_director',
						'safe' 
				),
				array(
						'organization_inn, organization_type, organization_name, legal_street, legal_city, legal_house, bank_bik, bank_rc, organization_director',
						'required',
						'on' => 'main_form' 
				),
				array(
						'organization_inn',
						'required',
						'on' => 'legal' 
				),
				array(
						'uid, merged, legal_entity, update_status, discount, manager, city',
						'numerical',
						'integerOnly' => true 
				),
				array(
						'balance, price_group, currency_type, comment, extra_phone, stop_list_state, stop_list_period, discount',
						'safe' 
				),
				// array('phone, extra_phone', 'match', 'pattern' => '/^([+]?[0-9 ]+)$/'),
				array(
						'first_name, second_name,organization_ogrn,ogrnip, okpo, father_name, skype, organization_name, organization_type, organization_inn, bank_kpp, bank_bik, bank, bank_rc, bank_ks, organization_director, delivery_zipcode, delivery_city, delivery_country, delivery_street, delivery_house, legal_zipcode, legal_city, legal_country, legal_street, legal_house, 1c_id',
						'length',
						'max' => 255 
				),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array(
						'id,fio, balance, price_group, currency_type, skype,ogrnip, okpo, organization_ogrn, uid, merged, organization_type, first_name,phone, extra_phone, second_name, father_name, legal_entity, phone, extra_phone, organization_name, organization_inn, bank_kpp, bank_bik, bank, bank_rc, bank_ks, organization_director, delivery_zipcode, delivery_city, delivery_country, delivery_street, update_status, 1c_id, delivery_house, legal_zipcode, legal_city, legal_country, legal_street, legal_house, manager, city',
						'safe',
						'on' => 'search' 
				) 
		);
	}
	
	/**
	 *
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'user' => array(
						self::BELONGS_TO,
						'LUser',
						'uid' 
				),
				'userCars' => array(
						self::HAS_MANY,
						'UsersCars',
						array(
								'user_id' => 'uid' 
						) 
				) 
		);
	}
	
	/**
	 *
	 * @return array customized attribute labels(name=>label)
	 */
	public function attributeLabels() {
		return array(
				'id' => 'ID',
				'uid' => 'ID',
				'merged' => 'Merged',
				'bank_name' => Yii::t('userControl', 'Banking data'),
				'first_name' => Yii::t('userControl', 'Name'),
				'second_name' => Yii::t('userControl', 'Surname'),
				'father_name' => Yii::t('userControl', 'Middle Name'),
				'legal_entity' => Yii::t('userControl', 'Type of organization.'),
				'legal_entity1' => Yii::t('userControl', 'Physical person'),
				'legal_entity2' => Yii::t('userControl', 'Legal entity'),
				'legal_entity3' => Yii::t('userControl', 'ST'),
				'phone' => Yii::t('userControl', 'Contact phone number'),
				'extra_phone' => Yii::t('userControl', 'Add. Contact phone number'),
				'organization_name' => Yii::t('userControl', 'Organization'),
				'organization_inn' => Yii::t('userControl', 'TIN'),
				'bank_kpp' => Yii::t('userControl', 'CRR'),
				'bank_bik' => Yii::t('userControl', 'BIC'),
				'bank' => Yii::t('userControl', 'Bank'),
				'bank_rc' => Yii::t('userControl', 'Settlement account'),
				'bank_ks' => Yii::t('userControl', 'correspondent account'),
				'organization_director' => Yii::t('userControl', 'Director/CEO'),
				'delivery' => Yii::t('userControl', 'Address'),
				'delivery_zipcode' => Yii::t('userControl', 'Index (simplifies delivery)'),
				'delivery_city' => Yii::t('userControl', 'City'),
				'delivery_country' => Yii::t('userControl', 'Country'),
				'delivery_street' => Yii::t('userControl', 'Street'),
				'delivery_house' => Yii::t('userControl', 'Home\Housing\structure\apart. (office)'),
				'legal' => Yii::t('userControl', 'Registered office'),
				'legal_zipcode' => Yii::t('userControl', 'Index'),
				'legal_city' => Yii::t('userControl', 'City'),
				'legal_country' => Yii::t('userControl', 'Country'),
				'legal_street' => Yii::t('userControl', 'Street'),
				'legal_house' => Yii::t('userControl', 'Home\Housing\structure\apart. (office)'),
				'email' => Yii::t('userControl', 'Email'),
				'balance' => Yii::t('userControl', 'Balance'),
				'price_group' => Yii::t('userControl', 'Price group'),
				'currency_type' => Yii::t('userControl', 'Currency type'),
				'fio' => Yii::t('userControl', 'FULL NAME'),
				'fullName' => Yii::t('userControl', 'FULL NAME'),
				'orders_count' => Yii::t('userControl', 'Orders'),
				'items_count' => Yii::t('userControl', 'Products'),
				'cars_count' => Yii::t('userControl', 'Cars'),
				'comment' => Yii::t('userControl', 'Note'),
				'organization_type' => Yii::t('userControl', 'Full name of organization'),
				'skype' => Yii::t('userControl', 'Skype'),
				'organization_ogrn' => Yii::t('userControl', 'BIN organization'),
				'okpo' => Yii::t('userControl', 'RNNBO'),
				'ogrnip' => Yii::t('userControl', 'PSRN ST'),
				'stop_list_state' => Yii::t('userControl', 'Stop-list state'),
				'stop_list_period' => Yii::t('userControl', 'Stop-list term'),
				'reg_password' => Yii::t('userControl', 'Password'),
				'reg_password2' => Yii::t('userControl', 'Repeat password'),
				'discount' => Yii::t('userControl', 'Discount'),
				'messages' => Yii::t('menu', 'Messages'),
				'manager' => Yii::t('userControl', 'Manager'),
				'city' => Yii::t('cities', 'City'),
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 *         based on the search/filter conditions.
	 */
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched .
		$criteria = new CDbCriteria();
		
		if(! empty($this->fio))
			$criteria->compare('concat(second_name,\' \',first_name)', $this->fio, true);
		$criteria->select .= ', concat(second_name,\' \',first_name) AS fio ';
		
		$criteria->select .= ',(SELECT count(*) FROM `'.UsersCars::model()->tableName().'` WHERE user_id=uid) AS cars_count,(SELECT count(*) FROM `'.Orders::model()->tableName().'` WHERE user_id=uid) AS orders_count,(SELECT count(*) FROM `'.Items::model()->tableName().'` WHERE user_id=uid) AS items_count ';
		
		$criteria->compare('id', $this->id);
		$criteria->compare('uid', $this->uid);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('balance', $this->balance);
		$criteria->compare('merged', $this->merged);
		$criteria->compare('first_name', $this->first_name, true);
		$criteria->compare('second_name', $this->second_name, true);
		$criteria->compare('legal_entity', $this->legal_entity);
		$criteria->compare('phone', $this->phone, true);
		$criteria->compare('extra_phone', $this->extra_phone, true);
		$criteria->compare('organization_name', $this->organization_name, true);
		$criteria->compare('organization_inn', $this->organization_inn, true);
		$criteria->compare('bank_kpp', $this->bank_kpp, true);
		$criteria->compare('bank_bik', $this->bank_bik, true);
		$criteria->compare('bank', $this->bank, true);
		$criteria->compare('bank_rc', $this->bank_rc, true);
		$criteria->compare('bank_ks', $this->bank_ks, true);
		$criteria->compare('organization_director', $this->organization_director, true);
		$criteria->compare('delivery_zipcode', $this->delivery_zipcode, true);
		$criteria->compare('delivery_city', $this->delivery_city, true);
		$criteria->compare('delivery_country', $this->delivery_country, true);
		$criteria->compare('delivery_street', $this->delivery_street, true);
		$criteria->compare('delivery_house', $this->delivery_house, true);
		$criteria->compare('legal_zipcode', $this->legal_zipcode, true);
		$criteria->compare('legal_city', $this->legal_city, true);
		$criteria->compare('legal_country', $this->legal_country, true);
		$criteria->compare('legal_street', $this->legal_street, true);
		$criteria->compare('legal_house', $this->legal_house, true);
		
		return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
				'pagination' => array(
						'pageSize' => 20 
				),
				'sort' => array(
						'defaultOrder' => 'id DESC',
						'attributes' => array(
								'fio' => array(
										'asc' => 'fio ASC',
										'desc' => 'fio DESC' 
								),
								'orders_count' => array(
										'asc' => 'orders_count ASC',
										'desc' => 'orders_count DESC' 
								),
								'cars_count' => array(
										'asc' => 'cars ASC',
										'desc' => 'cars DESC' 
								),
								'items_count' => array(
										'asc' => 'items_count ASC',
										'desc' => 'items_count DESC' 
								),
								'*' 
						) 
				) 
		));
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CMyActiveRecord descendants!
	 * 
	 * @param string $className
	 *        	active record class name.
	 * @return UserProfile the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	public function getMonthTotalValue() {
		$db = Yii::app()->db;
		$sql = 'SELECT SUM(t.total_cost+t.delivery_cost) FROM `'.Orders::model()->tableName().'` `t`  '.'WHERE  create_date>\''.strtotime(date('1.m.Y', time()))."' AND t.user_id = '$this->uid'  ";
		
		return round($db->createCommand($sql)->queryScalar(), 2);
	}
	
	/**
	 */
	public function updateBalance() {
		// print_r($this);
		$db = Yii::app()->db;
		$sql = 'SELECT SUM(t.value) FROM `'.UserBalanceOperations::model()->tableName().'` `t`  '."WHERE t.user_id = '$this->uid' ";
		$total = $db->createCommand($sql)->queryScalar();
		// if(!is_numeric($total))
		// $total = 0;
		$this->balance = $total;
		if(Yii::app()->config->get('StopList.Active') == '1' && $this->legal_entity == 0 && $this->balance < 0) {
			$this->stop_list_state = 1;
		}
		if($this->legal_entity == 0 && $this->balance >= 0) {
			$this->stop_list_state = 0;
		}
		$this->save(false, array(
				'balance' 
		));
	}
	
	/**
	 *
	 * @param type $sum        	
	 * @param type $comment        	
	 * @param type $order_id        	
	 */
	public function addMoneyOperation($sum, $comment, $order_id = '0', $date = '') {
		$model = new UserBalanceOperations();
		$model->user_id = $this->uid;
		$model->value = $sum;
		$model->comment = $comment;
		if(! empty($date)) {
			$model->create_time = strtotime($date);
			$model->scenario = '1c';
		}
		
		$model->order_id = $order_id;
		// print_r($model);
		
		$model->save();
		// print_r($model->errors);
	}
	
	/**
	 *
	 * @return type
	 */
	public function getEmail() {
		return $this->email;
	}
	public function getPhone() {
		return $this->phone;
	}
	public function getOrdersCount() {
		if(empty($this->orders_count)) {
			$db = Yii::app()->db;
			$sql = 'SELECT count(*) FROM `'.Orders::model()->tableName().'` `t`  '."WHERE t.user_id = '$this->uid' ";
			$this->orders_count = $db->createCommand($sql)->queryScalar();
		}
		return $this->orders_count;
	}
	public function getOrdersCountWithStatus($status) {
		$db = Yii::app()->db;
		if($status < 0) {
			$sign_operation = '!=';
			$status = - $status;
		} else
			$sign_operation = '=';
		$sql = 'SELECT count(*) FROM `'.Orders::model()->tableName().'` `t`  '."WHERE t.user_id = '$this->uid' and status$sign_operation'$status' ";
		$temp = $db->createCommand($sql)->queryScalar();
		
		return $temp;
	}
	public function getDoneOrdersCount() {
		if(empty($this->orders_done_count)) {
			$db = Yii::app()->db;
			$sql = 'SELECT count(*) FROM `'.Orders::model()->tableName().'` `t`  '."WHERE t.user_id = '

        $this->uid' and status = '8' ";
			$this->orders_done_count = $db->createCommand($sql)->queryScalar();
		}
		return $this->orders_done_count;
	}
	public function deleteUserAccounts() {
		$db = Yii::app()->db;
		$sql = 'DELETE FROM `'.Orders::model()->tableName().'`   '."WHERE user_id = '$this->uid' ";
		$db->createCommand($sql)->query();
		$sql = 'DELETE FROM `'.Items::model()->tableName().'`  '."WHERE user_id = '$this->uid' ";
		$db->createCommand($sql)->query();
		$sql = 'DELETE FROM `lily_account`    '."WHERE uid = '$this->uid' ";
		$db->createCommand($sql)->query();
	}
	public function getSelectList() {
		$db = Yii::app()->db;
		$datas = $db->createCommand('SELECT id, first_name, second_name FROM '.$this->tableName())->queryAll();
		$array = array();
		
		foreach($datas as $data) {
			$array [$data ['id']] = $data ['second_name'].' '.$data ['first_name'];
		}
		return $array;
	}
	public function loginAsUser() {
		//Yii::app()->user->setState('UserOrderId', $this->uid);
		//Yii::app()->user->logout(); // разлогиниваем администратора
		Yii::app()->user->login(LUserIdentity::createAuthenticatedIdentity($this->uid), 0); // логинимся под пользователем $id
	}
	public function logoutAsUser() {
		Yii::app()->user->setState('UserOrderId', '');
	}
	public static function logoutAsUserStatic() {
		Yii::app()->user->setState('UserOrderId', '');
	}
	public static function getUserActiveId() {
		if(Yii::app()->user->checkAccess('UserNameOrder')) {
			$temp = Yii::app()->user->getState('UserOrderId');
			if(! empty($temp))
				return $temp;
		}
		return Yii::app()->user->id;
	}
	public function getUserApiModel() {
		$model = new UsersApiAccess();
		$model->user_id = $this->uid;
		// print_r($model);
		return $model;
	}
	public function getFormatUserMessages() {
		// Admin don't read
		$count1 = UserMessage::model()->countByAttributes(array(
				'user_id' => $this->uid,
				'readed_admin' => 0 
		));
		
		// User don't read
		$count2 = Yii::app()->db->createCommand()->select('count(*) as count')->from('user_message u1')->join('user_message_dialog u2', 'u1.user_dialog_id = u2.id')->where('u2.user_id = '.$this->uid.' AND u1.user_id != u2.user_id AND readed_user = 0')->queryScalar();
		
		// User read
		$count3 = Yii::app()->db->createCommand()->select('count(*) as count')->from('user_message u1')->join('user_message_dialog u2', 'u1.user_dialog_id = u2.id')->where('u2.user_id = '.$this->uid.' AND u1.user_id != u2.user_id AND readed_user = 1')->queryScalar();
		
		return '<div style="color: red;">'.$count1.'</div>'.'<div style="color: blue;">'.$count2.'</div>'.'<div style="color: black;">'.$count3.'</div>';
	}
	
	public static function getManagers() {
		$list = array();
		
		$users = Yii::app()->db->createCommand()
		->select('a.uid, a.first_name, a.second_name')
		->from('user_profile a')
		->join('authassignment b', 'a.uid = b.userid')
		->where('b.itemname IN("manager", "mainManager", "managerNotDiscount")')
		->queryAll();
		
		$count = count($users);
		for ($i = 0; $i < $count; $i ++) {
			$list[$users[$i]['uid']] = trim($users[$i]['first_name'].' '.$users[$i]['second_name']);
		}
			
		return $list;
	}
}