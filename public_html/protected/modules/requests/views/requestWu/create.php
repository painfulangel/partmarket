<?php
$this->breadcrumbs = array(
  Yii::t('requests',   'Requests for replacement parts PU'),
);
$this->pageTitle = Yii::t('requests',   'Requests for replacement parts PU');
?>

<h1><?= Yii::t('requests',   'Requests for replacement parts PU') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model, 'recaptchakey' => $recaptchakey)); ?>