<?php
$this->pageTitle = $model->title;
$this->metaTitle = $model->meta_title;
$this->metaDescription = $model->meta_description;
$this->metaKeywords = $model->meta_keywords;
$this->breadcrumbs = $model->breadcrumbs;
?>

<h1><?= $model->title ?></h1>
<div id="katalogAccessoriesView">
    <div class="span3 pull-left  images">
        <?php
        $this->widget('ext.jquery_fancybox.FancyboxWidget', array('items' => $model->getImages()));
        ?>
    </div>

    <div class="span5 pull-left">
        <div>
            <?= $model->text ?>
        </div>
        <div>
            
            <b><?php echo Yii::t('katalogAccessories', 'Price') ?>:</b> <?= Yii::app()->controller->module->getPriceFormatFunction(Yii::app()->controller->module->getPriceFunction(array('price' => $model->price, 'brand' => '0', 'price_price_group' => Yii::app()->config->get('KatalogAccessories.PriceGroup'.Yii::app()->getModule('pricegroups')->getUserGroup())))) ?>
            <br/>
            <?php
            echo Yii::app()->getModule('shop_cart')->getForm(Yii::app()->controller->module->getCartFormData(array('model' => $model)));
            ?>
        </div>
    </div>
    <div class="clear"></div>
</div>