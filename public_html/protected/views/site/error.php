<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->config->get('Site.SiteName') . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>