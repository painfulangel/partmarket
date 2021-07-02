<?php
class UniversalCharsCopy extends CFormModel {
	public $razdelParentId;
	public $razdelId;
	
    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('razdelParentId', 'numerical', 'integerOnly' => true),
        );
    }
    
    public function import() {
    	$razdelParentId = intval($this->razdelParentId);
    	$razdelId = intval($this->razdelId);
    	
    	$razdel = UniversalRazdel::model()->findByPk($razdelId);
    	if (is_object($razdel)) {
    		//Old
    		$chars = UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$razdelId));
    		
    		foreach ($chars as $char) {
    			$char->delete();
    		}
    		
    		//New
    		$chars = UniversalChars::model()->findAll(array('condition' => 'id_razdel = '.$razdelParentId));
    		foreach ($chars as $char) {
    			$c = new UniversalChars();
    			$c->id_razdel = $razdelId;
    			$c->name = $char->name;
    			$c->type = $char->type;
    			$c->min = $char->min;
    			$c->max = $char->max;
    			$c->filter = $char->filter;
    			$c->filter_main = $char->filter_main;
    			$c->filter_view = $char->filter_view;
    			$c->order = $char->order;
    			
    			if ($c->save()) {
    				if (in_array($char->type, array(2, 4))) {
    					$values = $char->getValues();
    					
    					foreach ($values as $value) {
    						$v = new UniversalCharsListValues();
    						$v->id_chars = $c->primaryKey;
    						
    						if ($char->type == 2)
    							$v->value_string = $value;
    						else
    							$v->value_number = $value;
    						
    						$v->save();
    					}
    				}
    			}
    		}
    		
    		return true;
    	}
    	
    	return false;
    }
    
    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
	public function attributeLabels() {
		return array(
			'razdelParentId' => Yii::t('universal', 'Section ID'),
			'razdelId' => Yii::t('universal', 'Section ID'),
		);
	}
}