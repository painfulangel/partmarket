<?php
$this->breadcrumbs = array(
    Yii::t('site_layout', 'Site Map'),
);

$this->pageTitle = Yii::t('site_layout', 'Site Map');
?>

<h1><?= Yii::t('site_layout', 'Site Map') ?></h1>

<ul>
    <?php
    foreach ($data as $key => $value) {
        echo '<li><a href="' . $key . '">' . $value . '</a></li>';
    }
    ?>

</ul>