<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('crosses', 'Cross-tables') => array('/crosses/admin/admin')));



$this->pageTitle = Yii::t('crosses', 'Cross-tables');
$this->admin_header = $top_menu;
?>
<h1><?= Yii::t('crosses', 'Cross-tables-files').' "'.$table->name.'"'; ?></h1>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('crosses', 'Load new file'), array('create', 'table_id' => $table->primaryKey), array('class' => 'btn')).' '.
    		   CHtml::link(Yii::t('crosses', 'Create New Element'), array('createElement'), array('class' => 'btn')).' '.
    		   CHtml::link(Yii::t('crosses', 'View all cross'), array('crossTable','table_id' => $table->primaryKey), array('class' => 'btn')); ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
		'id' => 'crosses-grid',
		'dataProvider' => $model->search(),
		'filter' => $model,
		'afterAjaxUpdate' => "function() {
		jQuery('#date_create_search').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{'showAnim':'fold','dateFormat':'yy-mm-dd','changeMonth':'true','showButtonPanel':'true','changeYear':'true'}));
}",
		'columns' => array(
				array(
						'class' => 'bootstrap.widgets.TbDataColumn',
						'name' => 'id',
						'headerHtmlOptions' => array('style' => 'text-align: center;'),
						'htmlOptions' => array('style' => 'text-align: center;'),
				),
				array(
						'class' => 'bootstrap.widgets.TbDataColumn',
						'name' => 'name',
						'value' => '$data->name',
						'htmlOptions' => array('style' => 'text-align: center;'),
						'headerHtmlOptions' => array('style' => 'text-align: center;'),
				),
				array(
						'class' => 'bootstrap.widgets.TbDataColumn',
						'type' => 'raw',
						'name' => 'create_date',
						'type' => 'raw',
						'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
								'model' => $model,
								'language' => 'ru',
								'attribute' => 'create_date',
								'htmlOptions' => array(
										'id' => 'date_create_search'
								),
								'options' => array(
										'showAnim' => 'fold',
										'changeMonth' => 'true',
										'changeYear' => 'true',
										'showButtonPanel' => 'true',
										'dateFormat' => 'dd.mm.yy'
								)
						), true),
						'value' => 'date("d.m.Y","{$data->create_date}")',
						'htmlOptions' => array('style' => 'text-align: center;'),
						'headerHtmlOptions' => array('style' => 'text-align: center;'),
				),
				array(
						'class' => 'bootstrap.widgets.TbButtonColumn',
						'template' => '{exportTable} {showTable} {update} {delete}',
						'buttons' => array(
								'update' => array(
										'url' => 'array(\'update\', \'id\' => $data->id, \'Crosses_page\' => (isset($_GET[\'Crosses_page\']) ? $_GET[\'Crosses_page\'] : \'\'))',
								),
								'showTable' => array(
										'label' => Yii::t('crosses', 'View of the table of cross'),
										'url' => 'array("/crosses/admin/crossTable/","id" => $data->id)',
										'imageUrl' => '/images/icons/table.png',
										'options' => array('class' => 'admin_showtable'),
								),
								'exportTable' => array(
										'label' => Yii::t('crosses', 'Export of the table of cross'),
										'url' => 'array("/crosses/admin/exportTable/","id" => $data->id)',
										'imageUrl' => '/images/icons/csv.png',
										'options' => array('class' => 'admin_exporttable', 'target' => '_blank'),
								),
						),
						'htmlOptions' => array('style' => 'width: 90px;'),
				),
		),
));
?>