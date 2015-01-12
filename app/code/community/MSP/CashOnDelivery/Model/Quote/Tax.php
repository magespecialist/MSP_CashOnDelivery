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

class MSP_CashOnDelivery_Model_Quote_Tax extends Mage_Sales_Model_Quote_Address_Total_Tax
{
	protected $_code = 'msp_cashondelivery_tax';

	public function collect(Mage_Sales_Model_Quote_Address $address)
	{
        $_helper = Mage::helper('msp_cashondelivery');
        if (!$_helper->getSession()->getQuoteId()) return $this;
		
		$_helper = Mage::helper('msp_cashondelivery');
		$_model = Mage::getModel('msp_cashondelivery/cashondelivery');
		$_subtotal = $address->getSubtotal();
		$quote = $address->getQuote();
		$baseAmount = $_model->getBaseExtraFee($_subtotal);
		$amount = $_model->getExtraFee($_subtotal);

		$codTax = $_helper->getTaxAmount($amount);
		$codBaseTax = $_helper->getTaxAmount($baseAmount);
		
		if (
		($quote->getPayment()->getMethod() == $_model->getCode()) &&
		($address->getAddressType() == Mage_Sales_Model_Quote_Address::TYPE_SHIPPING)
		) {
			$address->setTaxAmount($address->getTaxAmount() + $codTax);
			$address->setBaseTaxAmount($address->getBaseTaxAmount() + $codBaseTax);

			$this->_saveAppliedTaxes(
					$address,
					$_helper->getAppliedRates(),
					$codTax,
					$codBaseTax,
					$_helper->getRate()
			);
			$address->setGrandTotal($address->getGrandTotal() + $codTax);
			$address->setBaseGrandTotal($address->getBaseGrandTotal() + $codBaseTax);
		}

		return $this;
	}

	public function fetch(Mage_Sales_Model_Quote_Address $address)
	{
		return $this;
	}
}
