<?php
/* @var $this Controller */
$this->pageTitle = Yii::t('lily', '{appName} - E-mail registration', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
$this->breadcrumbs = array(
    Yii::t('lily', 'E-mail registration')
);
?><h1><?php echo Yii::t('lily', 'E-mail registration'); ?></h1>
<?php
foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="panel panel-default flash-' . $key . '">' . $message . "</div>\n";
}
?>
<p><?php echo Yii::t('lily', 'Please fill out the following form:'); ?></p>
<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => "LRegisterForm-form",
        'htmlOptions' => array('class' => 'regForm'),
        'action' => '',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),)
    );
    ?>
    <?php echo $form->errorSummary($model); ?>
    <div class="formDiv">
        <div class="emailFieldsDiv">              
            <p class="note"><?php echo Yii::t('lily', 'If you forgot your password, you can restore it using {restorePageLink}', array('{restorePageLink}' => CHtml::link(Yii::t('lily', 'restore password.'), Yii::app()->createUrl('/' . LilyModule::route('account/restore'))))); ?>.</p>
            <?php echo $form->textFieldRow($model, 'email', array('class' => 'span3')); ?>
        </div>

        <?php
//        foreach (Yii::app()->user->getFlashes() as $key => $message) {
//            echo '<div class="panel panel-default flash-' . $key . '">' . $message . "</div>\n";
//        }
        ?>


        <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span3')); ?>
        <?php echo $form->passwordFieldRow($model, 'passwordRepeat', array('class' => 'span3')); ?>


        <?php if (LilyModule::instance()->accountManager->loginAfterRegistration) { ?>
            <?php echo $form->checkBoxRow($model, 'rememberMe', array('class' => '')); ?>
        <?php } ?>
        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => Yii::t('lily', "Register me"),
            ));
            ?>
        </div>

    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->


