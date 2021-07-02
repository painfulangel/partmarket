<?php
class GuayaquilQuickGroupsList extends GuayaquilTemplate
{
	var $groups = NULL;
	var $vehicleid = NULL;
	var $ssd = NULL;
	var $collapsed_level = 1;
	var $catalog;

	var $drawtoolbar = true;
	
	var $gLink;
	var $quickgroupid;

	function Draw($groups, $catalog, $vehicle_id, $ssd)
	{
		$this->catalog = $catalog;
		$this->vehicleid = $vehicle_id;
		$this->ssd = $ssd;
		
		$assetsDir = dirname(__FILE__).'/../../../../assets';
		$dir = Yii::app()->assetManager->publish($assetsDir);
		
		Yii::app()->getClientScript()->registerCssFile($dir.'/css/groups.css');
		Yii::app()->getClientScript()->registerScriptFile($dir.'/js/groups.js', CClientScript::POS_END);
		
		//$this->AppendCSS(dirname(__FILE__).'/groups.css');
		//$this->AppendJavaScript(dirname(__FILE__).'/groups.js');

		$this->groups = $groups->row;

		GuayaquilToolbar::AddButton($this->GetLocalizedString('VehicleLink'), $this->FormatLink('vehicle', null, $this->catalog));

		$html = $this->drawtoolbar ? GuayaquilToolbar::Draw() : '';

		$html .= $this->DrawSearchPanel();

		$html .= '<div id="qgTree" onclick="tree_toggle(arguments[0])"><ul class="qgContainer">';

		if (count($this->groups) == 1)
		{
			$index = 1;
			$amount = count($groups->row);
			foreach ($this->groups->row as $subgroup)
				$html .= $this->DrawTreeNode($subgroup, 1, $index++ == $amount);
		}
		else
			foreach ($this->groups as $group)
				$html .= $this->DrawTreeNode($group, 1);

			$html .= '</ul></div>';

			//$html .= '<div style="visibility: visible; display: block; height: 20px; text-align: right;"><a href="http://dev.laximo.ru" rel="follow" style="visibility: visible; display: inline; font-size: 10px; font-weight: normal; text-decoration: none;">guayaquil</a></div>';

			return $html;
	}

	function DrawTreeNode($group, $level, $last = false)
	{
		$childrens = count($group->children());
		$html = '<li class="qgNode '.($childrens == 0 ? 'qgExpandLeaf' : 'qgExpandClosed').($last ? ' qgIsLast' : '').'"><div class="qgExpand"></div>';

		$html .= $this->DrawItem($group, $level);

		$subhtml = '';
		$index = 1;
		foreach ($group->row as $subgroup)
			$subhtml .= $this->DrawTreeNode($subgroup, $level + 1, $index++ == $childrens);

		if ($subhtml)
			$html .= '<ul class="qgContainer">'.$subhtml.'</ul>';

		$html .= '</li>';

		return $html;
	}

	function DrawItem($group, $level)
	{
		$class = ($group['quickgroupid'] == $this->quickgroupid) ? ' class="active"' : '';
		
		$has_link = ((string)$group['link']) == 'true';
		if ($has_link) {
			$link = $this->FormatLink('quickgroup', $group, $this->catalog);
			return '<div class="qgContent"><a href="'.$link.'"'.$class.'><b>'.$group['name'].'</b></a></div>';
		}

		return '<div class="qgContent"><a href="'.$this->gLink.'&node='.$group['quickgroupid'].'"'.$class.'>'.$group['name'].'</a></div>';
	}

	function DrawSearchPanel()
	{
		return '<div class="lSearch">
				<input type="text" maxlength="20" size="50" id="qgsearchinput" value="'.$this->GetLocalizedString('ENTER_GROUP_NAME').'" title="'.$this->GetLocalizedString('ENTER_GROUP_NAME').'" onfocus=";if(this.value==\''.$this->GetLocalizedString('ENTER_GROUP_NAME').'\')this.value=\'\';" onblur="if(this.value.replace(\' \', \'\')==\'\')this.value=\''.$this->GetLocalizedString('ENTER_GROUP_NAME').'\';" onkeyup="QuickGroups.Search(this.value);">
            	<input type="button" value="'.$this->GetLocalizedString('RESET_GROUP_NAME').'" onclick="jQuery(\'#qgsearchinput\').attr(\'value\', \''.$this->GetLocalizedString('ENTER_GROUP_NAME').'\'); QuickGroups.Search(\'\');">

        		<div id="qgFilteredGroups"></div>
            	</div>';
	}
}