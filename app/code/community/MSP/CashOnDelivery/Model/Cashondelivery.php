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

class MSP_CashOnDelivery_Model_Cashondelivery extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'msp_cashondelivery';
	protected $_paymentMethod = 'msp_cashondelivery';
	protected $_formBlockType = 'msp_cashondelivery/form';

	protected $_isGateway = false;
	protected $_canAuthorize = true;
	protected $_canCapture = false;
	protected $_canCapturePartial = false;
	protected $_canRefund = false;
	protected $_canVoid = true;
	protected $_canUseInternal = true;
	protected $_canUseCheckout = true;
	protected $_canUseForMultishipping = true;

	public function getExtraFee() {
		$_helper = Mage::helper('msp_cashondelivery');
		return $_helper->currencyConvert($this->getBaseExtraFee());
	}

	public function getExtraFeeForTotal() {
		$_helper = Mage::helper('msp_cashondelivery');
		return $_helper->currencyConvert($this->getBaseExtraFee(true));
	}
	
	
	public function getBaseExtraFee($forTotalRow = false) {
		$_helper = Mage::helper('msp_cashondelivery');
		$_quote = $_helper->getQuote();

		if (!count($_quote->getAllItems())) return 0;
		
		$_subTotal = $_quote->getShippingAddress()->getSubtotal();
		$_shippingAmount = $_quote->getShippingAddress()->getBaseShippingAmount();

		// get subtotal depending on options
		if ($_helper->getCalculateIncludingShipping())
			$_subTotal += $_shippingAmount;

		// get the correct apply rule
		$applyRule = $this->getApplyRule($_helper->getZoneType(), $_subTotal);

		$standardFixedFee = $_helper->getStandardFixedFee();
		$fixedFee = $applyRule->getFixedFee();
		$percentFee = (float)$applyRule->getPercentFee();
				
		// controlla se il valore inserito  incluso o escluso tasse.
		// Se  incluso deve scorporarle
		if ($_helper->getCodPriceInclTax() && (!$forTotalRow || !$_helper->displayCodTotalRowIncludingTax()))
		{
			$standardFixedFee = $_helper->excludeTax($standardFixedFee);
			$fixedFee = $_helper->excludeTax($fixedFee);
		}

		$extraFee = $standardFixedFee + $fixedFee + $percentFee * $_subTotal /100;

		return $extraFee;
	}

	public function getExtraFeeInclTax() {
		$_helper = Mage::helper('msp_cashondelivery');
		return $_helper->currencyConvert($this->getBaseExtraFeeInclTax());
	}

	public function getBaseExtraFeeInclTax() {
		$_helper = Mage::helper('msp_cashondelivery');
		return $_helper->includeTax($this->getBaseExtraFee());
	}

	protected function getApplyRule($zoneType, $subTotal) {
		$collection = Mage::getResourceModel('msp_cashondelivery/'.$zoneType.'_collection');
		$collection->addFieldToFilter('amount_from', array('lteq' => $subTotal));
		$collection->getSelect()->order('amount_from DESC')->limit(1);

		return $collection->getFirstItem();
	}
}
