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
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function pageredirect_civicrm_install() {
  _pageredirect_civix_civicrm_install();
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
 * @throws CRM_Core_Exception
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
