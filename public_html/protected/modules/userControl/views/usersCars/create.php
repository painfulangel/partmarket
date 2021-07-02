<?php
$this->breadcrumbs = array(
    Yii::t('userControl', 'Creating a new car'),
);
$this->pageTitle = Yii::t('userControl', 'Creating a new car');
?>

<h1><?= Yii::t('userControl', 'Creating a new car') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>