<?php
/* @var $this OperationController|TaskController|RoleController */
/* @var $model AuthItemForm */
/* @var $form TbActiveForm */

$this->breadcrumbs = array(
    $this->capitalize($this->getTypeText(true)) => array('index'),
    Yii::t('auth_main', 'New {type}', array('{type}' => $this->getTypeText())),
);
$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Clients'),
        'url' => array('/userControl/adminUserProfile/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Create Client'),
        'url' => array('/userControl/adminUserProfile/createNewUser'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Rights to users'),
        'url' => array('/auth/assignment/index'),
        'active' => true,
    ),
);
?>

<h1><?php echo Yii::t('auth_main', 'New {type}', array('{type}' => $this->getTypeText())); ?></h1>

<?php
$form = $this->beginWidget(
        'bootstrap.widgets.TbActiveForm', array(
    'type' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        )
);
?>

<?php echo $form->hiddenField($model, 'type'); ?>
<?php echo $form->textFieldRow($model, 'name'); ?>
<?php echo $form->textFieldRow($model, 'description'); ?>

<div class="form-actions">
    <?php
    echo TbHtml::submitButton(
            Yii::t('auth_main', 'Create'), array(
        'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            )
    );
    ?>
    <?php
    echo TbHtml::linkButton(
            Yii::t('auth_main', 'Cancel'), array(
        'color' => TbHtml::BUTTON_COLOR_LINK,
        'url' => array('index'),
            )
    );
    ?>
</div>

<?php $this->endWidget(); ?>