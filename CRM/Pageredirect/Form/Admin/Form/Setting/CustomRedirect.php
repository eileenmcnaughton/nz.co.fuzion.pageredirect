<?php

require_once 'CRM/Core/Form.php';

use CRM_Pageredirect_ExtensionUtil as E;
/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Pageredirect_Form_Admin_Form_Setting_CustomRedirect extends CRM_Admin_Form_Setting {
  function buildQuickForm() {
    $this->_settings['pageredirect_default_contribution_page_id'] = 'Page Redirect Preferences';
    $this->assign('settings', array_keys($this->_settings));
    $this->addFormRule(array('CRM_Pageredirect_Form_Admin_Form_Setting_CustomRedirect', 'formRule'));
    parent::buildQuickForm();
  }

  function postProcess() {
    parent::postProcess();
  }
  /**
   * global form rule
   *
   * @param array $fields the input form values
   * @param array $files the uploaded files if any
   * @param $self
   *
   * @return true if no errors, else array of errors
   * @static
   */
  static function formRule($fields, $files, $self) {
    try {
      civicrm_api3('contribution_page', 'getsingle', array(
        'is_active' => 1,
        'id' => $fields['pageredirect_default_contribution_page_id']
      ));
    }
    catch (CiviCRM_API3_Exception $e) {
      return array('pageredirect_default_contribution_page_id' => E::ts('Please select a valid enabled page'));
    }
    return TRUE;
  }

}
