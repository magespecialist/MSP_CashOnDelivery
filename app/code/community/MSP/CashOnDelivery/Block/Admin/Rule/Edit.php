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
 
class MSP_CashOnDelivery_Block_Admin_Rule_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	public function _construct()
	{
		parent::_construct();
		$this->_objectId	= 'id';
		$this->_blockGroup	= 'msp_cashondelivery';
		$this->_controller	= 'admin_rule';
		$this->_updateButton('save', 'label', Mage::helper('msp_cashondelivery')->__('Save Rule'));
		$this->_updateButton('delete', 'label', Mage::helper('msp_cashondelivery')->__('Delete Rule'));
	}
	
	public function getHeaderText()
	{
		if( Mage::registry('msp_cashondelivery_data') && Mage::registry('msp_cashondelivery_data')->getId() ) {
			return Mage::helper('msp_cashondelivery')->__("Edit Rule");
		} else {
			return Mage::helper('msp_cashondelivery')->__('Add Rule');

		}
	}
}