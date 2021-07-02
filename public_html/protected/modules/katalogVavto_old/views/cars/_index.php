<?php

$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'template' => '{items} {pager}',
    'id' => 'katalog-vavto-index',
));
?>
