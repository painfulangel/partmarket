<?php
$this->breadcrumbs = array(
    Yii::t('news', 'News'),
);
$this->pageTitle = Yii::t('news', 'News');
?>

<h1><?= Yii::t('news', 'News') ?></h1>

<?php
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'template' => "{items}\n{pager}",
    'htmlOptions' => array('class' => 'b_news_list'),
));
?>
