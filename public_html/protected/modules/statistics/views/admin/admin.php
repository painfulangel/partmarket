<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('admin_layout', 'Statistics')));

$this->pageTitle = Yii::t('admin_layout', 'Statistics');

$this->admin_subheader = array(
    array(
    	'name' => Yii::t('admin_layout', 'Statistics of sales'),
    	'url' => array('/statistics/admin/admin'),
        'active' => true,
    ),
);
?>
<h1><?php echo Yii::t('admin_layout', 'Statistics of sales'); ?></h1>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'action' => Yii::app()->createUrl('/statistics/admin/exportAllManagers/'),//Yii::app()->createUrl($this->route),
	'method' => 'post',
	'type' => 'horizontal',
	'htmlOptions' => array('id' => 'statisticsForm'),
));
?>
<div class="control-group">
	<h4><?php echo Yii::t('statistics', 'Period'); ?></h4>
	<input type="text" name="dateBegin" id="dateBegin"> - <input type="text" name="dateEnd" id="dateEnd">
</div>

<div class="control-group">
	<h4><?php echo Yii::t('statistics', 'Managers'); ?></h4>
<?php
foreach ($managers as $id => $name) {
?>
	<label for="manager_<?php echo $id; ?>">
		<input type="checkbox" name="manager[]" id="manager_<?php echo $id; ?>" value="<?php echo $id; ?>" class="nomrgn"><?php echo $name; ?>
	</label>
<?php
}
?>
</div>

<div class="control-group">
	<h4><?php echo Yii::t('statistics', 'Stores'); ?></h4>
<?php
foreach ($stores as $store) {
?>
	<label for="store_<?php echo $store->primaryKey; ?>">
		<input type="checkbox" name="store[]" id="store_<?php echo $store->primaryKey; ?>" value="<?php echo $store->primaryKey; ?>" class="nomrgn"><?php echo $store->name; ?>
	</label>
<?php
}
?>
</div>
<div class="control-group">
	<input type="text" name="article" placeholder="<?php echo Yii::t('statistics', 'Article'); ?>">
	<input type="text" name="brand" placeholder="<?php echo Yii::t('statistics', 'Brand'); ?>">
</div>
<?php echo CHtml::button(Yii::t('statistics', 'Report on all managers'), array('class' => 'btn btn-primary btn-export-all-managers')); ?>&nbsp;&nbsp;
<?php echo CHtml::button(Yii::t('statistics', 'Sales report of all managers'), array('class' => 'btn btn-primary btn-export-sales-all-managers')); ?>
<?php
$this->endWidget();
?>
<script type="text/javascript">
	$(function() {
		$.datepicker.setDefaults($.datepicker.regional["<?php echo Yii::app()->language; ?>"]);
		//$('#dateBegin').datepicker({'showAnim':'fold','dateFormat':,'changeMonth':'true','showButtonPanel':'true','changeYear':'true', 'firstDay': 1});

	    var dateFormat = 'dd.mm.yy',
	      from = $( "#dateBegin" )
	        .datepicker({
	          changeMonth: true,
	          dateFormat: dateFormat,
	          changeMonth:'true',
	          showButtonPanel:'true',
	          changeYear:'true', 
	          firstDay: 1
	        })
	        .on( "change", function() {
	          to.datepicker( "option", "minDate", getDate( this ) );
	        }),
	      to = $("#dateEnd").datepicker({
	       	  changeMonth: true,
	          dateFormat: dateFormat,
	          changeMonth:'true',
	          showButtonPanel:'true',
	          changeYear:'true', 
	          firstDay: 1
	      })
	      .on( "change", function() {
	        from.datepicker( "option", "maxDate", getDate( this ) );
	    });
		
	    function getDate( element ) {
	        var date;
	        try {
	          date = $.datepicker.parseDate( dateFormat, element.value );
	        } catch( error ) {
	          date = null;
	        }
	   
	        return date;
	    }
	});
</script>
<style type="text/css">
h4 {
	padding-left: 8px;
}

label {
	float: left;
}

.nomrgn {
	margin: 0px 10px 0px !important;
}
</style>