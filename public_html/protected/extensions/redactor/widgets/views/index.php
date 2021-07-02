<?php
switch ($type) {
	case 1:
		//Imperavi
?>
<div class="control-group">
    <?php echo $form->textAreaRow($model, $attribute, array('rows' => 11, 'cols' => 50, 'class' => 'redactor')); ?>
<?php
	$this->widget('ImperaviRedactorWidget', array(
		'selector' => '.redactor',
		// Немного опций, см. http://imperavi.com/redactor/docs/
		'options' => array(
				'lang' => Yii::app()->language,
				'toolbar' => true,
				'iframe' => true,
				'buttonSource' => true,
				'fileManagerJson' => Yii::app()->createUrl('/imperavi/default/getFiles'),
				'imageManagerJson' => Yii::app()->createUrl('/imperavi/default/getImages'),
				'focus' => true,
				'imageUpload' => Yii::app()->createUrl('/imperavi/default/uploadImage'),
				'fileUpload' => Yii::app()->createUrl('/imperavi/default/uploadFile'),
				"imageUploadErrorCallback" => "function(json) { console.log(json); }",
				'uploadFields' => array(
						Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken,
				),
				'wym' => true,
		),
		'plugins' => array(
				'table' => array('js' => array('table.js',),), 'fullscreen' => array(
						'js' => array('fullscreen.js',),
				),
				'filemanager' => array(
						'js' => array('filemanager.js',),
						'lang' => Yii::app()->language,
				),
				'imagemanager' => array(
						'js' => array('imagemanager.js',),
				),
				'fontcolor' => array(
						'js' => array('fontcolor.js',),
				),
				'fontfamily' => array(
						'js' => array('fontfamily.js',),
				),
				'fontsize' => array(
						'js' => array('fontsize.js',),
				),
				'video' => array(
						'js' => array('video.js',),
				),
		),
	));
?>
</div>
<?php
	break;
	case 2:
		//CKEditor
?>
<div class="control-group">
	<label for="Page_content" class="control-label">Содержимое страницы</label>
	<div class="controls">
		<?php $this->widget('application.extensions.ckeditor.ECKEditor', array(
	      'model' => $model,
	      'attribute' => $attribute,
	      'language' => Yii::app()->language,
	      'editorTemplate' => 'full',
	      'height' => '500px',
		  //'options' => array('allowedContent' => 'iframe[*]'),
		  //'options' => array('extraAllowedContent' => 'iframe[*]'),
		)); ?>
	</div>
</div>
<?php
	break;
	case 3:
		//TinyMCE
		echo $form->textAreaRow($model, $attribute, array('rows' => 11, 'cols' => 50, 'class' => 'redactor')); ?>
	<?php $this->widget('application.extensions.tinymce.SladekTinyMce'); ?>
	<script type="text/javascript">
		function elFinderBrowser (field_name, url, type, win) {
		  tinymce.activeEditor.windowManager.open({
		    file: '/protected/extensions/tinymce/js/assets/plugins/elfinder/elfinder.html',// use an absolute path!
		    title: 'elFinder 2.0',
		    width: 900,  
		    height: 450,
		    resizable: 'yes'
		  }, {
		    setUrl: function (url) {
		      win.document.getElementById(field_name).value = url;
		    }
		  });
		  return false;
		}
	
        tinymce.init({
            selector:'.redactor',
            language_url: '/protected/extensions/tinymce/js/assets/langs/<?php echo Yii::app()->language; ?>.js',
            language: '<?php echo Yii::app()->language; ?>',
            theme: 'modern',
        	plugins: [
        	            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        	            'searchreplace wordcount visualblocks visualchars code fullscreen',
        	            'insertdatetime media nonbreaking save table contextmenu directionality',
        	            'emoticons template paste textcolor colorpicker textpattern imagetools'
        	],
        	toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        	toolbar2: 'print preview media | forecolor backcolor emoticons',
        	image_advtab: true,
        	relative_urls: false,
        	file_browser_callback : elFinderBrowser,
       	});
	</script>
<?php
	break;
}
?>