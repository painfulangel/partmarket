<?php
	Yii::app()->clientScript->registerScriptFile('/libs/redactorjs/ru.js');
	
	echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textFieldRow($model, 'article', array('class' => 'span5', 'maxlength' => 255));

	/*if (!$model->isNewRecord && ($image = $model->getThumb())) {
?>
	<div class="control-group">
		<div class="controls">
			<img src="<?php echo $image; ?>" style="max-height: 100px;">
		</div>
		<div class="controls delete_image">
			<input type="checkbox" name="delete_image" id="delete_image" value="1"><label for="delete_image"><?php echo Yii::t('tires', 'Delete'); ?></label>
		</div>
	</div>
<?php
	}
	
	echo $form->fileFieldRow($model, '_image');*/
	
	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'description'));
	//!!! Виджет, выводящий редактор нужного типа

	echo $form->dropDownListRow($model, 'country', MaslaPropertyValues::selectList(1), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'producer', MaslaPropertyValues::selectList(2), array('class' => 'span5', 'empty' => ''));
	echo $form->textAreaRow($model, 'specif', array('rows' => 5, 'class' => 'span5', 'maxlength' => 255));
	echo $form->textAreaRow($model, 'producer_text', array('rows' => 5, 'class' => 'span5', 'maxlength' => 255));
	echo $form->dropDownListRow($model, 'scope', MaslaPropertyValues::selectList(5), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'sae', MaslaPropertyValues::selectList(6), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'engine_type', MaslaPropertyValues::selectList(7), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'fuel_type', MaslaPropertyValues::selectList(8), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'oil_type', MaslaPropertyValues::selectList(9), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'api', MaslaPropertyValues::selectList(17), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'ilsac', MaslaPropertyValues::selectList(18), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'iso', MaslaPropertyValues::selectList(20), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'acea', MaslaPropertyValues::selectList(22), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'jaso', MaslaPropertyValues::selectList(23), array('class' => 'span5', 'empty' => ''));
	
	echo $form->dropDownListRow($model, 'density', MaslaPropertyValues::selectList(10), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'temp_harden', MaslaPropertyValues::selectList(11), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'color', MaslaPropertyValues::selectList(12), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'index_viscosity', MaslaPropertyValues::selectList(13), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'viscosity_forty', MaslaPropertyValues::selectList(14), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'viscosity_hundred', MaslaPropertyValues::selectList(15), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'temp_flash', MaslaPropertyValues::selectList(16), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'alkali_number', MaslaPropertyValues::selectList(19), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'temp_loss_fluidity', MaslaPropertyValues::selectList(21), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'temp_boiling', MaslaPropertyValues::selectList(24), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'sulphate_ash', MaslaPropertyValues::selectList(25), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'total_acid_number', MaslaPropertyValues::selectList(26), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'viscosity_seeming', MaslaPropertyValues::selectList(27), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'evaporability', MaslaPropertyValues::selectList(28), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'sulfur', MaslaPropertyValues::selectList(29), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'zinc', MaslaPropertyValues::selectList(30), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'phosphorus', MaslaPropertyValues::selectList(31), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'molybdenum', MaslaPropertyValues::selectList(32), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'boron', MaslaPropertyValues::selectList(33), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'magnesium', MaslaPropertyValues::selectList(34), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'calcium', MaslaPropertyValues::selectList(35), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'silicon', MaslaPropertyValues::selectList(36), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'sodium', MaslaPropertyValues::selectList(37), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'viscosity_seeming_35', MaslaPropertyValues::selectList(38), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'ph', MaslaPropertyValues::selectList(39), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'barium', MaslaPropertyValues::selectList(40), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'aluminum', MaslaPropertyValues::selectList(41), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'iron', MaslaPropertyValues::selectList(42), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'potassium', MaslaPropertyValues::selectList(43), array('class' => 'span5', 'empty' => ''));
	
	echo $form->checkBoxRow($model, 'active_state', array('class' => 'span1', 'maxlength' => 255));
?>