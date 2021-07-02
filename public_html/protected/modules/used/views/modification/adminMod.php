<?php
/**
 *
 * @var ModificationController $this
 * @var \UsedMod $model
 * @var [#M#M#C\UsedModels.model.findByPk, array] $models
 */
$this->pageTitle = $models->name;//Yii::t(UsedModule::TRANSLATE_PATH, 'Manage Used Modifications');
$this->breadcrumbs=array(
    Yii::t(UsedModule::TRANSLATE_PATH, 'Used catalog') => array('/used/admin'),
    $models->brand->name=>array('/used/models/adminModels', 'UsedModels[brand_id]'=>$models->brand->id),
    $models->name,
);
?>
<div class="container">

    <h1><?php echo $this->pageTitle;?></h1>

    <div class="row">
        <div class="span6">
            <?php $this->widget('zii.widgets.CListView', array(
                'id'=>'model-list-view',
                'dataProvider'=>$model->search(),
                'itemView'=>'_view',
            )); ?>
        </div>
        <div class="span7">
            <div class="btn-toolbar">
                <?php echo CHtml::ajaxLink(
                    Yii::t(UsedModule::TRANSLATE_PATH, 'Add modification'),
                    array('createMain', 'model_id'=>$model->model_id),
                    array(
                        'update'=>'#load-form',
                    ),
                    array(
                        'class' => 'btn btn-success',
                    ));?>
            </div>
            <div class="" id="load-form"></div>
        </div>
    </div>
    <div style="height: 60px;"></div>
</div>
