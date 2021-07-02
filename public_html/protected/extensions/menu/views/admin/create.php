<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('menu', 'Menu') => array('admin'), Yii::t('menu', 'Create a menu item')));

$this->pageTitle = Yii::t('menu', 'Create a menu item');

$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Menu'),
        'url' => array('/menu/admin/admin'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Page – Side'),
        'url' => array('/pages_left/admin/index'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Page – Top'),
        'url' => array('/pages_top/admin/index'),
        'active' => false,
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

<h1><?= Yii::t('menu', 'Create a menu item') ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>