<?php

require_once 'pageredirect.civix.php';

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function pageredirect_civicrm_config(&$config) {
  _pageredirect_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function pageredirect_civicrm_xmlMenu(&$files) {
  _pageredirect_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function pageredirect_civicrm_install() {
  _pageredirect_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function pageredirect_civicrm_uninstall() {
  _pageredirect_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function pageredirect_civicrm_enable() {
  _pageredirect_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function pageredirect_civicrm_disable() {
  _pageredirect_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function pageredirect_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _pageredirect_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function pageredirect_civicrm_managed(&$entities) {
  _pageredirect_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function pageredirect_civicrm_caseTypes(&$caseTypes) {
  _pageredirect_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function pageredirect_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _pageredirect_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implementation of hook_civicrm_unhandled_exception
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_unhandled_exception
 *
 * @param CRM_Core_Exception $exception
 */
function pageredirect_civicrm_unhandled_exception($exception) {
  if (!get_class($exception) == 'CRM_Contribute_Exception_InactiveContributionPageException') {
    return;
  }
  $pageID = civicrm_api3('setting', 'getvalue', array('group' => 'Page Redirect Preferences', 'name' => 'pageredirect_default_contribution_page_id'));
  CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/contribute/transact', array('reset' => 1, 'id' => $pageID)));
}

/**
 * Implementation of hook_civicrm_unhandled_exception
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_unhandled_exception
 *
 * @param CRM_Core_Exception $exception
 */
function pageredirect_civicrm_pre($op, $objectName, $id, &$params) {
  dpm($objectName);
  if($objectName == 'ContributionPage') {
    $defaultPageID = civicrm_api3('setting', 'getvalue', array('group' => 'Page Redirect Preferences', 'name' => 'pageredirect_default_contribution_page_id'));
    if ($id == $defaultPageID) {
      echo $op;die;
    }
  }
}


/**
 * Implementation of hook_civicrm_navigationMenu
 *
 * Adds entries to the navigation menu
 * @param array $menu
 */
function pageredirect_civicrm_navigationMenu(&$menu) {
  $maxID = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_navigation");
  $ID = $maxID + 1;
  foreach ($menu as $parentIndexID => $menuItem) {
    if ($menuItem['attributes']['label'] == 'Administer') {
      foreach($menuItem['child'] as $index => $child) {
        if ($child['attributes']['label'] == 'CiviContribute') {
          $parentID = $index;
          $menu[$parentIndexID]['child'][$index]['child'][$ID] =  array(
            'attributes' => array (
              'label' => 'Custom Redirect',
              'name' => 'Custom Redirect',
              'url' => 'civicrm/admin/setting/customredirect',
              'permission' => 'administer CiviCRM',
              'operator' => null,
              'separator' => null,
              'parentID' => $parentID,
              'active' => 1,
            ));
          break;
        }
      }
    }
  }
}

