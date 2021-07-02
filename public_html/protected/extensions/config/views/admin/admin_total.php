<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('config', 'Site configuration')));

$this->pageTitle = Yii::t('config', 'Site configuration');
$groups = Config::getGroupList();
$this->admin_header = array(
    array(
        'name' => Yii::t('admin_layout', 'Site settings'),
        'url' => array('/config/admin/adminTotal'),
        'active' => true,
    ),
    array(
        'name' => Yii::t('config', 'Help'),
        'url' => array('/cronLogs/help'),
        'active' => false,
    ),
    array(
        'name' => Yii::t('admin_layout', 'Logs'),
        'url' => array('/cronLogs/admin'),
        'active' => false,
    ),
     array(
        'name' =>Yii::t('admin_layout', 'Translation settings'),
        'url' => array('/adminLanguages/admin/'),
        'active' => false,
    ),
);
?>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'config-form',
    'enableAjaxValidation' => false,
        ));

$tabs = array(
    array(
        'label' => Yii::t('languages', 'Basic'),
        'content' => $this->renderPartial('_admin_total', array('type'=>$type,'form' => $form, 'data' => $data, 'groups' => $groups, 'lang' => NULL), true),
        'active' => true
    ),
);
foreach (Config::model()->langsList() as $row) {
    $tabs[] = array(
        'label' => $row['name'],
        'content' => $this->renderPartial('_admin_total', array('type'=>$type,'form' => $form, 'data' => Config::model()->getTranslatedModel($row['link_name'], true)->getTranslatedData(), 'lang' => $row, 'groups' => $groups), true),
    );
}

$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs',
    'tabs' => $tabs,
))
?>
<style>
    .config-group .config-group-element{
        border: 2px solid #cfcfcf;
        padding: 3px;

    }

    .config-group .bg{
        background-color: #e8f6ff;
    } 
    .config-group .element-label{
        /*float: left;*/
        width:170px;
        padding-right: 10px;
        vertical-align: middle;
        display:table-cell;
    }  
    .config-group .element-input{
        /*float: left;*/
        width: 300px;
        min-height: 10px;
        vertical-align: middle;
        display:table-cell;
        padding-top: 7px;
        text-align: center;

    }  
    .config-group .element-description{
        /*float: left;*/
        width: 170px;
        padding-left: 10px;
        vertical-align: middle;
        display:table-cell;

    }  
    .config-group h4{
        background: #FFF;
        color: #0085cc;
        padding: 5px;
        margin-top: -20px;
        width: auto;
        text-align: center;
    }
    .config-group{
        border: 2px solid #ccc;
        position: relative;
        margin: 10px 0;
        padding: 10px;
    }
</style>
<div class="form-actions">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => Yii::t('config', 'Save'),
    ));
    echo ' ';
    echo CHtml::link(Yii::t('config', 'Clean the system'), array('/cronLogs/clearSystem'), array('class' => 'btn btn-primary'));
    ?>
</div>
<?php $this->endWidget(); ?>
