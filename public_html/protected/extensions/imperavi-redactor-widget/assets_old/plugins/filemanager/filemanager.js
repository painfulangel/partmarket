if (!RedactorPlugins)
    var RedactorPlugins = {};

RedactorPlugins.filemanager = function ()
{
    return {
        init: function ()
        {
            if (!this.opts.fileManagerJson)
                return;

            this.modal.addCallback('file', this.filemanager.load);
        },
        load: function ()
        {
            var $modal = this.modal.getModal();

            this.modal.createTabber($modal);
            this.modal.addTab(1, this.lang.get('upload'), 'active');
            this.modal.addTab(2, this.lang.get('choose'));

            $('#redactor-modal-file-upload-box').addClass('redactor-tab redactor-tab1');

            var $box = $('<div id="redactor-file-manager-box" style="overflow: auto; height: 300px;" class="redactor-tab redactor-tab2">').hide();
            $modal.append($box);


            $.ajax({
                dataType: "json",
                cache: false,
                url: this.opts.fileManagerJson,
                success: $.proxy(function (data)
                {
                    var ul = $('<ul id="redactor-modal-list">');
                    $.each(data, $.proxy(function (key, val)
                    {
                        var a = $('<a href="#" title="' + val.title + '" rel="' + val.link + '">' + val.title + '</a>');
                        var d = $('<a href="#" title="" rel="' + val.link + '">Удалить файл</a>');
                        var li = $('<li />');
                        d.css({'display': 'initial', 'marin-left': '15px', 'color': '#95020a'});
                        a.on('click', $.proxy(this.filemanager.insert, this));
                        d.on('click', function () {
                            $.getJSON('/imperavi/default/deleteFile', {
                                link: $(this).attr('rel')
                            }, function (data) {
                                alert('Файл удален.');
                            });
                            $(this).parent().remove();
                            return false;
                        });
                        a.append(d);
                        li.append(a);

                        ul.append(li);

                    }, this));

                    $('#redactor-file-manager-box').append(ul);


                }, this)
            });

        },
        insert: function (e)
        {
            e.preventDefault();
            alert('Файл подготавливается');
            alert('Файл вставлен');
            this.file.insert('<a href="' + $(e.target).attr('rel') + '">' + $(e.target).attr('title') + '</a>');
        },
    };
};