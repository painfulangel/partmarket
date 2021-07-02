<?php
/*$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'web-payments-robokassa-form',
    'action' => 'https://merchant.roboxchange.com/Index.aspx',
    'enableAjaxValidation' => false,
));
?>
<div class="control-group " style="display: none;">
    <b>Сумма к оплате:</b>
    <?php echo Yii::app()->getModule('currencies')->getDefaultPrice($model->total_value) ?>
</div>
<?php
    // регистрационная информация - логин
    echo CHtml::hiddenField('MrchLogin', $model->system_login);
    // сумма заказа
    echo CHtml::hiddenField('OutSum', $model->total_value);
    // номер заказа
    echo CHtml::hiddenField('InvId', $model->id);
    // описание заказа
    echo CHtml::hiddenField('Desc', $model->description);
    // формирование подписи
    echo CHtml::hiddenField('SignatureValue', $model->getSign(1));
    // тип товар
    echo CHtml::hiddenField('Shp_item', $model->id);
    echo CHtml::hiddenField('IncCurrLabel', '');
    // язык
    echo CHtml::hiddenField('Culture', 'ru');
?>
<div class="form-actions">
<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('webPayments', 'Proceed to payment'),
    ));
?>
</div>
<?php $this->endWidget();*/ ?>
<form action="https://loans-qa.tcsbank.ru/api/partners/v1/lightweight/create" method="post" id="web-payments-credit-form">
    <input name="shopId" value="test_online" type="hidden"/><?php //Уникальный идентификатор магазина, выдается банком при подключении ?>
    <input name="showcaseId" value="test_online" type="hidden"/><?php //Идентификатор витрины магазина. Витрины —это различные сайты, зарегистрированные на одно юридическое лицо. В случае единственной витрины можно не указывать. ?>
    <input name="promoCode" value="default" type="hidden"/><?php //Указывается в случае, если на товар распространяется акции (например, рассрочки). Подробности уточняйте у персонального менеджера. ?>
    <input name="sum" value="<?php echo number_format($model->total_value, 2, '.', ''); ?>" type="hidden"><?php //Сумма всех позиций заказа в рублях. Число с двумя десятичными знаками и разделителем точкой. ?>
    <?php
        if (is_object($order) && ($count = count($order->items))) {
            $items = $order->items;

            for ($i = 0; $i < $count; $i ++) {
                $item = $items[$i];
    ?>
    <?php //Состав заказа, где N — порядковый номер товара в заказе. Нумерация начинается с 0. Вся информация о заказе, переданная через эти поля, придет в уведомительных письмах. ?>
    <input name="itemName_0" value="<?php echo $item->name; ?>" type="hidden"/><?php //Название товара. ?>
    <input name="itemQuantity_0" value="<?php echo $item->quantum; ?>" type="hidden"/><?php //Количество единиц товара. ?>
    <input name="itemPrice_0" value="<?php echo number_format($item->price, 2, '.', ''); ?>" type="hidden"/><?php //Стоимость единицы товара в рублях. Число с двумя десятичными знаками и разделителем точкой. ?>
    <?php /* ?><input name="itemCategory_0" value="iPhone Apple" type="hidden"/><?php //Категория товара: мебель, электроника, бытовая техника (необязательно).*/ ?>
    <?php
            }
        }
    ?>
    <input name="orderNumber" value="turbomotors<?php echo $model->id; ?>" type="hidden"/><?php //Номер заказа в системе магазина. Если его не передать, будет присвоен автоматически сгенерированный на стороне банка номер заказа. ?>
    <input name="customerEmail" value="fcbarcelona@cktv.ru<?php //if (is_object($order->User)) echo $order->User->email; ?>" type="hidden"/><?php //Адрес электронной почты клиента. ?>
    <?php /* ?><input name="customerPhone" value="+79031234567" type="hidden"/><?php //Номер мобильного телефона клиента. Формат: 10 или 11 цифр номера с любым форматированием: со скобками, пробелами, дефисами и т.п. Например, +7ХХХХХХХХХХ; 7(ХХХ)ХХХХХХХ; 8-ХХХ-ХХХ-ХХ-ХХ; (ХХХ)ХХХ-ХХ-ХХ. ?><?php */ ?>
    <input type="submit" value="Купи в кредит"/>
</form>
<script>
    document.getElementById('web-payments-credit-form').submit();
</script>