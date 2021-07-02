<?php

if ($model->galleryBehavior->getGallery() === null) {
    echo '<p>' . Yii::t('katalogAccessories', 'To add images you must save the product.') . '</p>';
} else {
    $this->widget('GalleryManager', array(
        'gallery' => $model->galleryBehavior->getGallery(),
        'controllerRoute' => '/gallery'
    ));
}
?>