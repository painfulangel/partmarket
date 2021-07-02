<?php
$this->breadcrumbs = array(
    Yii::t('userControl', 'My Cars') => array('index'),
    Yii::t('userControl', 'Edit car'),
);
$this->pageTitle = Yii::t('userControl', 'Edit car');
?>

<h1><?= Yii::t('userControl', 'Edit car') ?></h1>
<?php echo $this->renderPartial('_details', array('model' => $model2, 'details' => $details)); ?>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>