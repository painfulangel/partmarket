<?php
/**
 * Created by PhpStorm.
 * User: foreach
 * Date: 03.05.19
 * Time: 18:31
 */
$this->pageTitle = Yii::t('app', 'New Search - Search Result');
$this->breadcrumbs=array(
    Yii::t('app', 'Search Result'),
);
?>
<h1>Результаты поиска</h1>
<table class="items table">
    <thead>
    <tr>
        <th style="text-align: center;vertical-align: top;">
            <a name="brand">
                Производитель
            </a>
        </th>
        <th style="text-align: center; color: #0088cc;vertical-align: top;">Номер</th>
        <th style="text-align: center;vertical-align: top;">
            <a name="name">Наименование</a>
        </th>
        <th style="text-align: center;vertical-align: top;">
            <a name="price">
                Цена
            </a>
        </th>
        <th style="text-align: center;vertical-align: top;">
            <a name="dostavka">Срок доставки (дней)</a>
        </th>
        <th style="text-align: center;vertical-align: top;">
            <a name="kolichestvo">Кол-во (на складе)</a>
        </th>
        <th style="text-align: center; color: #0088cc;vertical-align: top;">Склад</th>
        <th style="text-align: center; color: #0088cc;vertical-align: top;">Кол-во (в заказ)</th>
    </tr>
    </thead>
    <tbody id="main_load_block">
    <!--<tr>
        <td colspan="8">
            <center class="Filter_detail_name">Искомый артикул</center>
        </td>
    </tr>
    <tr>
        <td colspan="8">
            <center>Товаров не найдено</center>
        </td>
    </tr>
    <tr>
        <td colspan="8">
            <center class="Filter_detail_name">Аналоги и замены других производителей</center>
        </td>
    </tr>-->
    <?php foreach ($result as $item):?>
    <tr>
        <td rowspan="1">
            <div> <?php echo $item['brand'];?></div>
        </td>
        <td rowspan="1"><?php echo $item['articul'];?></td>
        <td><?php echo $item['name'];?></td>
        <td><b><?php echo $item['price_echo'];?></b></td>
        <td><?php echo $item['dostavka'];?></td>
        <td><?php echo $item['kolichestvo'];?></td>
        <td>
            <div><?php echo $item['store'];?></div>
        </td>
        <td class="buy">
            <form action="/shop_cart/shoppingCart/create/" target="_footer_iframe" method="post">
                <div style="display:none">
                    <input type="hidden" value="<?php echo Yii::app()->request->csrfToken;?>" name="<?php echo Yii::app()->request->csrfTokenName?>">
                </div>
                <div style="clear: both;"></div>
                <input type="hidden" name="brand" value="<?php echo $item['brand'];?>">
                <input type="hidden" name="article" value="<?php echo $item['articul'];?>">
                <input type="hidden" name="price_group_1" value="<?php echo $item['price_group_1'];?>">
                <input type="hidden" name="price_group_2" value="<?php echo $item['price_group_2'];?>">
                <input type="hidden" name="price_group_3" value="<?php echo $item['price_group_3'];?>">
                <input type="hidden" name="price_group_4" value="<?php echo $item['price_group_4'];?>">
                <input type="hidden" name="supplier_price" value="<?php echo $item['supplier_price'];?>">
                <input type="hidden" name="price_purchase" value="<?php echo $item['price_purchase'];?>">
                <input type="hidden" name="price_purchase_echo" value="<?php echo $item['price_purchase_echo'];?>">
                <input type="hidden" name="price" value="<?php echo $item['price'];?>">
                <input type="hidden" name="price_echo" value="<?php echo $item['price_echo'];?>">
                <input type="hidden" name="description" value="<?php echo $item['store_description'];?>">
                <input type="hidden" name="article_order" value="<?php echo $item['articul_order'];?>">
                <input type="hidden" name="supplier_inn" value="<?php echo $item['supplier_inn'];?>">
                <input type="hidden" name="supplier" value="<?php echo $item['supplier'];?>">
                <input type="hidden" name="store_id" value="<?php echo $item['store_id'];?>">
                <input type="hidden" name="store" value="<?php echo $item['store'];?>">
                <input type="hidden" name="name" value="<?php echo $item['name'];?>">
                <input type="hidden" name="delivery" value="<?php echo $item['dostavka'];?>">
                <input type="hidden" name="quantum_all" value="<?php echo $item['kolichestvo'];?>">
                <input type="hidden" name="price_data_id" value="<?php echo $item['price_data_id'];?>">
                <input type="hidden" name="store_count_state" value="<?php echo $item['store_count_state'];?>">
                <input style="width: 20px;" class="textfld" type="text" value="1" name="quantum" min="1" max="10" id="quantum">
                <input type="hidden" name="weight" value="">
                <input class="js-btn-add-cart" onclick="if(10=='в наличии'||(this.form.quantum.value<=10&amp;&amp;10>0)){return true;}else {alert('Нет в наличии.');return false;}" style="width: 38px; height: 29px; border:none;" type="submit" name="yt0" value="">
            </form>
        </td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
