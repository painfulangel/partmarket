<?php
    //$this->pageTitle = $model->title;
    //$this->metaTitle = $model->meta_title;
    //$this->metaDescription = $model->meta_description;
    //$this->metaKeywords = $model->meta_keywords;

    $this->pageTitle = $model->title;

    $this->metaTitle = ($subtype ? $subtype_ru.' '.$model->title : ($type_ru ? $type_ru.' '.$model->title : $model->meta_title));
    $this->metaDescription = ($subtype ? $subtype_ru.' '.$model->title : ($type_ru ? $type_ru.' '.$model->title : $model->meta_title));
    $this->metaKeywords = ($subtype ? $subtype_ru.' '.$model->title : ($type_ru ? $type_ru.' '.$model->title : $model->meta_title));

    $breadcrumbs = $model->breadcrumbs;
    
    if ($subtype) {
        $breadcrumbs[$type_ru] = str_replace('subtype-'.$subtype, '', $path);
        $breadcrumbs[] = $subtype_ru;
    } else if ($type) {
        $breadcrumbs[] = $type_ru;
    }

    $this->breadcrumbs = $breadcrumbs;
?>
<div class = "b-model-about">
    <h1><?php echo ($subtype_ru ? $subtype_ru.' ' : ($type_ru ? ' '.$type_ru.' ' : 'Автозапчасти для ')).$model->title; ?></h1>
    <div class = "model-desc">
        <?php
        if (!empty($model->image)) {
            echo '<div class="pic" style="float: left; width: 100px;">';
            echo CHtml::image('/' . $model->getAttachment(), $model->short_text
                    , array('width' => '100px')
            );
            echo '</div>';
        }

        if (!$type || !$subtype) {
        ?>
        <div class="models-staff" style="min-width: 900px;">
            <div class="row-fluid">
<?php
                $i = 0;
                foreach ($categories as $key => $c) {
?>
                    <div class="span2"<?php if ($i == 0) { ?> style="margin-left: 2.564102564102564%;"<?php } ?>><?php echo CHtml::link($c, ($type ? $path.'/subtype-'.$key.'/' : $path.'/type-'.$key.'/')); ?></div>
<?php
                    $i ++;
                }
                /*$childs = KatalogVavtoItems::model()->getCarPartTypes();
                foreach ($childs as $key => $child) {
                    $type = Yii::app()->request->getParam('type', '');
                    ?>
                    <li class="<?= $key ?>"><?= CHtml::link(($type == $key ? '<b>' : '') . $child . ($type == $key ? '</b>' : ''), '?type=' . $key) ?></li>
                    <?php
                }*/
?>
            </div>
        </div><?php } ?>
    </div>
</div>
<br>
<br>
<?php
$start = microtime(true);

$dataProvider = $model2->getItemsDataProvider();

$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view_posiotion',
    'template' => '{items} {pager}',
    'ajaxUpdate' => true,
    'id' => 'katalog-vavto',
    'htmlOptions' => array('class' => 'models-list'),
));

/*$criteria = new CDbCriteria;
$criteria->compare('cathegory_id', $model2->cathegory_id);
$criteria->compare('active_state', 1);
$criteria->select = 'image, short_text, id, title, sub_title, text, article';

$criteria->order = 'id DESC';

$criteria->limit = 10;
$criteria->offset = 0;

$items = KatalogVavtoItems::model()->findAll($criteria);

//echo '<p style="font-weight: bold;">'.(microtime(true) - $start).'</p>';

$count = count($items);

for ($i = 0; $i < $count; $i ++) {
    $data = $items[$i];

    //$this->renderPartial('_view_posiotion', array('data' => $data));
?>
<div class="item">
    <div class="pic">
    <?php if ($data->image) {
        //echo CHtml::link(CHtml::image('/'.$data->getAttachment(), $data->short_text), array('/katalogVavto/items/view', 'id' => $data->id), array());
    } else {
        //echo CHtml::link(CHtml::image('/images/KatalogVavtoItems/4.png', $data->short_text), array('/katalogVavto/items/view', 'id' => $data->id), array());
    }
    ?>
    </div>
    <h2>
        <?php //echo CHtml::link($data->title, array('/katalogVavto/items/view', 'id' => $data->id), array()) ?> <?php echo $data->sub_title ?>
    </h2>
    <div class="txt"><?php echo $data->text ?></div>
    <div class="more"><span><?php echo Yii::t('katalogVavto', 'Article') ?>: <?php echo $data->article ?> </span><?php echo CHtml::link(Yii::t('katalogVavto', 'All offers'), (Yii::app()->config->get('Site.SearchType') == 1 ? array('/detailSearchNew/default/search', 'article' => $data->article) : array('/detailSearch/default/search', 'search_phrase' => $data->article)), array('class' => 'btn  btn-more')) ?></div>
</div>
<?php
}*/

//echo '<p style="font-weight: bold;">'.(microtime(true) - $start).'</p>';

if ($type == '' && $subtype == '') {
?>
<div class="txt">
    <?php echo $model->text; ?>
</div>   
<?php } ?>