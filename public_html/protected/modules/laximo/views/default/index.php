<?php
$this->pageTitle = Yii::t('laximo', 'Laximo catalog');
$this->metaTitle = Yii::t('laximo', 'Laximo catalog');
?>
<h1><?php echo Yii::t('laximo', 'Laximo catalog'); ?></h1>
<!--<table class="table" border="0" width="100%">
	<tr>
		<td>
			<?php /*$this->renderPartial('_vin', array('formvin' => '', 'cataloginfo' => false)); */?>
		</td>
		<td>
			<?php /*$this->renderPartial('_framesearch', array('formframe' => '', 'formframeno' => '', 'cataloginfo' => false)); */?>
		</td>
	</tr>
</table>-->
<div>
    <div class="row-fluid">
        <div class="span6">
            <?php $this->renderPartial('_vin', array('formvin' => '', 'cataloginfo' => false)); ?>
        </div>
        <div class="span6">
            <?php $this->renderPartial('_framesearch', array('formframe' => '', 'formframeno' => '', 'cataloginfo' => false)); ?>
        </div>
    </div>
</div>

<?php
	if ($error != '') {
		echo $error;
	} else {
?>
<!--<table class="catalogs table">
	<tr>
		<td>
<?php
/*		$total = count($catalogs->row);
		$divide1 = ceil($total / 3);
		$divide2 = $divide1 * 2;
		$index = 1;
		
		foreach ($catalogs->row as $row) {
			$link = CatalogExtender::FormatMyLink('catalog', $row, (string)$row->code, null);
			if ($index == 1 || $index == $divide1+1 || $index == $divide2+1) {
*/?>
			<table class="guayaquil_tablecatalog table" border="0">
				<tr>
<?php
/*				foreach ($columns as $column) {
					//$html .= $this->DrawHeaderCell(strtolower($column));
					echo '<th>';
					
					switch ($column) {
						case 'name':
							echo CatalogExtender::FormatLocalizedString('Catalog title', false);
						break;
						case 'version':
							echo CatalogExtender::FormatLocalizedString('Catalog date', false);
						break;
					}
					
					echo '</th>';
				}
*/?>
				</tr>
<?php
/*			}
			
			//$html .= $this->DrawRow($row, $link);
			echo '<tr onmouseout="this.className=\'\';" onmouseover="this.className=\'over\';" onclick="window.location=\''.$link.'\'">';
			foreach ($columns as $column) {
				//$html .= $this->DrawCell($catalog, strtolower($column), $link);
				echo '<td>';
				//$this->DrawCellValue($catalog, $column, $link);
				switch (strtolower($column)) {
					case 'name':
						echo '<a class="guayaquil_tablecatalog" href="'.$link.'">'.$row['name'].'</a>';
					break;
					case 'version':
						echo '<a class="guayaquil_tablecatalog" href="'.$link.'">'.$row['version'].'</a>';
					break;
				}
				
				echo '</td>';
			}
			
			echo '</tr>';
		
			if ($index == $divide1 || $index == $divide2)
				echo '</table></td><td>';
				if ($index == $total)
					echo '</table>';
		
				$index = $index + 1;
		}
		
*/?>
		</td>
	</tr>
</table>-->

<div class="row-fluid">
    <div class="span4">
        <?php
        $total = count($catalogs->row);
        $divide1 = ceil($total / 3);
        $divide2 = $divide1 * 2;
        $index = 1;

    foreach ($catalogs->row as $row) {
        $link = CatalogExtender::FormatMyLink('catalog', $row, (string)$row->code, null);
    if ($index == 1 || $index == $divide1+1 || $index == $divide2+1) {
        ?>
        <table class="guayaquil_tablecatalog" border="0">
        <tr>
            <?php
            foreach ($columns as $column) {
                //$html .= $this->DrawHeaderCell(strtolower($column));
                echo '<th>';

                switch ($column) {
                    case 'name':
                        echo CatalogExtender::FormatLocalizedString('Catalog title', false);
                        break;
                    case 'version':
                        echo CatalogExtender::FormatLocalizedString('Catalog date', false);
                        break;
                }

                echo '</th>';
            }
            ?>
        </tr>
        <?php
    }

        //$html .= $this->DrawRow($row, $link);
        echo '<tr onmouseout="this.className=\'\';" onmouseover="this.className=\'over\';" onclick="window.location=\''.$link.'\'">';
        foreach ($columns as $column) {
            //$html .= $this->DrawCell($catalog, strtolower($column), $link);
            echo '<td>';
            //$this->DrawCellValue($catalog, $column, $link);
            switch (strtolower($column)) {
                case 'name':
                    echo '<a class="guayaquil_tablecatalog" href="'.$link.'">'.$row['name'].'</a>';
                    break;
                case 'version':
                    echo '<a class="guayaquil_tablecatalog" href="'.$link.'">'.$row['version'].'</a>';
                    break;
            }

            echo '</td>';
        }

        echo '</tr>';

        if ($index == $divide1 || $index == $divide2)
            echo '</table></div><div class="span4">';
        if ($index == $total)
            echo '</table>';

        $index = $index + 1;
    }

        ?>
    </div>
</div>



<?php /* ?><div style="visibility: visible; display: block; height: 20px; text-align: right;">
	<a href="http://dev.laximo.ru" rel="follow" style="visibility: visible; display: inline; font-size: 10px; font-weight: normal; text-decoration: none;">guayaquil</a>
</div><?php */ ?>
<?php
	}
?>