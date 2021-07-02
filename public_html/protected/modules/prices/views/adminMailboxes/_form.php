<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'prices-ftp-autoload-mailboxes-form',
    'enableAjaxValidation' => false,
        ));
if ($model->isNewRecord && empty($model->pop_port))
    $model->pop_port = '110';
?>

<p class="help-block"><?= Yii::t('prices', 'Fields containing') ?> <span class="required">*</span> <?= Yii::t('prices', 'Indispensable to filling.') ?></p>


<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldRow($model, 'mailbox', array('class' => 'span5', 'maxlength' => 45)); ?>

<?php echo $form->passwordFieldRow($model, 'password', array('class' => 'span5', 'maxlength' => 45)); ?>

<?php echo $form->dropDownListRow($model, 'protocol', PricesFtpAutoloadMailboxes::getMailProtocols(), array(
    'class' => 'span5',
    'empty' => Yii::t('prices', 'Select a protocol...')
)); ?>

<div id="pop-box" <?php echo (!$model->isNewRecord && $model->protocol == PricesFtpAutoloadMailboxes::IMAP_PROTOCOL) ? 'style="display: none;"' : '';?>>
    <?php echo $form->textFieldRow($model, 'pop_adress', array('class' => 'span5', 'maxlength' => 45)); ?>

    <?php echo $form->textFieldRow($model, 'pop_port', array('class' => 'span5', 'maxlength' => 45)); ?>
</div>

<div id="imap-box" <?php echo (!$model->isNewRecord && $model->protocol == PricesFtpAutoloadMailboxes::POP_PROTOCOL) ? 'style="display: none;"' : '';?>>
    <?php echo $form->textFieldRow($model, 'imap_address', array('class' => 'span5', 'maxlength' => 45)); ?>

    <?php echo $form->textFieldRow($model, 'imap_port', array('class' => 'span5', 'maxlength' => 45)); ?>
</div>

<?php echo $form->dropDownListRow($model, 'expire', PricesFtpAutoloadMailboxes::getMailExpire(), array(
    'class' => 'span5',
    //'empty' => Yii::t('prices', 'Select expire days...')
)); ?>

<?php /*echo $form->dropDownListRow($model, 'frequency', PricesFtpAutoloadMailboxes::getCronFrequency(), array(
    'class' => 'span5',
)); */?>

<?php
if (empty($model->cron_general)) {
    $model->cron_general = 7;
}
echo $form->dropDownListRow($model, 'cron_general', array(
    '1' => 'каждые 5 минут',
    '2' => 'каждые 30 минут',
    '3' => Yii::t('prices', 'Everyone hour'),
    '4' => Yii::t('prices', 'Each 3 hours'),
    '5' => Yii::t('prices', 'Each 6 hours'),
    '6' => Yii::t('prices', 'Each 12 hours'),
    '7' => Yii::t('prices', 'Every day'),
    '8' => Yii::t('prices', 'each 2 days'),
    '9' => Yii::t('prices', 'each 3 days'),
), array('class' => ' span5 tool-tip', 'maxlength' => 32));
?>

<?php echo $form->checkBoxRow($model, 'delete_old', array('class' => '')); ?>

<?php echo $form->checkBoxRow($model, 'just_new', array('class' => '')); ?>

<?php echo $form->checkBoxRow($model, 'state', array('class' => ' tool-tip', 'title' => '')); ?>

<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('prices', 'Add') : Yii::t('prices', 'Save'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>

<script>
    $(document).ready(function () {
        $('#PricesFtpAutoloadMailboxes_protocol').on('change', function () {
            var protocol = $(this).val();
            console.log(protocol);
            if(protocol == '<?php echo PricesFtpAutoloadMailboxes::IMAP_PROTOCOL?>'){
                $('#imap-box').show();
                $('#pop-box').hide();
            }

            if(protocol == '<?php echo PricesFtpAutoloadMailboxes::POP_PROTOCOL?>'){
                $('#imap-box').hide();
                $('#pop-box').show();
            }
        });
    });
</script>