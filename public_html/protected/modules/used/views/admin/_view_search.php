<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 14.12.16
 * Time: 13:36
 */
?>
<a href="/used/items/update/id/<?php echo $model->id;?>" target="_blank">
<div class="parts-item_box">
    <div class="boxheader row-fluid">
        <div class="span10">
            <!--<a href="#">-->
            <?php echo $model->name;?>
            <!--</a>-->
        </div>
        <div class="span2 text-right">
            <!--<a href="/used/items/update/id/<?php /*echo $model->id;*/?>"><i class="icon-edit"></i></a>
            --><?php /*echo CHtml::ajaxLink(
                $text = '<i class="icon-remove"></i>',
                $url = '/used/items/deleteAjax/id/'.$model->id,
                $ajaxOptions=array (
                    'type'=>'GET',
                    'dataType'=>'html',
                    'success'=>'function(html){if(html==1){jQuery(".item-'.$model->id.'").remove();} }'
                ),
                $htmlOptions=array ('id'=>'it-'.uniqid())
            );*/?>
        </div>
    </div>
    <div class="row-fluid">
        <div class="item-img span3">
            <img src="<?php echo $model->getFrontImage();?>" style="min-width: 100%;">
        </div>
        <div class="span9">
            <span class="item-line_info">Производитель: <?php echo $model->brandItem->name;?></span>
            <span class="item-line_info">Состояние: <?php echo $model->getState();?></span>
            <span class="item-line_info">Наличие: <?php echo $model->availability;?></span>
            <span class="item-line_info">Тип: <?php echo $model->getType();?></span>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <p class="pull-right"><?php echo $model->price;?></p>
        </div>
    </div>
</div>
</a>
