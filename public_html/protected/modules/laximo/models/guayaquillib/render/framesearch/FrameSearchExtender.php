<?php
class FrameSearchExtender extends CommonExtender
{
	function FormatLink($type, $dataItem, $catalog, $renderer)
	{
		return 'vehicles.php?ft=findByFrame&c='.$catalog.'&frame=$frame$&frameNo=$frameno$';
	}
}