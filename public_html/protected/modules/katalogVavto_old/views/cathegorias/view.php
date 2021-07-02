<?php
if ($child_model == NULL) {
    $this->pageTitle = $model->title;
    $this->metaTitle = $model->meta_title;
    $this->metaDescription = $model->meta_description;
    $this->metaKeywords = $model->meta_keywords;
    $this->breadcrumbs = $model->breadcrumbs;
} else {
    $this->pageTitle = $child_model->title;
    $this->metaTitle = $child_model->meta_title;
    $this->metaDescription = $child_model->meta_description;
    $this->metaKeywords = $child_model->meta_keywords;
    $this->breadcrumbs = $child_model->breadcrumbs;
}
?>

<?php
if ($model->level == 1 || $model->level == 2) {
    ?>
    <div class="b-brend-about">
        <h2>
            <?= Yii::t('katalogVavto', 'Models') ?>
        </h2>
        <div class="brend-desc">
            <?php
            if (!empty($model->image)) {
                echo '<div class="pic">';
                echo CHtml::image('/' . $model->getAttachment(), $model->short_title
                        , array('width' => '185px')
                );
                echo '</div>';
            }
            ?>

            <div class="txt">
                <h2><?= $model->title ?></h2>
                <?= $model->text ?>
                <ul>
                    <?php
                    $childs = $model->children()->findAll('active_state=1 AND (SELECT COUNT(*) FROM `' . $model->tableName() . '` `tch` WHERE `tch`.parent_id=t.id LIMIT 1)>0');
                    $childs_id = array();
                    foreach ($childs as $child) {
                        if ($child_model == NULL || ($child_model != NULL && $child_model->id == $child->id))
                            $childs_id[] = $child->id;
                        ?>
                        <li class="<?= $child->sub_image_class ?>"><?= CHtml::link(($child_model != NULL && $child_model->id == $child->id ? '<b>' : '') . $child->short_title . ($child_model != NULL && $child_model->id == $child->id ? '</b>' : ''), array('', 'id' => $child->id)) ?></li>
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
//print_r();
    $this->widget('bootstrap.widgets.TbListView', array(
        'dataProvider' => $model->getAllChilds($childs_id),
        'itemView' => '_view_car',
        'template' => '{items} {pager}',
        'id' => 'katalog-vavto',
        'htmlOptions' => array('class' => 'brends-list'),
    ));
    ?>

    <?php
} else if ($model->level == 3 || $model->level == 4) {
    ?>
    <div class = "b-model-about">
        <h2> <?= Yii::t('katalogVavto', 'Parts') ?>
        </h2>
        <div class = "model-desc">
            <?php
            if (!empty($model->image)) {
                echo '<div class="pic">';
                echo CHtml::image('/' . $model->getAttachment(), $model->short_title
                        , array('width' => '185px')
                );
                echo '</div>';
            }
            ?>
            <div class="txt">
                <h2><?= $model->title ?></h2>
                <?= $model->text ?>
            </div>
            <div class="models-staff">
                <ul>
                    <?php
                    $childs = $model->children()->findAll('active_state=1 ');
                    $childs_id = array();
                    foreach ($childs as $child) {
                        if ($child_model == NULL || ($child_model != NULL && $child_model->id == $child->id))
                            $childs_id[] = $child->id;
                        ?>
                        <li class="<?= $child->sub_image_class ?>"><?= CHtml::link(($child_model != NULL && $child_model->id == $child->id ? '<b>' : '') . $child->short_title . ($child_model != NULL && $child_model->id == $child->id ? '</b>' : ''), array('', 'id' => $child->id)) ?></li>
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
    $this->widget('bootstrap.widgets.TbListView', array(
        'dataProvider' => $model->getItemsDataProvider(),
        'itemView' => '_view_posiotion',
        'template' => '{items} {pager}',
        'id' => 'katalog-vavto',
        'htmlOptions' => array('class' => 'models-list'),
    ));
    ?>
    <?php
}
?>

