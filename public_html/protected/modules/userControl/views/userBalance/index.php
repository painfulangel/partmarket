<?php
$this->breadcrumbs = array(
    Yii::t('userControl', 'Finance'),
);
$this->pageTitle = Yii::t('userControl', 'Finance');
?>

<?php
$this->renderPartial('userControl.views.userProfile._cabinet_top', array('title' => Yii::t('userControl', 'Finance')));
?>

<div id="tranz">
    <h3><?= Yii::t('userControl', 'Last operations') ?></h3>

    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'id' => 'user-balance-operations-grid',
        'dataProvider' => $model->search(),
        'pagerCssClass' => 'pagination pagination-centered',
        'columns' => array(
            array(
                'class' => 'bootstrap.widgets.TbDataColumn',
                'name' => 'create_time',
                'type' => 'raw',
                'value' => 'date(\'d.m.Y H:i:s\',$data->create_time)',
                'headerHtmlOptions' => array('style' => 'text-align: center;'),
                'htmlOptions' => array(
                    'style' => 'text-align: center;',
                    'aria-label'=>$model->getAttributeLabel("create_time")
                ),
            ),
            array(
                'class' => 'bootstrap.widgets.TbDataColumn',
                'name' => 'value',
                'type' => 'raw',
                'value' => '($data->value>0?\'<img src="/images/theme/plus.png">\':\'<img src="/images/theme/minus.png">\')',
                'headerHtmlOptions' => array('style' => 'text-align: center;'),
                'htmlOptions' => array(
                    'style' => 'text-align: center;',
                    'aria-label'=>$model->getAttributeLabel("value")
                ),
            ),
            array(
                'class' => 'bootstrap.widgets.TbDataColumn',
                'name' => 'value',
                'type' => 'raw',
                'value' => 'Yii::app()->getModule(\'currencies\')->getFormatPrice($data->value)',
                'headerHtmlOptions' => array('style' => 'text-align: center;'),
                'htmlOptions' => array(
                    'style' => 'text-align: center;',
                    'aria-label'=>$model->getAttributeLabel("value")
                ),
            ),
            array(
                'class' => 'bootstrap.widgets.TbDataColumn',
                'name' => 'balance',
                'type' => 'raw',
                'value' => 'Yii::app()->getModule(\'currencies\')->getFormatPrice($data->balance)',
                'headerHtmlOptions' => array('style' => 'text-align: center;'),
                'htmlOptions' => array(
                    'style' => 'text-align: center;',
                    'aria-label'=>$model->getAttributeLabel("balance")
                ),
            ),
            array(
                'class' => 'bootstrap.widgets.TbDataColumn',
                'name' => 'comment',
                'headerHtmlOptions' => array('style' => 'text-align: center;'),
                'htmlOptions' => array(
                    'style' => 'text-align: center;',
                    'aria-label'=>$model->getAttributeLabel("comment")
                ),
            ),
        ),
    ));
    ?>
</div>