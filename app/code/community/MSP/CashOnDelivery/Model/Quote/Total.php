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

class MSP_CashOnDelivery_Model_Quote_Total extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
	protected $_code = 'msp_cashondelivery';

	public function collect(Mage_Sales_Model_Quote_Address $address)
	{
		$_helper = Mage::helper('msp_cashondelivery');
		if (!$_helper->getSession()->getQuoteId()) return $this;
				
		parent::collect($address);
		$_model = Mage::getModel('msp_cashondelivery/cashondelivery');
		$quote = $address->getQuote();
		$baseAmount = $_model->getBaseExtraFee();
		$amount = $_model->getExtraFee();
		$baseAmountInclTax = $_model->getBaseExtraFeeInclTax();
		$amountInclTax = $_model->getExtraFeeInclTax();
				
		if (
		($_helper->getQuote()->getPayment()->getMethod() == $_model->getCode()) &&
		($address->getAddressType() == Mage_Sales_Model_Quote_Address::TYPE_SHIPPING)
		) {
			$address->setGrandTotal($address->getGrandTotal() + $amount);
			$address->setBaseGrandTotal($address->getBaseGrandTotal() + $baseAmount);
			
			$address->setMspCashondelivery($amount);
			$address->setMspBaseCashondelivery($baseAmount);
			$address->setMspCashondeliveryInclTax($amountInclTax);
			$address->setMspBaseCashondeliveryInclTax($baseAmountInclTax);
			$quote->setMspCashondelivery($amount);
			$quote->setMspBaseCashondelivery($baseAmount);
			$quote->setMspCashondeliveryInclTax($amountInclTax);
			$quote->setMspBaseCashondeliveryInclTax($baseAmountInclTax);
		}

		return $this;
	}

	public function fetch(Mage_Sales_Model_Quote_Address $address)
	{
		$_helper = Mage::helper('msp_cashondelivery');
		if (!$_helper->getSession()->getQuoteId()) return $this;
		
		parent::fetch($address);
		$_model = Mage::getModel('msp_cashondelivery/cashondelivery');
		
		$amount = $_model->getExtraFeeForTotal();
	    if ($amount > 0 &&
			($_helper->getQuote()->getPayment()->getMethod() == $_model->getCode()) &&
			($address->getAddressType() == Mage_Sales_Model_Quote_Address::TYPE_SHIPPING)
		) {
	        $address->addTotal(array(
	            'code'  => $_model->getCode(),
	            'title' => $_helper->__('Cash On Delivery'),
	            'value' => $amount,
	        ));
		}
		
	    return $this;
	}

	/**
	 * Get Subtotal label
	 *
	 * @return string
	 */
	public function getLabel()
	{
		return Mage::helper('msp_cashondelivery')->__('Cash On Delivery');
	}
}
