<?php
$this->breadcrumbs = array(
    Yii::t('userControl', 'Settings'),
);
$this->pageTitle = Yii::t('userControl', 'Settings');
?>
<?php
$this->renderPartial('userControl.views.userProfile._cabinet_top', array('title' => Yii::t('userControl', 'Settings')));
?>
<div class="pers-cab-wrap">
    <?php
    $this->widget('bootstrap.widgets.TbTabs', array(
        'type' => 'tabs',
        'tabs' => array(
            array(
                'label' => Yii::t('userControl', 'Profile'),
                'content' => $this->renderPartial('update', array('model' => $model), true),
                'active' => true
            ),
            array(
                'label' => Yii::t('userControl', 'Edit your password'),
                'content' => $this->renderPartial('edit_pass', array('model' => $model), true),
            ),
        ),
    ))
    ?>

</div>
<?php
Yii::app()->clientScript->registerScript('cabinet_pass', "
$( document ).ready(function() {
        $('#edit_pass_load').load('/lily/account/edit?ajax=true');
});
");
?>
<?php
$this->breadcrumbs = array(
    Yii::t('userControl', 'Settings'),
);
$this->pageTitle = Yii::t('userControl', 'Settings');
?>
<br/>
<br/>
<br/>
