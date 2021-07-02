<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 20.02.17
 * Time: 19:43
 */
$this->pageTitle = $model->brand->name.'/'.$model->model->name.'/'.$model->mod->name.'/'.$model->name;
$this->breadcrumbs=array(
    Yii::t(UsedModule::TRANSLATE_PATH, 'Used Brands') => array('/used/cars'),
    $model->brand->name => array('/used/cars/brand', 'brand'=>$model->brand->slug),
    $model->model->name => array('/used/cars/mod', 'brand'=>$model->brand->slug, 'model'=>$model->model->slug),
    $model->mod->name => array('/used/cars/modification', 'brand'=>$model->brand->slug, 'model'=>$model->model->slug, 'modification'=>$model->mod->slug),
    $model->name,
);
Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/../css/'). '/jquery.bxslider.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/../js/'). '/jquery.bxslider.min.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/../css/'). '/jquery.fancybox.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/../js/'). '/jquery.fancybox.min.js', CClientScript::POS_HEAD);

?>
<div id="content">
    <h1><?php echo $this->pageTitle;?></h1>

    <div class="panel">
        <div class="row-fluid">
            <div class="span12">
                <?php //echo CVarDumper::dump($model->attributes, 10, true);?>

                <div id="theContent" class="span12">

                    <div style="margin-top:0" class="page-header h1_small">
                        <h1><?php echo $model->name;?></h1>
                    </div>

                    <input type="hidden" value="0" id="od">
                    <input type="hidden" value="1" id="condition">
                    <input type="hidden" value="30345294" id="tvid">
                    <input type="hidden" value="10" id="firmid">
                    <input type="hidden" value="16680PE7661" id="orignr">
                    <input type="hidden" value="1096" id="modelId">
                    <input type="hidden" value="1476" id="partid">

                    <?php $slides = $model->imagesItemsForBxImages();?>

                    <div class="row-fluid">
                        <div id="block_img" class="span4">
                            <?php if(!$slides):?>
                                <img src="/uploads/models/default.jpg" class="img-responsive" style="height: 200px;width: 292px;">
                            <?php else:?>
                            <ul class="bxslider">
                                <?php
                                foreach ($slides as $itemImg) {
                                    echo $itemImg;
                                }
                                ?>
                            </ul>

                            <div id="bx-pager">
                                <?php foreach ($model->imagesItemsForBxControls() as $itemControl) {
                                    echo $itemControl;
                                } ?>
                            </div>
                            <script>
                                $('.bxslider').bxSlider({
                                    infiniteLoop: false,
                                    pagerCustom: '#bx-pager'
                                });
                            </script>
                            <?php endif;?>

                        </div> <!-- #block_img -->

                        <div class="span5">
                            <div style="margin-bottom: 10px;">
                                <strong>Производитель:</strong>  <?php echo $model->brandItem->name;?>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <strong>Состояние:</strong>  <?php echo $model->getState();?>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <strong>Наличие:</strong> <?php echo $model->availability;?>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <strong>Тип:</strong> <?php echo $model->getType();?>
                            </div>
                            <div style="margin-bottom: 10px;">
                                <strong>Внутренний номер:</strong> <?php echo $model->vendor_code;?>
                            </div>
                            <?php if($model->original_num):?>
                            <div style="margin-bottom: 10px;">
                                <strong>Номер ЗЧ:</strong>  <?php echo $model->original_num;?>
                            </div>
                            <?php endif;?>
                            <?php if($model->replacement):?>
                            <div style="margin-bottom: 10px;">
                                <strong>Номер ЗЧ(заменен производителем):</strong>  <?php echo $model->replacement;?>
                            </div>
                            <?php endif;?>
                            <div style="margin-bottom: 10px;">
                                <strong>Цена:</strong>   <?php //echo $model->price;?><?php echo UsedItems::getPriceMarkup($model->vendor_code, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => 'local_my'));?>
                            </div>
                            <div>
                                <?php echo $model->description;?>
                            </div>
                        </div>
                        <div class="span3 buy-block">
                            <div style="min-height: 80px;" class="bg-warning alert">
                                <div style="padding-top:10px;">

                                    <span style="font-size:1.8em;">
                                        <?php //echo $model->price;?>
                                        <?php echo UsedItems::getPriceMarkup($model->vendor_code, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => 'local_my'));?>
                                    </span>
                                    <!--<span class="rub" style="font-size:1.8em;">руб.</span>-->
                                </div>
                                <div class="row-fluid">
                                    <div class="span12 text-left">
                                        <div style="width: 140px;padding-top: 20px;" class="input-group input-group-sm">
                                            <!-- Добавлние в корзину. -->
                                            <?php /*echo CHtml::form('/shop_cart/shoppingCart/create');*/?><!--
                                                <input id="brand" type="hidden" name="brand" value="<?php /*echo $model->brandItem->name;*/?>">
                                                <input id="article" type="hidden" name="article" value="<?php /*echo $model->vendor_code;*/?>">
                                                <input id="price" type="hidden" name="price" value="<?php /*echo $model->price;*/?>">
                                                <input id="price_echo" type="hidden" name="price_echo" value="<?php /*echo UsedItems::getPriceMarkup($model->vendor_code, array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => 'local_my'));*/?>">
                                                <input id="description" type="hidden" name="description" value="<?php /*echo $model->description;*/?>">
                                                <input id="article_order" type="hidden" name="article_order" value="<?php /*echo $model->vendor_code;*/?>">
                                                <input id="supplier_inn" type="hidden" name="supplier_inn" value="7777777">
                                                <input id="supplier" type="hidden" name="supplier" value="My Cat">
                                                <input id="go_link" type="hidden" name="go_link" value="<?php /*echo Yii::app()->request->requestUri;*/?>">
                                                <input id="store" type="hidden" name="store" value="Каталог б/у">
                                                <input id="name" type="hidden" name="name" value="<?php /*echo $model->title;*/?>">
                                                <input id="delivery" type="hidden" name="delivery" value="<?php /*echo $model->delivery_time;*/?>">
                                                <input id="quantum_all" type="hidden" name="quantum_all" value="">
                                                <input id="price_data_id" type="hidden" name="price_data_id" value="<?php /*echo $model->getPriceDataId();*/?>">
                                                <input id="store_count_state" type="hidden" name="store_count_state" value="0">
                                                <input id="weight" type="hidden" name="weight" value="">
                                                <input id="quantum" type="hidden" name="quantum" value="1">
                                                <span class="">
                                                    <button class="cart-js-btn-add-cart btn btn-primary btn-sm" onclick="" type="submit">
                                                        <i class="glyphicon glyphicon-shopping-cart"></i>
                                                        В корзину
                                                    </button>
                                                </span>
                                            <?php /*echo CHtml::endForm();*/?>

                                            <hr>-->

                                            <?php echo Yii::app()->getModule('shop_cart')->getForm(Yii::app()->controller->module->getCartFormData($model));?>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div> <!-- .buy-block -->

                    </div>

                    <!-- noindex -->
                    <div rel="nofollow" id="teb_unit-advantages" class="row-fluid hidden-print">
                        <div class="span12">
                            <!-- Nav tabs -->
                            <h4>Применимость</h4>
                            <!-- /noindex -->
                            <!-- Tab panes -->
                            <div id="tab-info" class="tab-content">
                                <div id="applicability" class="tab-pane fade in active" role="applicability">
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <?php foreach ($model->usedItemsUsages as $k=>$usage):?>
                                                <a title="" href="/used/cars/<?php echo $usage->mod->brand->slug;?>/<?php echo $usage->mod->model->slug;?>/<?php echo $usage->mod->slug;?>">
                                                    <?php echo $usage->mod->brand->name.' '.$usage->mod->model->name.' '.$usage->mod->name;?>
                                                </a><br>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
<div class="clearfix"></div>
<script>
    $(document).ready(function(){
        $('#quantum').hide();
    });
</script>