<?php
/* @var $this DefaultController */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Used catalog');
$this->breadcrumbs=array(
    $this->pageTitle,
);
?>
<div id="content">
    <h1><?php echo $this->pageTitle;?></h1>

    <div class="panel">
        <div class="row-fluid">
            <div class="span12">
                <?php $this->widget('zii.widgets.CListView', array(
                    'id'=>'brand-list-view',
                    'dataProvider'=>$model,
                    'itemView'=>'_view',
                    'summaryText'=>false,
                    'itemsCssClass'=>'row-fluid clear',
                )); ?>

            </div>
        </div>
    </div>

</div>
<div class="clearfix"></div>