<?php
/**
 *
 * @var ModificationController $this
 * @var \UsedMod $model
 */
Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/../css/'). '/used.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/../js/'). '/jquery.mark.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/../js/'). '/used.js', CClientScript::POS_END);
$this->pageTitle = $model->brand->name.'/'.$model->model->name.'/'.$model->name;
$this->breadcrumbs=array(
    Yii::t(UsedModule::TRANSLATE_PATH, 'Used Brands') => array('/used/admin'),
    $model->brand->name => array('/used/models/adminModels', 'UsedModels[brand_id]'=>$model->brand->id),
    $model->model->name => array('/used/modification/adminMod', 'UsedMod[model_id]'=>$model->model_id),
    $model->name,
);
?>
<div class="container">
    <div style="height: 20px;"></div>
    <div class="row">
        <div class="span4">
            <img src="<?php echo $model->getImageUrl()?>" alt="<?php echo $model->name;?>" width="162">
        </div>
        <div class="span8">
            <h2 class="head-mod"><?php echo $this->pageTitle;?></h2>
            <br>
            <input type="hidden" id="node" name="node" value="">
            <input type="hidden" id="unit" name="unit" value="">
            <?php echo CHtml::ajaxLink(
                $text = 'Добавить деталь',
                $url = '/used/items/createAjax/mid/'.$model->id,
                $ajaxOptions=array (
                    'type'=>'GET',
                    'dataType'=>'html',
                    'data' => 'js:$("#node, #unit").serialize()',
                    'success'=>'function(html){ jQuery("#unit-form").html(html);jQuery("#items-list-view").thml(""); }'
                ),
                $htmlOptions=array (
                    'class'=>'btn btn-success',
                    'id'=>'add-item'
                )
            );?>
        </div>
    </div>

    <div class="clearfix" style="height: 10px;"></div>

    <div class="row">
        <div class="span4">
            <div class="input-append">
                <input id="csrf-tok-a" type="hidden" value="<?php echo Yii::app()->request->csrfToken; ?>" name="YII_CSRF_TOKEN">
                <input
                    id="quick-search-group-a"
                    class="form-control"
                    type="text"
                    data-url-search="/used/admin/searchItems"
                    data-url="/auto/cars/acura/cl/cl_1996-2003/"
                    data-mid="<?php echo $model->id?>"
                    placeholder="Поиск по артикулу, наименованию">

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
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="span4">
            <div class="input-append">
                <input id="csrf-tok" type="hidden" value="<?php echo Yii::app()->request->csrfToken; ?>" name="YII_CSRF_TOKEN">
                <input id="quick-search-group" class="form-control" type="text" data-url-search="/used/admin/searchNodes" data-url="/auto/cars/acura/cl/cl_1996-2003/" data-mid="1096" placeholder="быстрый поиск по каталогу">
                <span class="input-group-btn">
                <button class="add-on btn btn-default btn-quick-search-action" disabled="disabled" type="button">
                    <span id="quick_search_icon_search" class="icon-search"></span>
                    <span id="quick_search_icon_remove" class="hidden icon-remove"></span>
                </button>
                </span>
                <div class="parts-left__hidden hidden"></div>
            </div>
            <div class="add-control-unit" style="display: none;">
                <?php echo CHtml::ajaxLink(
                    $text = 'Добавить агрегат',
                    $url = '/used/units/createAjax',
                    $ajaxOptions=array (
                        'type'=>'GET',
                        'dataType'=>'html',
                        'success'=>'function(html){ jQuery("#unit-form").html(html); }'
                    ),
                    $htmlOptions=array ('class'=>'btn btn-success')
                );?>
                <!--<a href="/used/units/createAjax" class="btn btn-success">Добавить агрегат</a>-->
            </div>
            <?php //echo CVarDumper::dump(UsedNodes::model()->generateTree($model),10,true);?>
            <div class="tree-nodes">
                <?php $this->widget('CTreeView', array(
                    'id' => 'category-treeview',
                    'data' => UsedNodes::model()->generateTree($model),
                    'collapsed' => true,
                    'htmlOptions' => array(
                        'class' => 'treeview-famfamfam1',
                    ),
                ));
                ?>
            </div>
        </div>
        <div class="span8">
            <div id="unit-form"></div>
            <div id="items-list-view"></div>
        </div>
    </div>

</div>
