<?php
	$title = Yii::t('universal', 'Universal catalog products').'"'.$razdel->name.'"';
	
	$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('universal', 'Universal catalog') => array('/universal/admin/admin'), 
													 $title => array('admin', 'id' => $razdel->primaryKey), 
													 Yii::t('universal', 'Copy characteristics from other section')));

	$this->pageTitle = Yii::t('universal', 'Copy characteristics from other section');
?>

<h1><?php echo Yii::t('universal', 'Copy characteristics from other section'); ?></h1>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'chars-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
));
?>
<?php echo $form->errorSummary($model) ?>
<?php echo $form->dropDownListRow($model, 'razdelParentId', $sections); ?>
<?php echo $form->hiddenField($model, 'razdelId'); ?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('universal', 'Copy'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>