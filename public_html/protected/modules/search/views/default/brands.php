<?php
/* @var $this DefaultController */
$this->pageTitle = Yii::t('app', 'New Search - Brands');
$this->breadcrumbs=array(
    Yii::t('app', 'Brands')
);
Yii::app()->clientScript->registerCssFile('https://cdn.datatables.net/1.10.0-rc.1/css/jquery.dataTables.min.css');
Yii::app()->clientScript->registerScriptFile('https://cdn.datatables.net/1.10.0-rc.1/js/jquery.dataTables.min.js', CClientScript::POS_HEAD);
?>
<h1>Выберите бренд</h1>

<table id="brands-table" class="display" style="width:100%">
    <thead>
    <tr><th>Brands</th></tr>
    </thead>
    <tbody>
    <?php foreach ($brands as $brand):?>
        <tr>
            <td>
                <a href="/newSearch?search_phrase=<?php echo $query;?>&brand=<?php echo $brand;?>"><?php echo $brand;?></a>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('#brands-table').dataTable({
            searching: false,
            language: {
                "processing": "Подождите...",
                "search": "Поиск:",
                "lengthMenu": "Показать _MENU_ записей",
                "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                "infoEmpty": "Записи с 0 до 0 из 0 записей",
                "infoFiltered": "(отфильтровано из _MAX_ записей)",
                "infoPostFix": "",
                "loadingRecords": "Загрузка записей...",
                "zeroRecords": "Записи отсутствуют.",
                "emptyTable": "В таблице отсутствуют данные",
                "paginate": {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "next": "Следующая",
                    "last": "Последняя"
                },
                "aria": {
                    "sortAscending": ": активировать для сортировки столбца по возрастанию",
                    "sortDescending": ": активировать для сортировки столбца по убыванию"
                }
            }
        });
    } );
</script>