<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>
<h1>Выбор марки автомобиля</h1>
<?= Yii::app()->config->get('KatalogVavto.TextBefore') ?>
<div id="model_list">
    <ul class="columns_list">
        <?php
        $images = array(
            'kia' => 'icon7.png',
            'hnd' => 'icon29.png',
            'cit' => 'icon27.png',
            'peug' => 'icon21.png',
            'ren' => 'icon22.png',
            'toy' => 'icon10.png',
            'lex' => 'icon31.png',
            'nis' => 'icon25.png',
//            'inf' => 'icon.png',
            'suz' => 'icon11.png',
            'maz' => 'icon19.png',
        );
        $datas = KatalogVavtoBrands::model()->findAllByAttributes(array('active_state' => '1'));
        foreach ($datas as $data) {
            if (!isset($images[$data->menu_image]))
                continue;
            ?>
            <li>
                <?php echo (isset($images[$data->menu_image]) ? CHtml::image('/images/img/' . $images[$data->menu_image]) : '') . CHtml::link($data->short_title, array('/katalogVavto/brands/view', 'id' => $data->id)) ?>
            </li>
        <?php } ?>
    </ul>
</div>
<?= Yii::app()->config->get('KatalogVavto.TextAfter') ?>
