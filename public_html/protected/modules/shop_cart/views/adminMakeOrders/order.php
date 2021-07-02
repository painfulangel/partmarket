<?php
$this->pageTitle = Yii::t('shop_cart', 'Checkout');
$this->breadcrumbs = array(
    Yii::t('shop_cart', 'Basket') => array('view'),
    Yii::t('shop_cart', 'Checkout'),
);
?>
<h1><?= Yii::t('shop_cart', 'Checkout') ?></h1>

<?php echo $this->renderPartial('_view_order', array('model' => $model)); ?>


<div class="form-actions">
    <?= CHtml::link(Yii::t('shop_cart', 'To enter and checkout (bought here)'), array('order', 'step' => 'pre-login'), array('class' => 'btn btn-primary')) ?> 
    <?= CHtml::link(Yii::t('shop_cart', 'Express checkout (previously bought here)'), '#', array('class' => 'btn btn-primary', 'onclick' => '$(\'#fast_regs_id\').show();')) ?>
</div>


<div id="fast_regs_id" style="display: none;">
    <?php echo $this->renderPartial('_fast_registration', array('model' => new LRegisterForm, 'model_profile' => new UserProfile)); ?>
</div>

