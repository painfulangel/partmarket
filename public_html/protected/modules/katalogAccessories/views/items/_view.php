<div class="view">

    <div class="span2 pull-left image">
        <?= CHtml::link(CHtml::image('' . $data->getImage()), array('items/view', 'id' => $data->id)) ?>
    </div>
    <div class="span3 pull-left">
        <div>
            <b>     
                <?= CHtml::link($data->title, array('items/view', 'id' => $data->id)) ?>
            </b>
        </div>
        <div><?= $data->short_text ?> </div>
    </div>

    <div class="span2 pull-left">
        <b>
            <?= Yii::app()->controller->module->getPriceFormatFunction(Yii::app()->controller->module->getPriceFunction(array('price' => $data->price, 'brand' => '0', 'price_price_group' => Yii::app()->config->get('KatalogAccessories.PriceGroup'.Yii::app()->getModule('pricegroups')->getUserGroup())))) ?>
        </b>
    </div>
    <div class="span1 pull-left">
        <?php
        echo Yii::app()->getModule('shop_cart')->getForm(Yii::app()->controller->module->getCartFormData(array('model' => $data)));
        ?>
    </div>

    <div class="clear"></div>
</div>