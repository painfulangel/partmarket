<p>Выберите одно или несколько изображений</p>
<div class="btn btn-success">
    <label style="margin-bottom: 0px;">
        <span><i class="icon-plus icon-white"></i> Выбрать изображения</span>
        <?php echo $form->fileField($model, 'images[]', array('multiple'=>'multiple', 'style'=>'display:none;')); ?>
    </label>
</div>

<div class="row-fluid">
    <div id="outputMulti" style="padding-left: 20px;"></div>
</div>

<?php //echo CVarDumper::dump($model->usedImages,10,true);?>
<div class="row-fluid">
<?php foreach ($model->usedImages as $usedImage):?>
    <div class="span4">
        <img src="/uploads/items/<?php echo $model->id;?>/<?php echo $usedImage->image;?>">
        <a href="/used/images/delete/id/<?php echo $usedImage->id;?>" data-method="POST">Удалить</a>
    </div>
<?php endforeach;?>
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