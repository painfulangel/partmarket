<?php
$this->breadcrumbs = array(
    Yii::t('userControl', 'User profile'),
);

$this->pageTitle = Yii::t('userControl', 'User profile');
?>

<h1><?= Yii::t('userControl', 'User profile') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>