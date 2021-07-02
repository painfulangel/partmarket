<?php
/*$assetsDir = dirname(dirname(dirname(__FILE__))).'/assets';
$folder = Yii::app()->assetManager->publish($assetsDir);
$css = $folder.'/css';
Yii::app()->clientScript->registerCssFile($css.'/admin.css');*/

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'                   => 'prop-form',
    'enableAjaxValidation' => false,
    'type'                 => 'horizontal',
	'htmlOptions'		   => array('enctype' => 'multipart/form-data'),
));
?>
<?php echo $form->errorSummary($model); ?>
<?php
$tabs = array(
    array(
        'label' => Yii::t('universal', 'Page'),
        'content' => $this->renderPartial('_content', array('form' => $form, 'model' => $model, 'chars' => $chars), true),
        'active' => true
    ),
    array(
        'label' => Yii::t('universal', 'SEO'),
        'content' => $this->renderPartial('_seo', array('form' => $form, 'model' => $model), true),
    ),
    array(
        'label' => Yii::t('universal', 'Pictures'),
        'content' => $this->renderPartial('_gallery_form', array('form' => $form, 'model' => $model), true),
    ),
);

$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs',
    'tabs' => $tabs,
))
?>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord ? Yii::t('universal', 'Add') : Yii::t('universal', 'Save'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>