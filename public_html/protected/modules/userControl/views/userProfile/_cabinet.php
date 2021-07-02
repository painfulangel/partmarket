
<div class = "cab-info balance pull-left">
    <div class = "cab-info-title">
        <h3>Состояние баланса</h3>
    </div>
    <div class = "cab-info-content">
        <dl>
            <dt>Баланс:</dt>
            <dd><?= CHtml::link($model->balance, array('/userControl/userBalance/index')) ?></dd>
            <dt>Сумма за период:</dt>
            <dd><?php echo $model->getMonthTotalValue() ?></dd>
            <dt>Отсрочка платежа дн.:</dt>
            <dd class = "danger"><?= ($model->stop_list_period > 0 ? $model->stop_list_period : 0) ?></dd>
            <dt>Профиль:</dt>
            <?php
            if (true) {
                if ($model->legal_entity == 1) {
                    $this->widget('bootstrap.widgets.TbDetailView', array(
                        'data' => $model,
                        'attributes' => array(
                            'email',
                            'first_name',
                            'second_name',
                            'father_name',
                            'phone',
                            'extra_phone',
                            'skype',
                            //'balance',
                            'organization_name',
                            'organization_type',
                            'organization_inn',
                            'organization_ogrn',
                            'okpo',
                            'bank_kpp',
                            'bank_bik',
                            'bank',
                            'bank_rc',
                            'bank_ks',
                            'organization_director',
                            'delivery_zipcode',
                            'delivery_city',
                            'delivery_country',
                            'delivery_street',
                            'delivery_house',
                            'comment',
                            'legal_zipcode',
                            'legal_city',
                            'legal_country',
                            'legal_street',
                            'legal_house',
                        ),
                    ));
                } else if ($model->legal_entity == 2) {
                    $this->widget('bootstrap.widgets.TbDetailView', array(
                        'data' => $model,
                        'attributes' => array(
                            'email',
                            'first_name',
                            'second_name',
                            'father_name',
                            'phone',
                            'extra_phone',
                            'skype',
                            //'balance',
                            'organization_name',
                            'organization_inn',
                            'ogrnip',
                            'okpo',
                            'bank_kpp',
                            'bank_bik',
                            'bank',
                            'bank_rc',
                            'bank_ks',
//            'organization_director',
                            'delivery_zipcode',
                            'delivery_city',
                            'delivery_country',
                            'delivery_street',
                            'delivery_house',
                            'comment',
                            'legal_zipcode',
                            'legal_city',
                            'legal_country',
                            'legal_street',
                            'legal_house',
                        ),
                    ));
                } else {

                    $this->widget('bootstrap.widgets.TbDetailView', array(
                        'data' => $model,
                        'attributes' => array(
                            'email',
                            'first_name',
                            'second_name',
                            'father_name',
                            'phone',
                            'extra_phone',
                            'skype',
//            'balance',
                            'delivery_zipcode',
                            'delivery_city',
                            'delivery_country',
                            'delivery_street',
                            'delivery_house',
                            'comment',
                        ),
                    ));
                }
            }
            ?>

        </dl>

    </div>
    <div class = "cab-info print-docs pull-left">
        <div class = "cab-info-title">
            <h3>Документы для печати</h3>
        </div>
        <div class = "cab-info-content">
            <div class = "search-wrap">
                <form action="<?= $this->createUrl('/shop_cart/orders/index') ?>">
                    <input type = "text" name="Orders[id]" class = "form-control" placeholder = "Номер заказа"/>
                    <button type = "submit" class = ""></button>
                </form>
            </div>
            <ul>
                <!--<li><a href = "#">Счет</a></li>-->
                <!--<li><a href = "#">Счет-фактура</a></li>-->
                <li><?= CHtml::link('Счет-фактура',array('/shop_cart/orders/bill')) ?></li>
                <li><?= CHtml::link('Расходная накладная',array('/shop_cart/orders/waybill')) ?></li>
                <li><?= CHtml::link('Товарная накладная',array('/shop_cart/orders/checkDocument')) ?></li>
                <!--<li><a href = "#">Договор</a></li>-->
            </ul>
        </div>
    </div>
</div>
<div class = "cab-info manager pull-right">
    <div class = "cab-info-title">
        <h3>Персональный менеджер</h3>
    </div>
    <div class = "cab-info-content">
        <p>По вопросам планирования доставки, произведением отгрузки товара, по претензиям или возврату товара, а также, взаиморасчетам необходимо связаться с Персональным менеджером</p>
        <a href = "#">Жириков Игорь Юрьевич</a>
        <dl>
            <dt>тел.:</dt>
            <dd>+7 (495)236-99-33</dd>
            <dt>e-mail:</dt>
            <dd>igor@autojek.ru</dd>
            <dt>Skype:</dt>
            <dd>Jekzapp</dd>			
        </dl>
    </div>
</div>

<div class = "cab-info recharge pull-right">
    <div class = "cab-info-title">
        <h3>Способы пополнения баланса</h3>
    </div>
    <div class = "cab-info-content">
        <ul>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/sber-bank.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/svyaznoy.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/uni-stream.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/webmoney.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/ya.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/alfa.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/beeline.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/ediniy.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/evroset.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/mail.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/master.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/robo.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/rbk.png" alt = ""/></a>
            </li>
            <li>
                <a href = "<?= $this->createUrl('/webPayments/webPayments/pay') ?>"><img src = "/images/icons/qiwi.png" alt = ""/></a>
            </li>
        </ul>
    </div>
</div>
