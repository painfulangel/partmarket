<?php
$this->breadcrumbs = array(
    Yii::t('config', 'system Reset'),
);


$this->pageTitle = Yii::t('config', 'system Reset');
?>

<h1><?= Yii::t('config', 'system Reset') ?></h1>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'cron-logs-form',
    'enableAjaxValidation' => false,
        ));
?>

<div class="control-group "><label class="control-label" for="Stores_delivery"><?= Yii::t('config', 'Password reset') ?></label>
    <div class="controls">
        <?php echo CHtml::textField('password', '', array('class' => 'span5',)); ?>
    </div></div>
<div class="control-group "><label class="control-label" for="Stores_delivery"><?= Yii::t('config', 'List user ID') ?> </label>
    <div class="controls">
        <?php echo CHtml::textArea('users_ids', '', array('class' => 'span5',)); ?>
    </div></div>
<p>Id указывать через ","</p>


<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('config', 'Clear'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
