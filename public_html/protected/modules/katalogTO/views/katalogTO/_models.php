<?php ?>

<div class="katalog-to_div">
    <a href="<?= Yii::app()->createUrl('/katalogTO/katalogTO/types', array('id' => $data->id)) ?>">
        <?php
        echo (!empty($data->img) ? CHtml::image("/images/KatalogTO/cars/$data->img", $data->title, array("style" => "max-width: 100px;max-height: 100px;")) : "");
        ?>

        <h3><?= $data->name ?></h3>
        <p><?= $data->content ?></p>
    </a>
</div>