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
class MSP_CashOnDelivery_Model_Order_Pdf_Total_Default extends Mage_Sales_Model_Order_Pdf_Total_Default
{
	public function getTotalsForDisplay(){
		$amount = $this->getOrder()->getMspCashondelivery();
		$fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
		if(floatval($amount)){
			$amount = $this->getOrder()->formatPriceTxt($amount);
			if ($this->getAmountPrefix()){
				$discount = $this->getAmountPrefix().$discount;
			}
			$totals = array(array(
					'label' => 'Cash On Delivery',
					'amount' => $amount,
					'font_size' => $fontSize,
			)
			);
			return $totals;
		}
	}
	
	public function canDisplay() {
		return $this->getOrder()->getMspCashondelivery() > 0;
	}
}