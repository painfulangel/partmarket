<?php
$this->breadcrumbs = array(
    Yii::t('requests', 'Leave feedback'),
);
$this->pageTitle = Yii::t('requests', 'Leave feedback');
?>

<h1><?= Yii::t('requests', 'Leave feedback') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>