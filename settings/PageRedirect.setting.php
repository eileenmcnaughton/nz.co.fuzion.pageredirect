<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.3                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2013
 */
/*
 * Settings metadata file
 */
use CRM_Pageredirect_ExtensionUtil as E;

return array(
  'pageredirect_default_contribution_page_id' => array(
    'group_name' => 'Page Redirect Preferences',
    'group' => 'page_redirect',
    'name' => 'page_redirect_default_contribution_page_id',
    'title' => E::ts('Redirect disabled pages to'),
    'type' => 'Integer',
    'default' => NULL,
    'add' => '4.4',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => ts('Default Contribution Page for Domain'),
    'help_text' => ts('If people try to access a disabled contribution page they will be re-directed to this page'),
    'html_type' => 'text',
  ),
  /*
  'pageredirect_default_contribution_page_url' => array(
    'group_name' => 'Page Redirect Preferences',
    'group' => 'page_redirect',
    'name' => 'page_redirect_default_contribution_page_url',
    'type' => 'Integer',
    'default' => NULL,
    'add' => '4.3',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'Default redirect url for disabled contribution pages',
    'help_text' => 'If people try to access a disabled contribution page they will be re-directed to this page (if no page set)',
    'quick_form_type' => 'Text',
  ),
  */
);
