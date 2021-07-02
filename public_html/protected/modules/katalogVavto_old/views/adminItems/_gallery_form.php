<?php

if ($model->galleryBehavior->getGallery() === null) {
    echo '<p>' . Yii::t('katalogVavto', 'To add images, you should save items.') . '</p>';
} else {
    $this->widget('GalleryManager', array(
        'gallery' => $model->galleryBehavior->getGallery(),
        'controllerRoute' => '/gallery'
    ));
}
?>