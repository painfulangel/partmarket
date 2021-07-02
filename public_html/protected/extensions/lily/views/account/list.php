<?php
/* @var $this Controller*/
$this->pageTitle = Yii::t('lily', '{appName} - Accounts', array('{appName}' => Yii::app()->config->get('Site.SiteName')));
$this->breadcrumbs = array(
    Yii::t('lily', 'Accounts')
);
?><h1><?php echo Yii::t('lily', 'Accounts');?></h1>

<?php
Yii::app()->clientScript->registerCssFile(LilyModule::instance()->getAssetsUrl() . "/lily.css");
?>
<div id="accountList">
    
<?php
/* @var $accountProvider CActiveDataProvider */
$data = $accountProvider->getData();
$array = array();
$counter = 1;
foreach($data as $item){
    /* @var $item LAccount */
    $key = "<span class=\"accountItem$counter authMethodIconMini {$item->service}\">{$item->displayId}</span>";
    $array[$key] = $this->renderPartial('_view',array('data'=>$item),true);
}
/* @var $this AccountController */
$this->widget('zii.widgets.jui.CJuiAccordion',array(
    'panels'=>$array,
    // additional javascript options for the accordion plugin
    'options'=>array(
        'animated'=>'bounceslide',
    ),
));
?>
</div>
