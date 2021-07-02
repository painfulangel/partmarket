<?php
/* @var $this AssignmentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    Yii::t('auth_main', 'Assignments'),
);
?>

<h1><?php echo Yii::t('auth_main', 'Assignments'); ?></h1>

<?php
$this->widget(
        'bootstrap.widgets.TbGridView', array(
    'type' => 'striped hover',
    'dataProvider' => $dataProvider,
    'emptyText' => Yii::t('auth_main', 'No assignments found.'),
    'template' => "{items}\n{pager}",
    'filter' => $model,
    'columns' => array(
        array(
            'header' => Yii::t('auth_main', 'User'),
            'class' => 'AuthAssignmentNameColumn',
        ),
        array(
            'name' => 'uid',
            'class' => 'bootstrap.widgets.TbDataColumn',
        ),
        array(
            'name' => 'email',
            'class' => 'bootstrap.widgets.TbDataColumn',
        ),
        array(
            'header' => Yii::t('auth_main', 'Assigned items'),
            'class' => 'AuthAssignmentItemsColumn',
        ),
        array(
            'class' => 'AuthAssignmentViewColumn',
        ),
    ),
        )
);
?>
