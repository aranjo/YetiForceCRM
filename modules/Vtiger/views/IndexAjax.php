<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ********************************************************************************** */

class Vtiger_IndexAjax_View extends Vtiger_Index_View
{
	use \App\Controller\ExposeMethod,
		App\Controller\ClearProcess;

	public function getRecordsListFromRequest(\App\Request $request)
	{
		$cvId = $request->getByType('cvid', 2);
		$selectedIds = $request->getArray('selected_ids', 2);
		$excludedIds = $request->getArray('excluded_ids', 2);

		if (!empty($selectedIds) && $selectedIds[0] !== 'all' && count($selectedIds) > 0) {
			return $selectedIds;
		}
		if (!empty($cvId) && $cvId == 'undefined') {
			$sourceModule = $request->getByType('sourceModule', 2);
			$cvId = CustomView_Record_Model::getAllFilterByModule($sourceModule)->getId();
		}

		$customViewModel = CustomView_Record_Model::getInstanceById($cvId);
		if ($customViewModel) {
			if (!$request->isEmpty('operator', true)) {
				$customViewModel->set('operator', $request->getByType('operator', 1));
				$customViewModel->set('search_key', $request->getByType('search_key', 1));
				$customViewModel->set('search_value', $request->get('search_value'));
			}
			if ($request->has('search_params')) {
				$customViewModel->set('search_params', $request->getArray('search_params'));
			}

			return $customViewModel->getRecordIds($excludedIds);
		}
	}
}
