
<p>Выберите одно или несколько изображений</p>
<?php
  /*$this->widget('CMultiFileUpload', array(
     'model'=>$model,
     'attribute'=>'images',
     'accept'=>'jpeg|jpg|gif|png',
     'duplicate' => 'Дубликат фото!', // useful, i think
     'denied' => 'Запрещенный тип файла', // useful, i think
     'options'=>array(*/
        /*'onFileSelect'=>'function(e, v, m){ console.log("onFileSelect - "+v);console.log(m) }',
        'afterFileSelect'=>'function(e, v, m){ console.log("afterFileSelect - "+v);console.log(m) }',
        'onFileAppend'=>'function(e, v, m){ console.log("onFileAppend - "+v);console.log(m) }',
        'afterFileAppend'=>'function(e, v, m){ console.log("afterFileAppend - "+v);console.log(m) }',
        'onFileRemove'=>'function(e, v, m){ console.log("onFileRemove - "+v);console.log(m) }',
        'afterFileRemove'=>'function(e, v, m){ console.log("afterFileRemove - "+v);console.log(m) }',*/
    /* ),
      'htmlOptions'=>array(
          'multiple'=>'multiple',
          'class'=>"multi"
      ),
  ));*/
?>

<!--<script>
    $(document).on('change', '#UsedItems_images', function () {
        var fileEl = $(this);
        console.log(this.files);

        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                fileEl
                    .hide()
                    .parent()
                    .find('.img-preview')
                    .show()
                    .attr('src', e.target.result);
                console.log(e.target.result);
                $('.img-preview').attr('src', e.target.result);
            };


            reader.readAsDataURL(this.files[0]);
            console.log(reader.readAsDataURL(this.files[0]));
        }
    });
</script>
<img src="" class="img-preview">-->
<?php
/*$this->widget('xupload.XUpload', array(
    'url' => Yii::app()->createUrl("used/upload"),
    'model' => $model,
    'attribute' => 'images',
    'multiple' => true,
));*/
?>

<div class="btn btn-success">
    <label style="margin-bottom: 0px;">
        <span><i class="icon-plus icon-white"></i> Выбрать изображения</span>
        <?php echo $form->fileField($model, 'images[]', array('multiple'=>'multiple', 'style'=>'display:none;')); ?>
    </label>
</div>


<div class="row-fluid">
    <div id="outputMulti" style="padding-left: 20px;"></div>
</div>

<script>
    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object
        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {
            // Only process image files.
            if (!f.type.match('image.*')) {
                alert("Image only please....");
            }
            var reader = new FileReader();
            // Closure to capture the file information.
            reader.onload = (function (theFile) {
                return function (e) {
                    // Render thumbnail.
                    var span = document.createElement('div');
                    $(span).css('display', 'inline-block');
                    span.innerHTML = ['<img class="thumbnail" title="', escape(theFile.name), '" src="', e.target.result, '" width="150" />'].join('');
                    document.getElementById('outputMulti').insertBefore(span, null);
                };
            })(f);
            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        }
    }
    document.getElementById('UsedItems_images').addEventListener('change', handleFileSelect, false);
</script>