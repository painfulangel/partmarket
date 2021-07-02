<?php
/* @var $this Controller */
$this->pageTitle = Yii::t('lily', '{appName} - User initialization', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
$this->breadcrumbs = array(
    Yii::t('lily', 'User initialization')
);
?><h1><?php echo Yii::t('lily', 'Account initialization'); ?></h1>
<?php
switch ($action) {
    case 'start':
        ?>
        <div>
            <?php echo Yii::t('lily', 'Please fill in next few forms in order to initialize your account.'); ?>
            <br/> 
            <?php // echo CHtml::link(Yii::t('lily', 'Start'), $this->createUrl('', array('action' => 'next'))); ?>
            <div class="form-actions">
                <?php echo CHtml::link(Yii::t('lily', 'Start'), $this->createUrl('', array('action' => 'next')), array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
        <?php
        break;
    case 'finish':
        ?>
        <div>
            <?php echo Yii::t('lily', 'Account was successfully initialized.'); ?>
            <br/> 
            <?php // echo CHtml::link(Yii::t('lily', 'Finish'), $this->createUrl('', array('action' => 'next'))); ?>

            <div class="form-actions">
                <?php echo CHtml::link(Yii::t('lily', 'Finish'), $this->createUrl('', array('action' => 'next')), array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
        <?php
        break;
}