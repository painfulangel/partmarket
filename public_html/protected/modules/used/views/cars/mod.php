<?php
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Used catalog').' - '.$mod->brand->name.' - '.$mod->name;
$this->breadcrumbs=array(
    Yii::t(UsedModule::TRANSLATE_PATH, 'Used catalog') => array('/used/cars'),
    $mod->brand->name => array('/used/cars/brand', 'brand'=>$mod->brand->slug),
    $mod->name,
);
?>

<div id="content">
    <h1><?php echo $this->pageTitle;?></h1>

    <div class="panel">
        <div class="row-fluid">
            <div class="span12">
                <?php $this->widget('zii.widgets.CListView', array(
                    'id'=>'model-list-view',
                    'dataProvider'=>$dataProvider,
                    'itemView'=>'_mods_view',
                    'summaryText'=>false,
                    'itemsCssClass'=>'row-fluid clear',
                    'viewData'=>array('model'=>$mod),
                )); ?>

            </div>
        </div>
    </div>

</div>
<div class="clearfix"></div>

