<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 20.02.17
 * Time: 18:31
 */
//echo CVarDumper::dump($data,10,true);exit;
?>
<div class="span6">
    <div class="name">
        <a href="<?php echo Yii::app()->createUrl('/used/cars/item', array('slug'=>$data['slug']));?>"><?php echo $data['name'];?></a>
    </div>
    <div class="block">
        <div class="image car-search-front-image">
            <a href="<?php echo Yii::app()->createUrl('/used/cars/item', array('slug'=>$data['slug']));?>" title="<?php echo $data['name'];?>">
                <img src="<?php echo UsedItems::frontImage($data['id']);?>" style="min-width: 100%;">
            </a>
        </div>
        <!--<div class="price">
            <a class="btn btn-inverse" target="_blank" href="/search?search_phrase=G+055+167+M2">Узнать цену</a>
        </div>-->
    </div>
    <div class="chars">
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Производитель: <?php echo UsedItems::brandName($data['brand_id']);?></span>
            </div>
        </div>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Состояние: <?php echo UsedItems::stateView($data['state']);?></span>
            </div>
        </div>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Наличие: <?php echo $data['availability'];?></span>
            </div>
        </div>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Тип: <?php echo UsedItems::typeView($data['type']);?></span>
            </div>
        </div>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Внутренний номер: <?php echo $data['vendor_code'];?></span>
            </div>
        </div>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Номер ЗЧ: <?php echo $data['original_num'];?></span>
            </div>
        </div>
        <?php if($data['replacement']):?>
            <div class="char">
                <div class="lb">
                    <span class="item-line_info">Номер ЗЧ(заменен производителем): <?php echo $data['replacement'];?></span>
                </div>
            </div>
        <?php endif;?>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">
                    Цена: <?php //echo $data['price'];?>
                    <?php echo UsedItems::getPriceMarkup($data['vendor_code'], array('price_group_id' => Yii::app()->getModule('pricegroups')->getUserGroup(), 'id' => 'local_my'));?>
                </span>
            </div>
        </div>
    </div>
</div>