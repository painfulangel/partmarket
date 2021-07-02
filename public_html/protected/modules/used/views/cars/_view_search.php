<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 14.12.16
 * Time: 13:36
 */
?>
<div class="span6 art-search-result">
    <div class="name">
        <a href="<?php echo Yii::app()->createUrl('/used/cars/item', array('slug'=>$model->slug));?>"><?php echo $model->name;?></a>
    </div>
    <div class="block">
        <div class="image">
            <a class="fancybox" href="<?php echo $model->getFrontImage();?>" title="<?php echo $model->name;?>">
                <img src="<?php echo $model->getFrontImage();?>">
            </a>
        </div>
    </div>
    <div class="chars">
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Производитель: <?php echo $model->brandItem->name;?></span>
            </div>
        </div>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Состояние: <?php echo $model->getState();?></span>
            </div>
        </div>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Наличие: <?php echo $model->availability;?></span>
            </div>
        </div>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Тип: <?php echo $model->getType();?></span>
            </div>
        </div>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Внутренний номер: <?php echo $model->vendor_code;?></span>
            </div>
        </div>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Номер ЗЧ: <?php echo $model->original_num;?></span>
            </div>
        </div>
        <div class="char">
            <div class="lb">
                <span class="item-line_info">Цена: <?php echo $model->price;?></span>
            </div>
        </div>
    </div>
</div>

