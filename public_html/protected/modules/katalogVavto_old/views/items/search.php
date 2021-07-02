<?php

$this->pageTitle = Yii::t('katalogVavto', 'Search spare parts');
$this->metaTitle = Yii::t('katalogVavto', 'Search spare parts');
$this->metaDescription = '';
$this->metaKeywords = '';
$this->breadcrumbs = array(
    Yii::t('katalogVavto', 'Search spare parts'),
);
?>

<?php

$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $model->search(),
    'itemView' => '../cars/_view_posiotion',
    'template' => '{items} {pager}',
    'ajaxUpdate' => false,
    'id' => 'katalog-vavto',
    'htmlOptions' => array('class' => 'models-list'),
));
?>
   
