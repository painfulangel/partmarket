<?php
$this->breadcrumbs = array(
    Yii::t('requests', 'Reviews'),
);
$this->pageTitle = Yii::t('requests', 'Reviews');
?>

<h1><?= Yii::t('requests', 'Reviews') ?></h1>
<div>
    <?= CHtml::link(Yii::t('requests', 'Leave feedback'), array('/requests/feedbacks/create'), array('class' => 'btn btn-primary')) ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'template' => '{items} {pager}',
));
?>
