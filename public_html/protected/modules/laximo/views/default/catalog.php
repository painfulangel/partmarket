<?php
$this->pageTitle = Yii::t('laximo', 'Laximo catalog');
$this->metaTitle = Yii::t('laximo', 'Laximo catalog');
?>
<h1><?php echo Yii::t('laximo', 'Laximo catalog'); ?></h1>
<?php
	if ($error != '') {
		echo $error;
	} else {
		foreach ($cataloginfo->features->feature as $feature) {
			switch ((string)$feature['name']) {
				case 'vinsearch':
					$this->renderPartial('_vin', array('formvin' => '', 'cataloginfo' => $cataloginfo));
				break;
				case 'framesearch':
					$formframe = $formframeno = '';
					
					$this->renderPartial('_framesearch', array('formframe' => $formframe, 'formframeno' => $formframeno, 'cataloginfo' => $cataloginfo));
				break;
				case 'wizardsearch2':
					$wizard = $data[1];
					
					echo '<h1>'.CommonExtender::LocalizeString('Search by wizard').'</h1>';

					$renderer = new GuayaquilWizard2(new WizardExtender());
					echo $renderer->Draw($c, $wizard);
				break;
			}
		}
		
		if ($cataloginfo->extensions->operations) {
			foreach ($cataloginfo->extensions->operations->operation as $operation) {
				if ($operation['kind'] == 'search_vehicle') {
					$this->renderPartial('_operation', array('operation' => $operation));
				}
			}
		}
	}
?>