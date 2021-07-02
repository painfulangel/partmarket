<div class="item">
    <h2><?= CHtml::link($data->short_title, array('/katalogVavto/cars/view', 'id' => $data->id)) ?> <?= $data->years ?></h2>
    <div class="pic">
        <?php
        $image = $data->getImageIndex();
        if (!empty($image))
            echo CHtml::image('/' . $image, $data->short_text, array('style' => 'wifth:210px;'));
        ?>
        <div class="txt"><?= $data->short_text ?></div>
        <?= CHtml::link('<button class="btn btn-primary" ">'.Yii::t('katalogVavto', 'More').'...</button>', array('/katalogVavto/cars/view', 'id' => $data->id)) ?>
    </div>	
</div>
