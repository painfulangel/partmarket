<?php
$this->breadcrumbs = array(
    Yii::t('requests', 'Request price and availability details'),
);
$this->pageTitle = Yii::t('requests', 'Request price and availability details');
?>

<h1><?= Yii::t('requests', 'Request price and availability details') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model, 'recaptchakey' => $recaptchakey)); ?>