<?php
	if (!$model->isNewRecord && ($image = $model->getThumb())) {
?>
		<div class="control-group">
			<div class="controls">
				<img src="<?php echo $image; ?>" style="max-height: 100px;">
			</div>
			<div class="controls delete_image" style="margin-top: 10px;">
				<input type="checkbox" name="delete_image" id="delete_image" value="1" style="float: left; margin: 0px 5px 0px 0px;"><label for="delete_image" style="float: left;"><?php echo Yii::t('universal', 'Delete'); ?></label>
			</div>
		</div>
<?php
	}
	
	echo $form->fileFieldRow($model, '_image');
	
	echo $form->hiddenField($model, 'id_razdel');
	echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textFieldRow($model, 'article', array('class' => 'span5', 'maxlength' => 255));
	
	//Свойства, созданные для текущего раздела
	$count = count($chars);
	for ($i = 0; $i < $count; $i ++) {
		$name = 'char'.$chars[$i]->primaryKey;
		
		switch ($chars[$i]->type) {
			case 1:
			case 3:
			case 5:
				$htmlOptions = array('class' => 'span5', 'maxlength' => 255);
				
				if ($chars[$i]->type == 5) {
					$htmlOptions['placeholder'] = Yii::t('universal', 'From').' '.$chars[$i]->min.' '.Yii::t('universal', 'To').' '.$chars[$i]->max;
				}
				
				echo $form->textFieldRow($model, $name, $htmlOptions);
			break;
			case 2:
			case 4:
				echo $form->dropDownListRow($model, $name, $chars[$i]->getValues(), array('class' => 'span5', 'empty' => ''));
			break;
			case 6:
				echo $form->checkBoxRow($model, $name);
			break;
		}
		//new TbActiveForm();
	}
	
	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'anons'));
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'content'));
	//!!! Виджет, выводящий редактор нужного типа
	
	echo $form->textAreaRow($model, 'analogs', array('style' => 'width: 350px;'));
	echo $form->checkBoxRow($model, 'active_state', array('class' => 'span1', 'maxlength' => 255));
?>