<div class="item">
    <h2><?= CHtml::link($data->short_title, array('', 'id' => $data->id)) ?> <?= $data->years ?></h2>
    <div class="pic">
        <?php
        $image = $data->getAttachment();
        if (!empty($image))
            echo CHtml::image('/' . $image, $data->short_title, array('style' => 'wifth:210px;'));
        ?>
        <div class="txt"><?= $data->short_text ?></div>
        <?= CHtml::link('<button class="btn btn-more" onfocus="this.blur();">' . Yii::t('katalogVavto', 'More') . '...</button>', array('', 'id' => $data->id)) ?>
    </div>	
</div>