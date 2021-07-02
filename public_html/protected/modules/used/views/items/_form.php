<?php
/* @var $this ItemsController */
/* @var $model UsedItems */
/* @var $form CActiveForm */

?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'used-items-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'clientOptions' =>array('validateOnSubmit' => true),
	'type' => 'horizontal',
	'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
    )
));

$tabs = array(
    array(
        'label' => Yii::t(UsedModule::TRANSLATE_PATH, 'Main'),
        'content' => $this->renderPartial('_main', array('form' => $form, 'model' => $model), true),
        'active' => true
    ),
    array(
        'label' => Yii::t(UsedModule::TRANSLATE_PATH, 'SEO'),
        'content' => $this->renderPartial('_seo', array('form' => $form, 'model' => $model), true),
    ),
	array(
        'label' => Yii::t(UsedModule::TRANSLATE_PATH, 'Sets'),
        'content' => $this->renderPartial('_sets', array('form' => $form, 'model' => $model), true),
    ),
    array(
        'label' => Yii::t(UsedModule::TRANSLATE_PATH, 'Pictures'),
        'content' => $this->renderPartial('_pictures', array('form' => $form, 'model' => $model), true),
    ),
	array(
		'label' => Yii::t(UsedModule::TRANSLATE_PATH, 'Applicability'),
		'content' => $this->renderPartial('_applicat', array('form' => $form, 'model' => $model), true),
	),
);
/*foreach ($model->langsList() as $row) {
    $tabs[] = array(
        'label' => $row['name'],
        'content' => $this->renderPartial('application.views.adminLanguages._form_edit_languange', array('form' => $form, 'model' => $model->getTranslatedModel($row['link_name'], true), 'lang' => $row), true),
    );
}*/
?>

	<?php echo $form->errorSummary($model); ?>
	
	<?php $this->widget('bootstrap.widgets.TbTabs', array(
		'type' => 'tabs',
		'tabs' => $tabs,
	));?>


	<div class="form-actions">
		<?php
		$this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type' => 'primary',
			'label' => $model->isNewRecord ? Yii::t(UsedModule::TRANSLATE_PATH, 'Add') : Yii::t(UsedModule::TRANSLATE_PATH, 'Save'),
		));
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
	$(document).ready(function(){
		$('#UsedItems_set').change(function(){
			if($('#UsedItems_set').prop("checked")){
				console.log('check');
				$.get('/used/itemSets/create?index=0', function(data){
					$('#forms-sets').append(data);
				});
			}
			else{
				console.log('not');
			}
		});
	});
</script>