
<h1><?= $model->title ?></h1>
<div>
    <?= $model->text ?>
</div>
<?php
$childs = $model->children()->findAll('active_state=1 AND (SELECT COUNT(*) FROM `' . $model->tableName() . '` `tch` WHERE `tch`.parent_id=t.id LIMIT 1)>0');

if (count($childs) > 0) {
    ?>
    <div class="span6">
        <?php
        $dataProvider = new CArrayDataProvider('User');
        $dataProvider->setData($childs);
        $this->renderPartial('_index', array('dataProvider' => $dataProvider));
        ?>
    </div>
    <div>
        <?= $model->text ?>
    </div>
    <div class="span3">

    </div>

    <?php
} else {
    ?>
    <div class="view">
        <div class="span2 pull-left">
            <div>
                <b>     
                    <?= KatalogVavtoItems::model()->getAttributeLabel('article') . ':' ?>
                </b>
            </div>
        </div>
        <div class="span3 pull-left">
            <div>
                <b>     
                    <?= KatalogVavtoItems::model()->getAttributeLabel('title') . ':' ?>

                </b>
            </div>
        </div>
        <div class="span2 pull-left">
            <b>
                <?= KatalogVavtoItems::model()->getAttributeLabel('price') . ':' ?>

            </b>
        </div>
        <div class="clear"></div>
    </div>
    <?php
//print_r();
    $this->widget('bootstrap.widgets.TbListView', array(
        'dataProvider' => $model->getItemsDataProvider(),
        'itemView' => '../items/_view',
        'template' => '{items} {pager}',
        'id' => 'katalog-vavto',
    ));
}
?>
