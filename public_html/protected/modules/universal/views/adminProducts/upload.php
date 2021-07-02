<?php
	$title = Yii::t('universal', 'Universal catalog products').'"'.$razdel->name.'"';
	
	$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('universal', 'Universal catalog') => array('/universal/admin/admin'), 
													 $title => array('admin', 'id' => $razdel->primaryKey), 
													 Yii::t('universal', 'Upload products csv file')));

	$this->pageTitle = Yii::t('universal', 'Upload products csv file');
?>

<h1><?php echo Yii::t('universal', 'Upload products csv file'); ?></h1>
<div class="btn-toolbar" style="margin-bottom: 30px;">
    <?php echo CHtml::link(Yii::t('universal', 'Download example file'), array('download', 'id' => $razdel->primaryKey), array('class' => 'btn')); ?>
</div>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'products-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
));
?>
<?php echo $form->errorSummary($model) ?>
<?php echo $form->fileFieldRow($model, 'fileImport', array()); ?>
<?php echo $form->hiddenField($model, 'razdelId'); ?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('universal', 'Import'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>