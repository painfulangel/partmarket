<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$model = Yii::app()->getModule('userControl')->getCurrentUserModel();
$model_balance = new UserBalanceOperations('search');
$model_balance->user_id = $model->uid;
?>
<div class="row-fluid">
	<div class="span6">
		<h1><?php echo $title ?></h1>
		<div class="count"><?php echo Yii::t('userControl', 'Your balance') ?>: <span><?php echo Yii::app()->getModule('currencies')->getFormatPrice($model_balance->getBalance()) ?></span> 
            <?php echo CHtml::link(Yii::t('userControl', 'Deposit'), array('/webPayments/webPayments/pay'), array('class' => 'btn btn-success', 'style' => 'width:90px; margin-bottom:10px;')) ?>
        </div>
		<div class="row-fluid">
			<div class="span3" id="met">
				<div>
					<img src="/images/theme/01.png">
				</div>
                <?php echo CHtml::link(Yii::t('userControl', 'Basket'), array('/shop_cart/shoppingCart/view'), array('class' => 'btn btn-success', 'style' => 'width:90px;')) ?>
            </div>
			<div class="span3" id="met">
				<div>
					<img src="/images/theme/02.png">
				</div>
                <?php echo CHtml::link(Yii::t('userControl', 'Orders'), array('/shop_cart/orders/index'), array('class' => 'btn btn-success', 'style' => 'width:90px; ')) ?>
            </div>
			<div class="span3" id="met">
				<div>
					<img src="/images/theme/03.png">
				</div>
                <?php echo CHtml::link(Yii::t('userControl', 'Finance'), array('/userControl/userBalance/index'), array('class' => 'btn btn-success', 'style' => '')) ?>
            </div>
			<div class="span3" id="met">
				<div>
					<img src="/images/theme/04.png">
				</div>
                <?php echo CHtml::link(Yii::t('userControl', 'Garage'), array('/userControl/usersCars/index'), array('class' => 'btn btn-success', 'style' => 'width:90px; ')) ?>
            </div>
		</div>
	</div>

	<div class="span6">
		<div class="usinfo">
			<h5> <?php echo Yii::t('userControl', 'You') ?>: <?php echo $model->fullName ?></h5>
            <?php echo Yii::t('userControl', 'Contact the store') ?>: <br>
            <?php echo Yii::t('userControl', 'tel.') ?> <span><?php echo Yii::app()->config->get('Site.Phone') ?></span><br>
            <?php echo Yii::t('userControl', 'e-mail') ?>: <span><a
				href="mailto:<?php echo Yii::app()->config->get('Site.AdminEmail') ?>"><?php echo Yii::app()->config->get('Site.AdminEmail') ?></a></span><br>
            <?php
            $temp = Yii::app()->config->get('Site.SiteICQ');
            if (! empty($temp)) {
                ?>
                    <?php echo Yii::t('userControl', 'ICQ') ?>: <span><?php echo Yii::app()->config->get('Site.SiteICQ') ?></span>
                <?php } ?>
                <?php
                $temp = Yii::app()->config->get('Site.SiteICQ');
                if (! empty($temp)) {
                    ?>
                <?php echo Yii::t('userControl', 'Skype') ?>: <span><?php echo Yii::app()->config->get('Site.SiteSkype') ?></span>
            <?php } ?>
        </div>
		<div class="bal"><?php echo Yii::t('userControl', 'Ways of replenishment of balance') ?></div>
		<div class="kont">
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/001.png"></a>
			</div>
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/002.png"></a>
			</div>
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/003.png"></a>
			</div>
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/004.png"></a>
			</div>
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/005.png"></a>
			</div>
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/006.png"></a>
			</div>
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/007.png"></a>
			</div>

			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/008.png"></a>
			</div>
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/009.png"></a>
			</div>
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/010.png"></a>
			</div>
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/011.png"></a>
			</div>
			<div>
				<a target="_blank"
					href="/kupi-v-kredit"><img
					src="/images/theme/coun/credit.jpg"></a>
			</div>
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/013.png"></a>
			</div>
			<div>
				<a target="_blank"
					href="<?php echo Yii::app()->createUrl('/webPayments/webPayments/pay') ?>"><img
					src="/images/theme/coun/014.png"></a>
			</div>
		</div>
	</div>
</div>
