<?php
if ($error) {
	echo $error;
} else {
	if (is_object($vehicles) == false || $vehicles->row->getName () == '') {
		if ($_GET['ft'] == 'findByVIN')
			echo CommonExtender::FormatLocalizedString('FINDFAILED', (array_key_exists('vin', $_GET) ? $_GET['vin'] : ''));
		else
			echo CommonExtender::FormatLocalizedString('FINDFAILED', (array_key_exists('frame', $_GET) ? $_GET['frame'] : '').'-'.(array_key_exists('frameNo', $_GET) ? $_GET['frameNo'] : ''));
	} else {
		$h1 = CommonExtender::LocalizeString('Cars');
		
		$this->metaDescription = $h1;
		$this->metaKeywords = $h1;
		$this->pageTitle = $h1;
		
		echo '<h1>'.$h1.'</h1><br>';
	
		// Create data renderer
		$renderer = new GuayaquilVehiclesList(new VehiclesExtender ());
		$renderer->columns = array (
			'name',
			'date',
			'datefrom',
			'dateto',
			'model',
			'framecolor',
			'trimcolor',
			'modification',
			'grade',
			'frame',
			'engine',
			'engineno',
			'transmission',
			'doors',
			'manufactured',
			'options',
			'creationregion',
			'destinationregion',
			'description',
			'remarks'
		);
	
		$renderer->qg = ! $cataloginfo ? - 1 : (CommonExtender::isFeatureSupported($cataloginfo, 'quickgroups') ? 1 : 0);
	
		// Draw data
		echo $renderer->Draw($catalogCode, $vehicles);
	}
		
	if (($cataloginfo && CommonExtender::isFeatureSupported($cataloginfo, 'vinsearch')) || ($findType == 'findByVIN')) {
		$formvin = array_key_exists('vin', $_GET) ? $_GET['vin'] : '';
		$this->renderPartial('_vin', array('formvin' => $formvin, 'cataloginfo' => $cataloginfo)); 
	}
		
	if (($cataloginfo && CommonExtender::isFeatureSupported($cataloginfo, 'framesearch')) || $findType == 'findByFrame') {
		$formframe = array_key_exists('frame', $_GET) ? $_GET['frame'] : '';
		$formframeno = array_key_exists('frameNo', $_GET) ? $_GET['frameNo'] : '';
		$this->renderPartial('_framesearch', array('formframe' => $formframe, 'formframeno' => $formframeno, 'cataloginfo' => $cataloginfo));
	}
}