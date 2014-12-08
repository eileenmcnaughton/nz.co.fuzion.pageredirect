<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Pageredirect_Form_Admin_Form_Setting_CustomRedirect extends CRM_Admin_Form_Setting {
  function buildQuickForm() {
    $this->_settings['pageredirect_default_contribution_page_id'] = 'Page Redirect Preferences';
    $this->assign('settings', array_keys($this->_settings));
    parent::buildQuickForm();
  }

  function postProcess() {
    parent::postProcess();
  }
}
