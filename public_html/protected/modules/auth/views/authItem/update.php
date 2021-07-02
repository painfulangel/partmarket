<?php
/* @var $this OperationController|TaskController|RoleController */
/* @var $model AuthItemForm */
/* @var $item CAuthItem */
/* @var $form TbActiveForm */

$this->breadcrumbs = array(
    $this->capitalize($this->getTypeText(true)) => array('index'),
    $item->description => array('view', 'name' => $item->name),
    Yii::t('auth_main', 'Edit'),
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

<h1>
    <?php echo CHtml::encode($item->description); ?>
    <small><?php echo $this->getTypeText(); ?></small>
</h1>

<?php
$form = $this->beginWidget(
        'bootstrap.widgets.TbActiveForm', array(
    'type' => TbHtml::FORM_LAYOUT_HORIZONTAL,
        )
);
?>

<?php echo $form->hiddenField($model, 'type'); ?>
<?php
echo $form->textFieldRow(
        $model, 'name', array(
    'disabled' => true,
    'title' => Yii::t('auth_main', 'System name cannot be changed after creation.'),
        )
);
?>
<?php echo $form->textFieldRow($model, 'description'); ?>

<div class="form-actions">
    <?php
    echo TbHtml::submitButton(
            Yii::t('auth_main', 'Save'), array(
        'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            )
    );
    ?>
    <?php
    echo TbHtml::linkButton(
            Yii::t('auth_main', 'Cancel'), array(
        'color' => TbHtml::BUTTON_COLOR_LINK,
        'url' => array('view', 'name' => $item->name),
            )
    );
    ?>
</div>

<?php $this->endWidget(); ?>