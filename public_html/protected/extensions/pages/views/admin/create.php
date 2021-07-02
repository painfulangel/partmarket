<?php

$this->pageTitle = Yii::t('pages', 'Creating a page');

$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('pages', 'Static pages') => array('index'),
            $this->pageTitle,));
$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Menu'),
        'url' => array('/menu/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Page – Side'),
        'url' => array('/pages_left/admin/index'),
        'active' => Yii::app()->controller->module->position == '_left' ? true : false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Page – Top'),
        'url' => array('/pages_top/admin/index'),
        'active' => Yii::app()->controller->module->position == '_top' ? true : false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'News'),
        'url' => array('/news/admin/admin'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Clients reviews'),
        'url' => array('/requests/adminFeedbacks/admin'),
        'active' => false,
    ),
);
?>

<?= $this->renderPartial('_form', array('model' => $model)) ?>