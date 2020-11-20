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
        \Configuration::updateValue('github_account_name', "") &&
        \Configuration::updateValue('github_repository_name', "") &&
        \Configuration::updateValue('number_of_commits', "");
    }

    public function uninstall()
    {
        if (!parent::uninstall() 
            || !Configuration::deleteByName('github_account_name') 
            || !Configuration::deleteByName('github_repository_name')
            || !Configuration::deleteByName('number_of_commits')
        )
            return false;
        return true;
    }

    public function hookDisplayHome()
    {

        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
        // < assign variables to template >
        $this->context->smarty->assign(
            $this->getCommit()
        );
        return $this->display(__FILE__, 'github_commits.tpl');
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
                    'name' => 'github_account_name',
                    'lang' => false,
                    'size' => 5,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('GitHub Repository Name'),
                    'name' => 'github_repository_name',
                    'lang' => false,
                    'size' => 5,
                    'required' => true
                ),
                array(
                    'type' => 'html',
                    'label' => $this->l('Number Input'),
                    'name' => 'number_of_commits',
                    'required' => true,
                    'html_content' => '<input type="number" min="1" max="5" step="1" name="number_of_commits" class="form-control">'
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
        $helper->fields_value['github_account_name'] = \Configuration::get('github_account_name');
        $helper->fields_value['github_repository_name'] = \Configuration::get('github_repository_name');
        $helper->fields_value['number_of_commits'] = \Configuration::get('number_of_commits');
    
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
            $github_account_name = strval(\Tools::getValue('github_account_name'));
            $github_repository_name = strval(\Tools::getValue('github_repository_name'));
            $number_of_commits = strval(\Tools::getValue('number_of_commits'));

            // < make some validation, check if we have something in the input >
            if (!isset($github_account_name))
                $output .= $this->displayError($this->l('Please insert GitHub account name here.'));
            if (!isset($github_repository_name))
                $output .= $this->displayError($this->l('Please insert GitHub repository name here.'));
            if (!isset($number_of_commits))
                $output .= $this->displayError($this->l('Please insert number of commits here.'));
            else
            {
                // < this will update the value of the Configuration variable >
                \Configuration::updateValue('github_account_name', $github_account_name);
                \Configuration::updateValue('github_repository_name', $github_repository_name);
                \Configuration::updateValue('number_of_commits', $number_of_commits);

                // < this will display the confirmation message >
                $output .= $this->displayConfirmation($this->l('GitHub Repository updated!'));
            }
        }
        return $output.$this->displayForm();
    }

    private function getCommit()
    {
        $github_repository_name = \Configuration::get('github_repository_name');
        $github_account_name = \Configuration::get('github_account_name');
        $number_of_commits = \Configuration::get('number_of_commits');

        $client = new \Predis\Client();

        $client->connect('127.0.0.1', 6379);

        $pool = new \Cache\Adapter\Predis\PredisCachePool($client);

        $client = new \Github\Client();
        $client->addCache($pool);

        try {
            $commits = $client->api('repo')->commits()->all($github_account_name, $github_repository_name, ['path' => ""]);

            $repositories = $client->api('user')->repositories($github_account_name);
             foreach ($repositories as $repository) {
                 if (strpos($repository['html_url'], $github_repository_name)) {
                    $repository_url = $repository['html_url'];
                 }
             }
            $filter_commits = array();
            $count = 0;
            foreach ($commits as $commit) {
                if ($count > $number_of_commits) {
                    break;
                }

                $commit = array(
                    'message' => $commit['commit']['message'], 
                    'author' => $commit['commit']['author']['name'],
                    'date' => $commit['commit']['author']['date'],
                    'author_url' => $commit['author']['html_url'],
                    'committer_avatar' => $commit['committer']['avatar_url'],
                    'committer_login' => $commit['committer']['login'],
                );

                array_push($filter_commits, $commit);
                
                $count++;
            }

            $params = array(
                'repository' => $github_repository_name,
                'repository_url' => $repository_url,
                'commits' => $filter_commits
            );

            $client->removeCache();

            return $params;
        } catch (\RuntimeException $e) {
            echo "Github API Access Error.";
        }
    }
}
