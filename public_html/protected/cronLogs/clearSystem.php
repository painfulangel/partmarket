<?php
$this->breadcrumbs = array(
    Yii::t('config', 'Сброс системы'),
);


$this->pageTitle = Yii::t('config', 'Сброс системы');
?>

<h1><?= Yii::t('config', 'Сброс системы') ?></h1>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'cron-logs-form',
    'enableAjaxValidation' => false,
        ));
?>

<div class="control-group "><label class="control-label" for="Stores_delivery"><?= Yii::t('config', 'Пароль сброса') ?></label>
    <div class="controls">
        <?php echo CHtml::textField('password', '', array('class' => 'span5',)); ?>
    </div></div>
<div class="control-group "><label class="control-label" for="Stores_delivery"><?= Yii::t('config', 'Список ИД пользователей') ?> </label>
    <div class="controls">
        <?php echo CHtml::textArea('users_ids', '', array('class' => 'span5',)); ?>
    </div></div>
<p>Id указывать через ","</p>


<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('config', 'Очистить'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>
