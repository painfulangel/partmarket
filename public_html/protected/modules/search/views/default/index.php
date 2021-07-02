<?php
/* @var $this DefaultController */
$this->pageTitle = Yii::t('app', 'New Search');

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<h1><?php echo Yii::t('app', 'Search spare')?></h1>
<form class="form-search" method="get" action="<?php echo Yii::app()->createUrl('/search/default/brands');?>">
    <input type="text" name="search_phrase" class="input-medium search-query">
    <button type="submit" class="btn"><?php echo Yii::t('app', 'Search')?></button>
</form>