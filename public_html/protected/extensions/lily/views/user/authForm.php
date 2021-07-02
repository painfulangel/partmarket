<div class="form">
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => $id,
        'htmlOptions' => array('class' => 'authForm'),
        'action' => $action,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),)
    );

    Yii::app()->clientScript->registerScript('lily-init' . $id, '$("#' . $id . '").lily();');
    ?>
	<?php /*  <h2><?= Yii::t('lily', 'Choose method') ?>:</h2>

    <div class="authMethodSwitcherDiv">
        <ul class="authMethodSwitcher">
    <?php foreach ($services as $service => $options) { ?>
                    <li class="authMethod <?php echo $options->id; ?>" service="<?php echo $options->id; ?>">
                        <a class="authMethodLink" href="#">
                            <div class="authMethodIcon"><i></i></div>
                            <div class="authMethodTitle"><?php echo $options->title; ?></div>
                        </a>
                    </li>
    <?php } ?>
        </ul>
    </div> */ ?>
    <div class="authMethodSelectDiv row">
        <?php
        $data = $options = array();
        foreach ($services as $service => $opts) {
            $data[$service] = $opts->title;
            $options[$service] = array('class' => 'option_' . $service);
        }
        echo $form->dropDownList($model, 'service', $data, array('class' => 'authMethodSelect', 'options' => $options));
        ?>
    </div>
    <div class="eauthHandlers">
        <?php
        foreach ($services as $name => $service) {
            if ($name == 'email')
                continue;
            echo '<div class="auth-service ' . $service->id . '">'
            . CHtml::link('-', array($action, 'service' => $name), array('class' => 'auth-link ' . $service->id,))
            . '</div>';
        }
        ?>
    </div>
    <div class="formDiv">
        <?php if (isset($services['email'])) { ?>
            <div class="emailFieldsDiv">
                <p class="note emailFieldHint"><?php echo Yii::t('lily', 'Use fields Email, Password when method "E-mail" was selected'); ?></p>                
                <?php if (LilyModule::instance()->accountManager->registerEmail) { ?>
                    <p class="note" id="LilyRegId"><?= CHtml::link(Yii::t('lily', 'Registration'), array('/userControl/userProfile/registration')) ?></p>

                    <!--
                    <p class="note"><?php // echo Yii::t('lily', 'If you\'re not yet registered, just fill in E-mail and password fields with your e-mail address and a password you want to use. You\'ll be automaticaly registrated.');        ?></p>
                    -->
                <?php } else { ?>
                    <p class="note"><?php echo Yii::t('lily', 'If you\'re not yet registered, just go to {registrationPageLink} and pass the registration. Or you can choose another authentication method.', array('{registrationPageLink}' => CHtml::link(Yii::t('lily', "registration page"), array("/userControl/userProfile/registration")))); ?></p>
                <?php } ?>
                <p class="note"><?php echo Yii::t('lily', 'If you forgot your password, you can restore it using {restorePageLink}', array('{restorePageLink}' => CHtml::link(Yii::t('lily', 'restore password.'), Yii::app()->createUrl('/' . LilyModule::route('account/restore'))))); ?>.</p>
                <?php
                foreach (Yii::app()->user->getFlashes() as $key => $message) {
                    echo '<div class="panel panel-default flash-' . $key . '">' . $message . "</div>\n";
                }
                ?>
                <p><?php echo Yii::t('lily', 'Please fill out the following form with your login credentials:'); ?></p>
                <?php echo $form->emailFieldRow($model, 'email', array('class' => 'span3')); ?>
                <?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span3', 'autocomplete' => 'off')); ?>
            </div>
        <?php } ?>
        <?php echo $form->checkBoxRow($model, 'rememberMe', array('class' => '')); ?>
        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => $submitLabel,
            ));
            ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->