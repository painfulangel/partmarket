<?php
/*$assetsDir = dirname(dirname(dirname(__FILE__))).'/assets';
$folder = Yii::app()->assetManager->publish($assetsDir);
$css = $folder.'/css';
Yii::app()->clientScript->registerCssFile($css.'/admin.css');*/

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'                   => 'prop-form',
    'enableAjaxValidation' => false,
    'type'                 => 'horizontal',
	//'htmlOptions'		   => array('enctype' => 'multipart/form-data'),
));
?>
<?php echo $form->errorSummary($model); ?>
<div class="note"></div>
<?php
	echo $form->hiddenField($model, 'id_razdel');
	echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 255));
	
	echo $form->dropDownListRow($model, 'type', UniversalChars::getTypes(), array('class' => 'span5', 'empty' => ''));
?>
<div class="additional-info additional-info-2">
	<div class="rows"></div>
	<div class="control-group">
		<label class="control-label">&nbsp;</label>
		<div class="controls">
<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'button',
        'label' => Yii::t('universal', 'Add'),
    	'htmlOptions' => array('class' => 'add_row'),
    ));
?>
		</div>
	</div>
</div>

<div class="additional-info additional-info-5">
<?php
	echo $form->textFieldRow($model, 'min', array('class' => 'span5', 'maxlength' => 255));
	echo $form->textFieldRow($model, 'max', array('class' => 'span5', 'maxlength' => 255));
?>
</div>
<?php
	echo $form->checkBoxRow($model, 'filter', array('class' => 'span1', 'maxlength' => 255));
	echo $form->checkBoxRow($model, 'filter_main', array('class' => 'span1', 'maxlength' => 255));
?>
<div class="control-group additional-info additional-info-2">
	<?php echo $form->radioButtonListRow($model, 'filter_view', array(1 => Yii::t('universal', 'As list'), 2 => Yii::t('universal', 'As checkboxes'))); ?>
</div>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('universal', 'Add') : Yii::t('universal', 'Save'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    var list_id = '';
    var list_value = '';
    
	$(function() {
		$('#UniversalChars_type').change(function() {
			var id = $(this).val();
			var note = '';
			
			switch (parseInt($(this).val())) {
				case 1:
					note = "<?php echo Yii::t('universal', 'String value, for example name.'); ?>";
				break;
				case 2:
					id = 2;
					note = "<?php echo Yii::t('universal', 'Enter the name of a field. This field is intended for exact values.'); ?>";
				break;
				case 4:
					id = 2;
					note = "<?php echo Yii::t('universal', 'Enter the name of a field. This field is intended for exact digital values.'); ?>";
				break;
				case 6:
					note = "<?php echo Yii::t('universal', 'Enter the name of a field. This field is intended for values \'Yes\' or \'No\'.'); ?>";
				break;
			}
			
			$('div.additional-info:not(.additional-info-' + id + ')').hide();
			$('div.additional-info-' + id).show();

			$('div.note').html(note);
		})
		.change();
		
		$('button.add_row').click(function() {
			var div = $('div.additional-info-2 div.rows');
			var number = div.find('.control-group').length + 1;
			
			var html = '<div class="control-group">' + 
					   '<label class="control-label" for="list_' + number + '">&nbsp;</label>' + 
					   '<div class="controls"><input class="span5" maxlength="255" name="list[]" id="list_' + number + '" type="text" placeholder="<?php echo Yii::t('universal', 'Value'); ?> ' + number + '" value="' + list_value + '"></div>' + 
					   '<input type="hidden" name="list_id[]" value="' + list_id + '">' + 
					   '</div>';
		    
			div.append(html);
		});

<?php
		if ($model->primaryKey && count($values = $model->getValues())) {
			foreach ($values as $key => $value) {
?>
		list_id = <?php echo $key; ?>;
		list_value = '<?php echo htmlspecialchars($value); ?>';
		
		$('button.add_row').click();
<?php
			}
?>
		list_id = '';
		list_value = '';
<?php
		} else {
?>
		$('button.add_row').click();
<?php
		}
?>
	});
</script>
<style type="text/css">
    .form-horizontal .control-label {
    	margin-right: 10px;
    	width: 180px;
    }
    
    label.radio {
    	margin-left: 10px;
    }
    
    div.additional-info {
    	display: none;
    }
    
    div.note {
    	color: #337ab7;
    	height: 20px;
    	margin: 10px 0 20px 10px;
    }
</style>