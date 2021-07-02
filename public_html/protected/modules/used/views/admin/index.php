<?php
/* @var $this DefaultController */
$this->pageTitle = Yii::t(UsedModule::TRANSLATE_PATH, 'Used catalog');
$this->breadcrumbs=array(
    $this->pageTitle,
);
?>
<div class="container">
    <h1><?php echo $this->pageTitle;?></h1>

    <div class="row">
        <div class="span6">
            <?php $this->widget('zii.widgets.CListView', array(
                'id'=>'brand-list-view',
                'dataProvider'=>$model,
                'itemView'=>'_view',
            )); ?>

        </div>
        <div class="span7">
            <div class="btn-toolbar">
                <?php echo CHtml::ajaxLink(
                    Yii::t(UsedModule::TRANSLATE_PATH, 'Create'),
                    array('/used/brands/createMain'),
                    array(
                        'update'=>'#load-form',
                    ),
                    array(
                        'class' => 'btn btn-success',
                    ));?>
            </div>
            <div class="" id="load-form">

            </div>
        </div>
    </div>

    <div style="height: 60px;"></div>

</div>
<div class="clearfix"></div>