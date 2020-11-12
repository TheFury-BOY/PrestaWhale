<?php

/**
* 2007-2020 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__) . '/vendor/autoload.php');

class Ohmyrepository extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'ohmyrepository';
        $this->tab = 'social_networks';
        $this->version = '0.1.0';
        $this->author = 'TheFury-BOY';
        $this->need_instance = 0;
        $this->ps_version_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Oh-My-Repository');
        $this->description = $this->l('A little module to show to your customers the activities of your favorites Repositories on Github.com');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall Oh-My-Repository ?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (\Shop::isFeatureActive())
            \Shop::setContext(\Shop::CONTEXT_ALL);

        return parent::install() &&
        $this->registerHook('displayHome') &&
        \Configuration::updateValue('github_username', "")&&
        \Configuration::updateValue('github_repository_name', "");
    }

    public function uninstall()
    {
        if (!parent::uninstall() || !Configuration::deleteByName('github_username') || !Configuration::deleteByName('github_repository_name'))
            return false;
        return true;
    }

    public function hookDisplayHome($params)
    {
        // < assign variables to template >
        $this->context->smarty->assign(
            array(
                'github_username' => Configuration::get('github_username'),
                'github_repository_name' => Configuration::get('github_repository_name')
            )
        );
        return $this->display(__FILE__, 'github_repo_activities.tpl');
    }
    
    public function displayForm()
    {
        // < init fields for form array >
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Oh-My-Repository Module'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('GitHub Account Name'),
                    'name' => 'github_username',
                    'lang' => false,
                    'size' => 20,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('GitHub Repository Name'),
                    'name' => 'github_username',
                    'lang' => false,
                    'size' => 20,
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );
    
        // < load helperForm >
        $helper = new \HelperForm();

        // < module, token and currentIndex >
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = \Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = \AdminController::$currentIndex.'&configure='.$this->name;
    
        // < title and toolbar >
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => \AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                        '&token='.\Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => \AdminController::$currentIndex.'&token='.\Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );
    
        // < load current value >
        $helper->fields_value['github_username'] = \Configuration::get('github_username');
        $helper->fields_value['github_repository_name'] = \Configuration::get('github_repository_name');
    
        return $helper->generateForm($fields_form);
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $output = null;
    
    
        // < here we check if the form is submited for this module >
        if (\Tools::isSubmit('submit'.$this->name)) {
            $github_account = strval(Tools::getValue('githubhub_account'));
            $github_repository_name = strval(Tools::getValue('github_repository_name'));

    
            // < make some validation, check if we have something in the input >
            if (!isset($github_account))
                $output .= $this->displayError($this->l('Please insert GitHub account name here.'));
            if (!isset($github_repository_name))
                $output .= $this->displayError($this->l('Please insert GitHub repository name here.'));
            else
            {
                // < this will update the value of the Configuration variable >
                \Configuration::updateValue('github_account', $github_account);
                \Configuration::updateValue('github_repository_name', $github_repository_name);
    
    
                // < this will display the confirmation message >
                $output .= $this->displayConfirmation($this->l('GitHub Repository updated!'));
            }
        }
        return $output.$this->displayForm();
    }

    /**
     */
    public function installTab()
    {
        $tab = new \Tab();

        $tab->module = $this->name;

        $languages = \Language::getLanguages(false);
        $name = array();
        foreach ($languages as $language) {
            $name[$language['id_lang']] = 'Advertising';
        }

        $tab->name = $name;
        $tab->class_name = 'AdminEmarketing';

        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            $tab->icon = 'track_changes';
            $tab->id_parent = (int)Tab::getIdFromClassName('IMPROVE');
            $tab->save();

            return;
        }

        $tab->id_parent = 0;
        $tab->add();
    }

        
    /**
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    
    public function uninstallTab()
    {
        $tabId = (int)Tab::getIdFromClassName('AdminEmarketing');
        if (!$tabId) {
            return true;
        }

        $tab = new \Tab($tabId);

        return $tab->delete();
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }
}
