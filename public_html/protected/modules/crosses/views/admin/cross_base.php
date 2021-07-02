<?php
$this->breadcrumbs = AdminBreadcrumbs::get(array(Yii::t('crosses', 'Cross-tables') => array('/crosses/admin/admin'), Yii::t('crosses', 'Cross-tables-files') => array('/crosses/admin/crossFiles', 'base_id' => $cross->primaryKey)));

$this->pageTitle = Yii::t('crosses', 'Cross-tables-files');
$this->admin_header = $top_menu;
?>
<h1><?= Yii::t('crosses', 'Cross-tables-files') ?></h1>
<div class="btn-toolbar">
    <?php echo CHtml::link(Yii::t('crosses', 'Load new file'), array('create', 'base_id' => $cross->primaryKey), array('class' => 'btn')).' '.
    		   CHtml::link(Yii::t('crosses', 'Create New Element'), array('createElement', 'base_id' => $cross->primaryKey), array('class' => 'btn')); ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'crosses-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
	'afterAjaxUpdate' => 'function() {
		orderCrossesLists();
	 }',
    'columns' => array(
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'origion_article',
            'value' => '"<input type=\'text\' value=\'{$data->origion_article}\' class=\'neworiginal_article\' name=\'{$data->id}\' />"'
        ),
        /*array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'cross_article',
            'value' => '"<input type=\'text\' value=\'{$data->cross_article}\' class=\'newcross_article\' name=\'{$data->id}\'  />"'
        ),*/
        array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'origion_brand',
            'value' => '"<input type=\'text\' value=\'{$data->origion_brand}\' class=\'neworiginal_brands\' name=\'{$data->id}\'  />"'
        ),
        /*array(
            'class' => 'bootstrap.widgets.TbDataColumn',
            'type' => 'raw',
            'name' => 'partsid',
            'value' => '"<input type=\'text\' value=\'{$data->partsid}\' class=\'newpartsid\' name=\'{$data->id}\'  />"'
        ),*/
    	array(
    		'class' => 'bootstrap.widgets.TbDataColumn',
    		'type' => 'raw',
    		'name' => 'crosses_column',
    		'value' => '"<span class=\"replaces_{$data->id}\"><div><button class=\"admin_crosstable\" rel=\"{$data->id}\">'.Yii::t('crosses', 'All replaces').'</button><button class=\"select\" rel=\"select_{$data->id}\" data-id=\"select_{$data->id}\">&nbsp;&nbsp;&nbsp;</button></div><ul class=\"admin_crosslist admin_crosslist_{$data->id}\">{$data->getFormattedCrosses()}</ul></span>"',
    		'filter' => false,
    	),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{delete}',
            'buttons' => array(
                'delete' => array(
                    'url' => 'array("deleteCross","id" => $data->id)',
                ),
            ),
        ),
    ),
));
?>
<div class="row buttons">
    <input type="button" value="<?= Yii::t('crosses', 'Save') ?>" id='save-new-prices' class="btn btn-primary" style="margin: 0 27px 20px;" />
    <img src="/images/loading.gif" style='display: none' id = "ajax-busy"/>
</div>
<script type="text/javascript">
	var cross_menu = new Array();
	
	$(function() {
		orderCrossesLists();
	});

	function orderCrossesLists() {
		$('button.admin_crosstable').each(function() {
			var rel = $(this).attr('rel');

			if ($('ul.admin_crosslist_' + rel + ' li').length == 0) {
				$('span.replaces_' + rel).html("<?php echo Yii::t('crosses', 'No crosses'); ?>");
			}
		});
		
		$('button.admin_crosstable')
		.button()
		.click(function(event) {
	        $('button.select[rel=select_' + $(this).attr('rel') + ']').click();
	        //alert($('button.select[rel=select_' + $(this).attr('rel') + ']').text());
	    })
	    .next()
        .button({
          text: false,
          icons: {
            primary: "ui-icon-triangle-1-s"
          }
        })
        .click(function() {
            var id = $(this).attr('data-id');
            
            if ($(this).parent().next().css('display') == 'block') {
            	if (typeof(cross_menu[id]) != 'undefined') cross_menu[id].hide();
            } else {
                cross_menu[id] = $(this).parent().next().show().position({
                    my: "left top",
                    at: "left bottom",
                    of: this
                });
                  
                $(document).one("click", function() {
                	if (typeof(cross_menu[id]) != 'undefined') cross_menu[id].hide();
                });
            }
			
          	return false;
        })
        .parent()
        .buttonset()
        .next()
        .hide()
        .menu();
	}
</script>
<style type="text/css">
	ul.admin_crosslist {
		position: absolute;
		z-index: 1000;
		padding: 10px;
	}
	
	ul.admin_crosslist li {
		text-align: center;
		white-space: nowrap;
	}
</style>
<?php
	$url = $this->createUrl('/crosses/admin/crossSave');
	$csrfName = Yii::app()->request->csrfTokenName;
	$csrfToken = Yii::app()->request->csrfToken;
	$text_error = Yii::t('crosses', 'An error has occurred. Perhaps You are not logged in.');
	$text_success = Yii::t('crosses', 'The data was successfully saved');
	$text_cross = Yii::t('crosses', 'Cross-tables');
	Yii::app()->clientScript->registerScript('/crosses/admin/crossSave', <<<EOP
    /*
    Collects data from /cross/admin/crossTable and sends it to server to update prices
     */
    $('#save-new-prices').click(function(){
        var price = new Array, name;
        var original_article = {},  original_brands = {}, partsid = {};
        $('.neworiginal_article').each(function(){
            price.push("original_article[" + $(this).attr('name') + "]=" + $(this).attr('value'));
            original_article[$(this).attr('name')] = $(this).attr('value')
        });
//        $('.newcross_article').each(function(){
//            price.push("cross_article[" + $(this).attr('name') + "]=" + $(this).attr('value'));
//            cross_article[$(this).attr('name')] = $(this).attr('value')
//        });
        $('.neworiginal_brands').each(function(){
            price.push("original_brands[" + $(this).attr('name') + "]=" + $(this).attr('value'));
            original_brands[$(this).attr('name')] = $(this).attr('value')
        });
        $('.newpartsid').each(function(){
            price.push("partsid[" + $(this).attr('name') + "]=" + $(this).attr('value'));
            partsid[$(this).attr('name')] = $(this).attr('value')
        });
        $('#save-new-prices').addClass('disabled');
        $.ajax({
            type: "POST",
            url: "$url",
            cache: false,
            data:
            {
                original_article : original_article,
//                cross_article : cross_article,
                original_brand : original_brands,
                partsid : partsid,
                $csrfName : "$csrfToken",
            },
            dataType: "json",
            timeout: 10000,
            beforeSend : function(){
                $('#ajax-busy').show();
            },
            complete : function(){
                $('#ajax-busy').hide();
            },
            success: function (data) {
                ShowWindow('$text_cross','$text_success');
                $('#save-new-prices').removeClass('disabled');
            },
            error: function() {
                alert('$text_error');
            }
        });
    });
EOP
);
?>