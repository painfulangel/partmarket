<?php
/* @var $this OperationController|TaskController|RoleController */
/* @var $dataProvider AuthItemDataProvider */

$this->breadcrumbs = array(
    $this->capitalize($this->getTypeText(true)),
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

<h1><?php echo $this->capitalize($this->getTypeText(true)); ?></h1>

<?php echo TbHtml::linkButton(
    Yii::t('auth_main', 'Add {type}', array('{type}' => $this->getTypeText())),
    array(
        'color' => TbHtml::BUTTON_COLOR_PRIMARY,
        'url' => array('create'),
    )
); ?>

<?php $this->widget(
    'bootstrap.widgets.TbGridView',
    array(
        'type' => 'striped hover',
        'dataProvider' => $dataProvider,
        'emptyText' => Yii::t('auth_main', 'No {type} found.', array('{type}' => $this->getTypeText(true))),
        'template' => "{items}\n{pager}",
        'columns' => array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'header' => Yii::t('auth_main', 'System name'),
                'htmlOptions' => array('class' => 'item-name-column'),
                'value' => "CHtml::link(\$data->name, array('view', 'name'=>\$data->name))",
            ),
            array(
                'name' => 'description',
                'header' => Yii::t('auth_main', 'Description'),
                'htmlOptions' => array('class' => 'item-description-column'),
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'viewButtonLabel' => Yii::t('auth_main', 'View'),
                'viewButtonUrl' => "Yii::app()->controller->createUrl('view', array('name'=>\$data->name))",
                'updateButtonLabel' => Yii::t('auth_main', 'Edit'),
                'updateButtonUrl' => "Yii::app()->controller->createUrl('update', array('name'=>\$data->name))",
                'deleteButtonLabel' => Yii::t('auth_main', 'Delete'),
                'deleteButtonUrl' => "Yii::app()->controller->createUrl('delete', array('name'=>\$data->name))",
                'deleteConfirmation' => Yii::t('auth_main', 'Are you sure you want to delete this item?'),
            ),
        ),
    )
); ?>
