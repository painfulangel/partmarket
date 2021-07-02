<h1><?= Yii::t('userControl', 'Administrative users access to Api') ?></h1>
<div class="btn-toolbar">
    <?= CHtml::link(Yii::t('userControl', 'Create a new access'), array('adminApiAccess/create', 'id' => $model->user_id), array('class' => 'btn')) ?>
</div>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'users-api-access-grid',
    'dataProvider' => $model->search(),
//    'filter' => $model,
    'columns' => array(
//        'id',
//        array(
//            'class' => 'bootstrap.widgets.TbDataColumn',
//            'name' => 'user_id',
//            'value' => '$data->user->fullNameOrg',
//            'htmlOptions' => array('style' => 'text-align: center;'),
//            'headerHtmlOptions' => array('style' => 'text-align: center;'),
//        ),
//        'user_id',
        'access_token',
        array(
            'class' => 'ext.jtogglecolumn.JToggleColumn',
            'name' => 'active_state',
            'filter' => array('0' => Yii::t('userControl', 'No'), '1' => Yii::t('userControl', 'Yes')),
            'checkedButtonLabel' => Yii::t('userControl', 'Deactivate'),
            'uncheckedButtonLabel' => Yii::t('userControl', 'Activate'),
            'headerHtmlOptions' => array('style' => 'text-align: center;'),
            'htmlOptions' => array('style' => 'text-align: center;'),
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update} {delete}',
            'buttons' => array(
                'delete' => array(
                    'url' => 'array(\'adminApiAccess/delete\',\'id\'=>$data->id)',
                ),
                'update' => array(
                    'url' => 'array(\'adminApiAccess/update\',\'id\'=>$data->id)',
                ),
            )
        ),
    ),
));
?>

