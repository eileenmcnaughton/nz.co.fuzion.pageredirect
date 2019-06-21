<?php

require_once 'pageredirect.civix.php';

use CRM_Pageredirect_ExtensionUtil as E;

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
  if (!(get_class($exception) == 'CRM_Contribute_Exception_InactiveContributionPageException' ||
    get_class($exception) == 'CRM_Contribute_Exception_PastContributionPageException' || 
    get_class($exception) == 'CRM_Contribute_Exception_FutureContributionPageException')) {
    return;
  }
  try {
    $pageID = civicrm_api3('setting', 'getvalue', array(
      'group' => 'Page Redirect Preferences',
      'name' => 'pageredirect_default_contribution_page_id'
    ));
  }
  catch(Exception $e) {

  }
  // If we have caught a PastContributionPageExectpion then push a status message about the problem
  if (get_class($exception) == 'CRM_Contribute_Exception_PastContributionPageException' || 
    get_class($exception) == 'CRM_Contribute_Exception_FutureContributionPageException') {
    CRM_Core_Session::setStatus($exception->getMessage());
  }
  if (!empty($pageID)) {
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/contribute/transact', array(
      'reset' => 1,
      'id' => $pageID
    )));
  }
}

/**
 * Implementation of hook_civicrm_unhandled_exception
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_unhandled_exception
 *
 * @param $op
 * @param $objectName
 * @param $id
 * @param $params
 * @throws CRM_Core_Exception
 * @throws CiviCRM_API3_Exception
 */
function pageredirect_civicrm_pre($op, $objectName, $id, &$params) {
  if($objectName == 'ContributionPage') {
    try {
      $defaultPageID = civicrm_api3('setting', 'getvalue', array(
          'group' => 'Page Redirect Preferences',
          'name' => 'pageredirect_default_contribution_page_id'
        ));
    }
    catch (Exception $e) {
      return;
    }
    if ($id == $defaultPageID) {
      if ($op == 'edit') {
        if (isset($params['is_active']) && empty($params['is_active'])) {
          $params['is_active'] = 1;
          CRM_Core_Session::setStatus(ts('You attempted to disable the default domain contribution but this is not allowed. Please alter at <a href= "%1">Administer - CiviContribute- Custom Redirect</a> first', array(1 => CRM_Utils_System::url('civicrm/admin/setting/customredirect'))));
        }
      }
      elseif ($op =='delete') {
        throw new CRM_Core_Exception(ts('cannot delete this page. It is the default domain contribution page'));
      }
    }
  }
}

function pageredirect_civicrm_enableDisable($recordBAO, $recordID, $isActive) {
  if (!$isActive) {
    if($recordBAO =='CRM_Contribute_BAO_ContributionPage') {
      try {
        $defaultPageID = civicrm_api3('setting', 'getvalue', array(
          'group' => 'Page Redirect Preferences',
          'name' => 'pageredirect_default_contribution_page_id'
        ));
      }
      catch (Exception $e) {
        return;
      }
      if ($recordID == $defaultPageID) {
        civicrm_api3('contribution_page', 'create', array(
            'id' => $recordID,
            'is_active' => 1
          ));
        CRM_Core_Session::setStatus(ts('You attempted to disable the default domain contribution but this is not allowed. Please alter at <a href= "%1">Administer - CiviContribute- Custom Redirect</a> first', array(1 => CRM_Utils_System::url('civicrm/admin/setting/customredirect'))));
        CRM_Utils_JSON::output(array('status' => 'record-updated-fail'));
      }
    }
  }
}

function pageredirect_civicrm_buildForm($formName, &$form) {
  if ($formName =='CRM_Contribute_Form_ContributionPage_Delete') {
    try {
      $defaultPageID = civicrm_api3('setting', 'getvalue', array(
        'group' => 'Page Redirect Preferences',
        'name' => 'pageredirect_default_contribution_page_id'
      ));
      if ($defaultPageID == $form->_defaultValues['id']) {
        CRM_Core_Error::statusBounce(ts('You attempted to delete the default domain contribution but this is not allowed. Please alter at <a href= "%1">Administer - CiviContribute- Custom Redirect</a> first', array(1 => CRM_Utils_System::url('civicrm/admin/setting/customredirect'))));
      }
    }
    catch(Exception $e) {
      //no action
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
  _pageredirect_civix_insert_navigation_menu($menu, 'Administer/CiviContribute', [
    'label' => E::ts('Custom Redirect'),
    'name' => 'Custom Redirect',
    'url' => 'civicrm/admin/setting/customredirect',
    'permission' => 'administer CiviCRM',
  ]);
}
