<?php
$this->breadcrumbs = array(
    Yii::t('news', 'News') => array('index'),
    $model->title,
);

$this->pageTitle = $model->title;
$this->metaKeywords = $model->keywords;
$this->metaDescription = $model->description;
?>

<h1><?php echo $model->title; ?></h1>
<div class="b_news_single">
    <p class="date"><?= date('d.m.Y', $model->create_time) ?></p>
    <?= $model->text ?>
</div>

