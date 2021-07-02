<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'languages-form',
    'enableAjaxValidation' => false,
        ));
?>
<?php echo $form->errorSummary($model); ?>
<?php
if (!$model->isNewRecord)
    echo $form->fileFieldRow($model, 'upload_files', array('class' => 'span5'));
?>
<?php echo $form->textFieldRow($model, 'name', array('class' => 'span5', 'maxlength' => 127)); ?>
<?php echo $form->textFieldRow($model, 'short_name', array('class' => 'span5', 'maxlength' => 127)); ?>
<?php
echo $form->dropDownListRow($model, 'link_name', array(
    'ru' => 'ru',
    'en' => 'en',
    'ar' => 'ar',
    'bg' => 'bg',
    'bs' => 'bs',
    'ca' => 'ca',
    'cs' => 'cs',
    'da' => 'da',
    'de' => 'de',
    'el' => 'el',
    'es' => 'es',
    'fa_ir' => 'fa_ir',
    'fi' => 'fi',
    'fr' => 'fr',
    'he' => 'he',
    'hu' => 'hu',
    'id' => 'id',
    'it' => 'it',
    'ja' => 'ja',
    'kk' => 'kk',
    'ko_kr' => 'ko_kr',
    'lt' => 'lt',
    'lv' => 'lv',
    'nl' => 'nl',
    'no' => 'no',
    'pl' => 'pl',
    'pt' => 'pt',
    'pt_br' => 'pt_br',
    'ro' => 'ro',
    'sk' => 'sk',
    'sr_sr' => 'sr_sr',
    'sr_yu' => 'sr_yu',
    'sv' => 'sv',
    'ta_in' => 'ta_in',
    'th' => 'th',
    'tr' => 'tr',
    'uk' => 'uk',
    'vi' => 'vi',
    'zh_cn' => 'zh_cn',
    'zh_tw' => 'zh_tw',
        ), array('class' => 'span5', 'maxlength' => 10));
?>
<?php echo $form->checkBoxRow($model, 'rewrite_files', array()); ?>
<?php echo $form->checkBoxRow($model, 'active', array()); ?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('languages', 'Add') : Yii::t('languages', 'Save'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>