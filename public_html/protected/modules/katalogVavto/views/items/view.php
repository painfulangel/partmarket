<?php
$this->pageTitle = $model->title;
$this->metaTitle = $model->meta_title;
$this->metaDescription = $model->meta_description;
$this->metaKeywords = $model->meta_keywords;
$this->breadcrumbs = $model->breadcrumbs;
?>
<h1><?php echo $model->title ?></h1>
<div class="katalog_vavto_main_pic">
    <?php
    if (!empty($model->image)) {
    	echo CHtml::image('/'.$model->getAttachment(), $model->short_text);
    } else {
    	echo CHtml::image('/images/KatalogVavtoItems/4.png', $model->short_text);
    }
    ?>
</div>
<h2><?php echo $model->getAttributeLabel('article').':' ?> <?php echo $model->article ?></h2>
<?php
$ar = trim($model->article);
if (!empty($ar)) {
?>
    <div class="d_price"><?php echo CHtml::link('Узнать цену', (Yii::app()->config->get('Site.SearchType') == 1 ? array('/detailSearchNew/default/search', 'article' => $model->article) : array('/detailSearch/default/search', 'search_phrase' => $model->article))) ?></div>
<?php } ?>
<h5>Быстрый заказ</h5>
<?php
$brand = '';
$t = '';
$marka = '';
foreach ($model->breadcrumbs as $k => $v) {
    if (empty($brand)) {
        $brand = $k;
        continue;
    }
    if (empty($t)) {
        $t = $k;
        continue;
    }
    if (!empty($marka)) {
        $marka.=', ';
    }
    $marka.=$t;
}

if (!$formSend) {
	$model2->detail = $model->title.',  '.Yii::t('katalogVavto', 'Article').' '.$model->article;
	$model2->car_model = $marka;
	$model2->car_brand = $brand;
}

$this->renderPartial('requests.views.requestGetPrice._form', array('model' => $model2));
?>
<div id="katalogVavtoView">
    <div class="clear"></div>
</div>
<div><?php echo $model->text ?></div>
<style type="text/css">
	.katalog_vavto_main_pic img {
		max-height: 200px;
	}
</style>