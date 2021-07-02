<?php
$this->breadcrumbs = array(
    Yii::t('userControl', 'Registration'),
);

$this->pageTitle = Yii::t('userControl', 'Registration');
?>
<h1><?= Yii::t('userControl', 'Registration') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>