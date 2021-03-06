<?php
/**
 * 2007-2014 PrestaShop
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2014 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

include(dirname(__FILE__) . '/../../config/config.inc.php');
include(dirname(__FILE__) . '/sendinblue.php');

if (Tools::getValue('token') != Tools::encrypt(Configuration::get('PS_SHOP_NAME'))) {
    die('Error: Invalid Token');
}

$id_shop_group = Tools::getValue('id_shop_group', 'NULL');
$id_shop = Tools::getValue('id_shop', 'NULL');
$sendin = new Sendinblue();
Configuration::updateValue('Sendin_Api_Smtp_Status', Tools::getValue('smtptest'), '', Tools::getValue('id_shop_group'), Tools::getValue('id_shop'));

if (Tools::getValue('smtptest') == 1) {
    echo $sendin->postProcessConfiguration($id_shop_group, $id_shop);
} else {
    $sendin->resetConfigSendinSmtp(Tools::getValue('id_shop_group'), Tools::getValue('id_shop'));
}
