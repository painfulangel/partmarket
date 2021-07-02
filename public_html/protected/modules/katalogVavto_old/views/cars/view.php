<?php
//$this->pageTitle = $model->title;
//$this->metaTitle = $model->meta_title;
//$this->metaDescription = $model->meta_description;
//$this->metaKeywords = $model->meta_keywords;


$this->pageTitle = $model->title;
$this->metaTitle = $model->meta_title;
$this->metaDescription = $model->meta_description;
$this->metaKeywords = $model->meta_keywords;
$this->breadcrumbs = $model->breadcrumbs;
?>



<div class = "b-model-about">
    <h1>
        <?= $model->title ?>
    </h1>
    <div class = "model-desc">
        <?php
        if (!empty($model->image)) {
            echo '<div class="pic">';
            echo CHtml::image('/' . $model->getAttachment(), $model->short_text
                    , array('width' => '185px')
            );
            echo '</div>';
        }
        ?>

        <div class="txt">
            <?= $model->text ?>
        </div>
        <div class="models-staff">
            <ul>
                <?php
                $childs = KatalogVavtoItems::model()->getCarPartTypes();
                foreach ($childs as $key => $child) {
                    $type = Yii::app()->request->getParam('type', '');
                    ?>
                    <li class="<?= $key ?>"><?= CHtml::link(($type == $key ? '<b>' : '') . $child . ($type == $key ? '</b>' : ''), '?type=' . $key) ?></li>
                    <?php
                }
                ?>
            </ul>
        </div>


    </div>
</div>
<br>
<br>
<?php
$model2 = new KatalogVavtoItems('search');
$model2->unsetAttributes();
if (isset($_POST['KatalogVavtoItems'])) {
    $model2->attributes = $_POST['KatalogVavtoItems'];
}
$model2->cathegory_id = $model->id;
$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $model2->getItemsDataProvider(),
    'itemView' => '_view_posiotion',
    'template' => '{items} {pager}',
    'ajaxUpdate' => false,
    'id' => 'katalog-vavto',
    'htmlOptions' => array('class' => 'models-list'),
));
?>
   
