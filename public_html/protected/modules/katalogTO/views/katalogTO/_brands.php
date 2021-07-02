<?php ?>

<div class="katalog-to_div">
    <a href="<?= Yii::app()->createUrl('/katalogTO/katalogTO/models', array('id' => $data->id)) ?>">
        <?php
        echo (!empty($data->img) ? CHtml::image("/images/KatalogTO/logos/$data->img", $data->title, array("style" => "max-width: 50px;max-height: 50px;")) : "");
        ?>

        <h3><?= $data->name ?></h3>
    </a>
</div>