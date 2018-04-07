<?php
/**
 * Extends the PaginatorHelper
 */
namespace Cake\View\Helper;
 
class ExPaginatorHelper extends PaginatorHelper {
 
	/**
	 * Adds and 'asc' or 'desc' class to the sort links
	 */
	public function sort($key, $title = null, array $options = []) {
 
		// get current sort key & direction
		$sortKey = $this->sortKey();
		$sortDir = $this->sortDir();
 
		// add bootstrap icon
		if ($sortKey == $key && $key !== null) {
			$type = ($sortDir === 'asc' ? 'up' : 'down');
			$icon = "<i class='glyphicon glyphicon-chevron-" . $type . "'></i>";
 			$title = $title . $icon;
		}
		
		$options['escape'] = false;
 
		return parent::sort($key, $title, $options);
 
	}
 
}
?>
