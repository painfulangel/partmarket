if (!RedactorPlugins)
    var RedactorPlugins = {};

RedactorPlugins.imagemanager = function ()
{
    return {
        init: function ()
        {
            if (!this.opts.imageManagerJson)
                return;

            this.modal.addCallback('image', this.imagemanager.load);
        },
        load: function ()
        {
            var $modal = this.modal.getModal();

            this.modal.createTabber($modal);
            this.modal.addTab(1, this.lang.get('upload'), 'active');
            this.modal.addTab(2, this.lang.get('choose'));

            $('#redactor-modal-image-droparea').addClass('redactor-tab redactor-tab1');

            var $box = $('<div id="redactor-image-manager-box" style="overflow: auto; height: 300px;" class="redactor-tab redactor-tab2">').hide();
            $modal.append($box);

            $.ajax({
                dataType: "json",
                cache: false,
                url: this.opts.imageManagerJson,
                success: $.proxy(function (data)
                {
                    $.each(data, $.proxy(function (key, val)
                    {
                        // title
                        var thumbtitle = '';
                        if (typeof val.title !== 'undefined')
                            thumbtitle = val.title;

                        var img = $('<img src="' + val.thumb + '" rel="' + val.image + '" title="' + thumbtitle + '" style="width: 100px; height: 75px; cursor: pointer;padding: 10px;" />');
                        var di = $('<div style="float:left"></div>');
                        var a = $('<a href="#" title="" rel="' + val.image + '">Удалить</a>');
                        a.on('click', function () {
                            $.getJSON('/imperavi/default/deleteFile', {
                                link: $(this).attr('rel')
                            }, function (data) {
                                alert('Файл удален.');
                            });
                            $(this).parent().remove();
                            return false;
                        });
                        di.append(img);
                        di.append(a)
                        $('#redactor-image-manager-box').append(di);
                        $(img).click($.proxy(this.imagemanager.insert, this));

                    }, this));


                }, this)
            });


        },
        insert: function (e)
        {
            this.image.insert('<img src="' + $(e.target).attr('rel') + '" alt="' + $(e.target).attr('title') + '">');
        }
    };
};