<?php
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Used catalog').' - '.$model->name;
$this->breadcrumbs=array(
    Yii::t(UsedModule::TRANSLATE_PATH, 'Used catalog') => array('/used/cars'),
    $model->name,
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
                    'itemView'=>'_models_view',
                    'summaryText'=>false,
                    'itemsCssClass'=>'row-fluid clear',
                    'viewData'=>array('model'=>$model),
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
                )); ?>

            </div>
        </div>
    </div>

</div>
<div class="clearfix"></div>

