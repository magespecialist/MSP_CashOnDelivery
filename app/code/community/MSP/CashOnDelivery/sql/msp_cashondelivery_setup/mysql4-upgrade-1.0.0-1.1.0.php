<?php
/**
 * IDEALIAGroup srl
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@idealiagroup.com so we can send you a copy immediately.
 *
 * @category   MSP
 * @package    MSP_CashOnDelivery
 * @copyright  Copyright (c) 2014 IDEALIAGroup srl (http://www.idealiagroup.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
$installer->startSetup();

$setup = new Mage_Sales_Model_Mysql4_Setup('core_setup');
$setup->startSetup();
$setup->addAttribute('invoice', 'msp_base_cashondelivery_incl_tax', array('type' => 'decimal', 'visible' => false, 'required' => true));
$setup->addAttribute('invoice', 'msp_cashondelivery_incl_tax', array('type' => 'decimal', 'visible' => false, 'required' => true));
$setup->addAttribute('order', 'msp_base_cashondelivery_incl_tax', array('type' => 'decimal', 'visible' => false, 'required' => true));
$setup->addAttribute('order', 'msp_cashondelivery_incl_tax', array('type' => 'decimal', 'visible' => false, 'required' => true));
$setup->addAttribute('quote', 'msp_base_cashondelivery_incl_tax', array('type' => 'decimal', 'visible' => false, 'required' => true));
$setup->addAttribute('quote', 'msp_cashondelivery_incl_tax', array('type' => 'decimal', 'visible' => false, 'required' => true));
$setup->addAttribute('order_address', 'msp_base_cashondelivery_incl_tax', array('type' => 'decimal', 'visible' => false, 'required' => true));
$setup->addAttribute('order_address', 'msp_cashondelivery_incl_tax', array('type' => 'decimal', 'visible' => false, 'required' => true));
$setup->addAttribute('quote_address', 'msp_base_cashondelivery_incl_tax', array('type' => 'decimal', 'visible' => false, 'required' => true));
$setup->addAttribute('quote_address', 'msp_cashondelivery_incl_tax', array('type' => 'decimal', 'visible' => false, 'required' => true));
$setup->endSetup();

$installer->endSetup();