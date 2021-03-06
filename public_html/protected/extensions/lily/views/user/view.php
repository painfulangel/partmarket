<?php
/* @var $this Controller*/
$this->pageTitle = Yii::t('lily', '{appName} - User', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
$this->breadcrumbs = array(
    Yii::t('lily', 'User {userName}', array('{userName}'=>$user->name))
);
?><h1><?php echo Yii::t('lily', 'User {userName}', array('{userName}'=>$user->name));?></h1>

<?php

/* @var $user LUser */

$attrs = array('uid', 'name');

$attrs[] = array(
    'name' => 'state',
    'type' => 'raw',
    'value' => LUser::getStateLabel($user),
);

$attrs[] = array(
    'name' => 'inited',
    'type' => 'raw',
    'value' => LUser::getInitedLabel($user),
);
if (Yii::app()->user->checkAccess('listAccounts', array('uid' => $user->uid))) {
    $attrs[] = array(
        'label' => Yii::t('lily', 'Accounts'),
        'type' => 'raw',
        'value' => CHtml::link(Yii::t('lily', "Go to account list"), array('/'.LilyModule::route("account/list"), 'uid' => $user->uid)),
    );
    $attrs[] = array(
        'type' => 'raw',
        'value' => $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => new CActiveDataProvider('LAccount', array(
                'criteria' => array(
                    'condition' => 'uid=:uid AND hidden=0',
                    'params' => array(':uid' => $user->uid),
                    'order' => 'created ASC',
                ),
            )),
            'enablePagination' => false,
            'summaryText' => '',
            'columns' => array(
                array(
                    'name' => 'service',
                    'value' => '$data->serviceName',
                ),
                array(
                    'name' => 'id',
                    'value' => '$data->displayId',
                ),
                array(
                    'name' => 'created',
                    'value' => 'Yii::app()->dateFormatter->formatDateTime($data->created)',
                ),
            ),
                ), true)
            ,
    );
}
if ($user->state <= LUser::ACTIVE_STATE) {
    $value = '';
    if ($user->state != LUser::ACTIVE_STATE && (
            ($user->state == LUser::DELETED_STATE && Yii::app()->user->checkAccess('restoreUser', array('uid' => $user->uid)))
            || ($user->state == LUser::BANNED_STATE && Yii::app()->user->checkAccess('unbanUser', array('uid' => $user->uid)))
            ))
        $value.= '<li>' . CHtml::link(Yii::t('lily', "Activate user"), $this->createUrl("user/switch_state", array('uid' => $user->uid, 'mode' => LUser::ACTIVE_STATE))) . '</li>';
    if ($user->state != LUser::DELETED_STATE && (
            ($user->state == LUser::ACTIVE_STATE && Yii::app()->user->checkAccess('deleteUser', array('uid' => $user->uid)))
            || ($user->state == LUser::BANNED_STATE && Yii::app()->user->checkAccess('unbanUser', array('uid' => $user->uid)) && Yii::app()->user->checkAccess('deleteUser', array('uid' => $user->uid)))
            ))
        $value .= '<li>' . CHtml::link(Yii::t('lily', "Delete user"), $this->createUrl("user/switch_state", array('uid' => $user->uid, 'mode' => LUser::DELETED_STATE))) . '</li>';
    if ($user->state != LUser::BANNED_STATE && !Yii::app()->authManager->checkAccess('unbanUser', $user->uid, array('uid' => $user->uid)) && (
            ($user->state == LUser::ACTIVE_STATE && Yii::app()->user->checkAccess('banUser', array('uid' => $user->uid)))
            || ($user->state == LUser::DELETED_STATE && Yii::app()->user->checkAccess('restoreUser', array('uid' => $user->uid)) && Yii::app()->user->checkAccess('banUser', array('uid' => $user->uid)))
            ))
        $value.= '<li>' . CHtml::link(Yii::t('lily', "Ban user"), $this->createUrl("user/switch_state", array('uid' => $user->uid, 'mode' => LUser::BANNED_STATE))) . '</li>';
    if(!empty($value))
    $attrs[] = array(
        'type' => 'raw',
        'label' => Yii::t('lily', 'Actions'),
        'value' => '<ul>' . $value . '</ul>'
    );
}
$this->widget('zii.widgets.CDetailView', array(
    'data' => $user,
    'itemCssClass' => array(),
    'attributes' => $attrs,
));