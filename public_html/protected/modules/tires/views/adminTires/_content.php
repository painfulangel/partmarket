<?php
	Yii::app()->clientScript->registerScriptFile('/libs/redactorjs/ru.js');
	
	echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textFieldRow($model, 'article', array('class' => 'span5', 'maxlength' => 255));

	if (!$model->isNewRecord && ($image = $model->getThumb())) {
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
	
	echo $form->fileFieldRow($model, '_image');
	
	//!!! Виджет, выводящий редактор нужного типа
	$this->widget('application.extensions.redactor.widgets.Redactor', array('form' => $form, 'model' => $model, 'attribute' => 'description'));
	//!!! Виджет, выводящий редактор нужного типа

	echo $form->dropDownListRow($model, 'type', TiresPropertyValues::selectList(1), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'producer', TiresPropertyValues::selectList(2), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'width', TiresPropertyValues::selectList(3), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'height', TiresPropertyValues::selectList(4), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'diameter', TiresPropertyValues::selectList(5), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'seasonality', TiresPropertyValues::selectList(6), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'speed_index', TiresPropertyValues::selectList(7), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'shipp', TiresPropertyValues::selectList(8), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'load_index', TiresPropertyValues::selectList(9), array('class' => 'span5', 'empty' => ''));
	echo $form->dropDownListRow($model, 'axis', TiresPropertyValues::selectList(10), array('class' => 'span5', 'empty' => ''));
	
	echo $form->checkBoxRow($model, 'active_state', array('class' => 'span1', 'maxlength' => 255))
?>