<?php
	$this->pageTitle = $model->title;
	$this->metaTitle = $model->meta_title;
	$this->metaDescription = $model->meta_description;
	$this->metaKeywords = $model->meta_keywords;
	$this->breadcrumbs = $model->breadcrumbs;
?>
<h1><?php echo $model->title; ?></h1>
<?php
$dataProvider = $model->getItemsDataProvider();

if ($dataProvider->getTotalItemCount()) {
	$this->widget('bootstrap.widgets.TbListView', array(
	    'dataProvider' => $dataProvider,
	    'itemView' => '../items/_view',
	    'template' => '{items} {pager}',
	    'id' => 'katalog-accessories',
	));
} else {
	echo Yii::t('katalogAccessories', 'We work on filling the goods.');
}

if ($model->text) {
?>
<div style="margin-top:20px;">
    <?php echo $model->text; ?>
</div>
<?php } ?>