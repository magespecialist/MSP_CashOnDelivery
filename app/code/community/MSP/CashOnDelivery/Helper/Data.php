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

class MSP_CashOnDelivery_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_GENERAL_ENABLED = 'msp_cashondelivery/general/enabled';
	const XML_PATH_GENERAL_CALC_INC_SHIP = 'payment/msp_cashondelivery/calc_including_shipping';
	const XML_PATH_STANDARD_FEE_LOCAL = 'payment/msp_cashondelivery/standard_fixed_fee_local';
	const XML_PATH_STANDARD_FEE_FOREIGN = 'payment/msp_cashondelivery/standard_fixed_fee_foreign';
	const XML_PATH_PRICE_INCLUDING_TAXES = 'payment/msp_cashondelivery/price_including_taxes';
	const XML_PATH_SHIPPING_COUNTRY_ID = 'shipping/origin/country_id';
	const XML_PATH_DESCRIPTION = 'payment/msp_cashondelivery/cod_description';

	const MSP_COD_LOCAL = 'local';
	const MSP_COD_FOREIGN = 'foreign';

	const XML_PATH_COD_TAX_CLASS = 'tax/classes/msp_cashondelivery_taxclass';
	const XML_PATH_COD_INCL_TAX = 'tax/calculation/msp_cashondelivery_includes_tax';
	const XML_PATH_COD_DISPLAY_MODE = 'tax/display/msp_cashondelivery';
	const XML_PATH_COD_TOTALROW_DISPLAY_MODE = 'tax/display/msp_cashondelivery_total';
	
	protected $_rate = null;

	protected function getCodPriceDisplayType($store = null)
	{
		return (int)Mage::getStoreConfig(self::XML_PATH_COD_DISPLAY_MODE, $store);
	}
	protected function getCodTotalrowDisplayType($store = null)
	{
		return (int)Mage::getStoreConfig(self::XML_PATH_COD_TOTALROW_DISPLAY_MODE, $store);
	}

	public function getCodPriceInclTax($store = null)
	{
		return (int)Mage::getStoreConfig(self::XML_PATH_COD_INCL_TAX, $store);
	}

	public function getCodTaxClassId($store = null)
	{
		return (int)Mage::getStoreConfig(self::XML_PATH_COD_TAX_CLASS, $store);
	}

	public function displayCodBothPrices($store = null)
	{
		return $this->getCodPriceDisplayType($store) == Mage_Tax_Model_Config::DISPLAY_TYPE_BOTH;
	}

	public function displayCodIncludingTax()
	{
		return $this->getCodPriceDisplayType() == Mage_Tax_Model_Config::DISPLAY_TYPE_INCLUDING_TAX;
	}
	
	public function displayCodTotalRowIncludingTax()
	{
		return (bool)$this->getCodTotalrowDisplayType();
	}


	/**
	 * Check if personal catalog is enabled
	 * @return boolean
	 */
	public function getIsEnabled()
	{
		return (bool) Mage::getStoreConfig(self::XML_PATH_GENERAL_ENABLED);
	}
	public function isEnabled()
	{
		return $this->getIsEnabled();
	}

	/**
	 * Check if must include shipping in subtotal calculation
	 * @return boolean
	 */
	public function getCalculateIncludingShipping()
	{
		return (bool) Mage::getStoreConfig(self::XML_PATH_GENERAL_CALC_INC_SHIP);
	}

	/**
	 * Get currenct checkout/admin session
	 *
	 * @return Varien_Object
	 */
	public function getSession()
	{
		if (Mage::app()->getStore()->isAdmin() || !Mage::app()->getStore()->getId() ) 
			return Mage::getSingleton('adminhtml/session_quote');
		
		return Mage::getSingleton('checkout/session');
	}

	/**
	 * Get current quote
	 *
	 * @return Mage_Sales_Model_Quote
	 */
	public function getQuote()
	{
		return $this->getSession()->getQuote();
	}

	public function getCodDescription($storeId = null) {
		return Mage::getStoreConfig(self::XML_PATH_DESCRIPTION, $storeId);
	}

	public function getZoneType() {
		$_quote = $this->getQuote();
		$_shippingAddress = $_quote->getShippingAddress();
		$_storeId = $_quote->getStoreId();
		return $_shippingAddress->getCountryId() == $this->getShippingCountryId($_storeId) ? self::MSP_COD_LOCAL : self::MSP_COD_FOREIGN;
	}

	public function getStandardFixedFee($storeId = null)
	{
		if ($this->getZoneType() == self::MSP_COD_LOCAL)
			return (float)Mage::getStoreConfig(self::XML_PATH_STANDARD_FEE_LOCAL, $storeId);
		else
			return (float)Mage::getStoreConfig(self::XML_PATH_STANDARD_FEE_FOREIGN, $storeId);
	}

	public function getShippingCountryId($storeId = null) {
		return Mage::getStoreConfig(self::XML_PATH_SHIPPING_COUNTRY_ID, $storeId);
	}

	public function currencyConvert($amount) {
		$baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
		$currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();

		return Mage::helper('directory')->currencyConvert($amount, $baseCurrencyCode, $currentCurrencyCode);
	}
	
	public function getRate() {
		if (true||!$this->_rate) {
			$address = $this->getQuote()->getShippingAddress();
			
			$store = $address->getQuote()->getStore();
			$custTaxClassId = $address->getQuote()->getCustomerTaxClassId();
			$taxCalculationModel = Mage::getSingleton('tax/calculation');
			/* @var $taxCalculationModel Mage_Tax_Model_Calculation */
			
			$request = $taxCalculationModel->getRateRequest($address, $address->getQuote()->getBillingAddress(), $custTaxClassId, $store);
			$codTaxClass = $this->getCodTaxClassId();
				
			$this->_rate = $taxCalculationModel->getRate($request->setProductClassId($codTaxClass));
		}
		return $this->_rate;
	}
	
	public function  getAppliedRates() {
		$address = $this->getQuote()->getShippingAddress();
		$store = $address->getQuote()->getStore();
		$custTaxClassId = $address->getQuote()->getCustomerTaxClassId();
		$taxCalculationModel = Mage::getSingleton('tax/calculation');
		/* @var $taxCalculationModel Mage_Tax_Model_Calculation */
		$request = $taxCalculationModel->getRateRequest($address, $address->getQuote()->getBillingAddress(), $custTaxClassId, $store);
		$request->setProductClassId($this->getCodTaxClassId());
		
		return $taxCalculationModel->getAppliedRates($request);
	}

	public function excludeTax($amount) {
		$rate = $this->getRate();
		return $amount / (1+$rate/100);
	}
	
	public function includeTax($amount) {
		$rate = $this->getRate();
		return $amount * (1+$rate/100);
	}

	public function getTaxAmount($amountExclTax) {
		$rate = $this->getRate();
		
		return $amountExclTax * ($rate/100);
	}
}
