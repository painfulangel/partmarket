<?php
/**
 *
 * @var ModificationController $this
 * @var \UsedMod $model
 */
Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/../css/'). '/front.used.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/../css/'). '/used.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/../js/'). '/jquery.mark.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/../js/'). '/used.js', CClientScript::POS_END);
$this->pageTitle = $model->brand->name.'/'.$model->model->name.'/'.$model->name;
$this->breadcrumbs=array(
    Yii::t(UsedModule::TRANSLATE_PATH, 'Used Brands') => array('/used/cars'),
    $model->brand->name => array('/used/cars/brand', 'brand'=>$model->brand->slug),
    $model->model->name => array('/used/cars/mod', 'brand'=>$model->brand->slug, 'model'=>$model->model->slug),
    $model->name,
);
?>
<div id="content"">

    <div class="row-fluid">
        <div class="span4">
            <div class="panel-body side-cars-front">
                <img src="<?php echo $model->getImageUrl()?>" alt="<?php echo $model->name;?>" class="img-responsive">

                <div class="input-append" style="margin-top: 15px;">
                    <input id="csrf-tok-a" type="hidden" value="<?php echo Yii::app()->request->csrfToken; ?>" name="YII_CSRF_TOKEN">
                    <input
                        id="quick-search-group-a"
                        class="form-control"
                        type="text"
                        data-url-search="/used/cars/searchItems"
                        data-url="/auto/cars/acura/cl/cl_1996-2003/"
                        data-mid="<?php echo $model->id?>"
                        placeholder="<?php echo Yii::t('used', 'Search by article number, name')?>" title="<?php echo Yii::t('used', 'Search by article number, name')?>">

                        <span class="input-group-btn">
                        <button class="add-on btn btn-default btn-quick-search-action" disabled="disabled" type="button">
                            <!-- button search -->
                            <span id="quick_search_icon_search" class="icon-search"></span>
                            <!-- button flush search -->
                            <span id="quick_search_icon_remove" class="hidden icon-remove"></span>
                        </button>
                        </span>
                    <div class="parts-left__hidden hidden">

                    </div>
                </div>

                <div class="input-append">
                    <input id="csrf-tok" type="hidden" value="<?php echo Yii::app()->request->csrfToken; ?>" name="YII_CSRF_TOKEN">
                    <input id="quick-search-group" class="form-control" type="text" data-url-search="/used/admin/searchNodes" data-url="/auto/cars/acura/cl/cl_1996-2003/" data-mid="1096" placeholder="<?php echo Yii::t('used', 'Quick catalog search')?>" title="<?php echo Yii::t('used', 'Quick catalog search')?>">
                    <span class="input-group-btn">
                    <button class="add-on btn btn-default btn-quick-search-action" disabled="disabled" type="button">
                        <span id="quick_search_icon_search" class="icon-search"></span>
                        <span id="quick_search_icon_remove" class="hidden icon-remove"></span>
                    </button>
                    </span>
                    <div class="parts-left__hidden hidden"></div>
                </div>

                <?php
                /**
                 * @todo Сделать сюда новое дерево, там где показывает только те детали категории, где есть детали
                 */
                ?>
                <div class="tree-nodes">
                    <?php $this->widget('CTreeView', array(
                        'id' => 'category-treeview',
                        'data' => UsedNodes::model()->generateFrontTreeTest($model),//UsedNodes::model()->generateFrontTree($model),
                        'collapsed' => true,
                        'htmlOptions' => array(
                            'class' => 'treeview-famfamfam1',
                        ),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="span8">
            <?php //echo CVarDumper::dump($tmp,10,true);?>
            <h1><?php echo $this->pageTitle;?></h1>
            <!--<div id="items-list-view">-->
                <?php $this->widget('zii.widgets.CListView', array(
                    'id'=>'items-list-view',
                    'dataProvider'=>$dataProvider,
                    'itemView'=>'_item_view',
                    'summaryText'=>false,
                    'itemsCssClass'=>'items',
                    'pagerCssClass'=>'pagination',
                    'pager'=>array(
                        'header'=>false,
                        'footer'=>false,
                        'firstPageLabel'=>false,
                        'lastPageLabel'=>false,
                        'hiddenPageCssClass'=>'bot-hidden',
                        'nextPageLabel'=>'&raquo;',
                        'prevPageLabel'=>'&laquo;',
                        'htmlOptions'=>array('class'=>'custom-yii-list-view')
                    ),
                    //'viewData'=>array('model'=>$mod),
                )); ?>
            <!--</div>-->
        </div>
    </div>

    <div class="clearfix" style="height: 10px;"></div>

</div>

