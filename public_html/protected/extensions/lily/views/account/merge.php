<?php
/* @var $this Controller*/
$this->pageTitle = Yii::t('lily', '{appName} - Merge users', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
$this->breadcrumbs = array(
    Yii::t('lily', 'User')=>$this->createUrl('user/view'),
    Yii::t('lily', 'Merge')
);
?><h1><?php echo Yii::t('lily', 'Merge users');?></h1>

<div>
    <form action="" method='post'  >
        <p class="note"><?php echo Yii::t('lily', 'Do you really want to merge your user account with {userLink}?',
                array('{userLink}'=>CHtml::link($user->nameId, $this->createUrl('user/view', array('uid' => $user->uid)))));?></p>
        <?php if ($banWarning) { ?>
            <p class="warning"><?php echo Yii::t('lily', "Warning! User, you're merging with is banned. If you'll submit this form, your account would be banned also."); ?></p>
        <?php } ?>
        <?php if ($deleteWarning) { ?>
            <p class="warning"><?php echo Yii::t('lily', "Warning! User, you're merging with is deleted. If you'll submit this form, your account would be deleted also."); ?></p>
        <?php } ?>
        <div class="row buttons">
            <?php echo CHtml::submitButton(Yii::t('lily', 'Yes'), array('name' => 'accept')); ?>
            <?php echo CHtml::link(Yii::t('lily', 'Cancel'), $this->createUrl('account/list')); ?>
        </div>
    </form>
</div>