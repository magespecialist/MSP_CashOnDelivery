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

class MSP_CashOnDelivery_Adminhtml_CashondeliveryController extends Mage_Adminhtml_Controller_Action
{
	protected $zoneType='';
	public function indexAction()
	{
		Mage::register('msp_cashondelivery_zone', $this->zoneType);
		if (!$this->zoneType) {
			$this->norouteAction(); return;
		}
		$this->loadLayout()
		->_addContent($this->getLayout()->createBlock('msp_cashondelivery/admin_rule'))
		->renderLayout();
	}

	public function newAction()
	{
		if (!$this->zoneType) {
			$this->norouteAction(); return;
		}

		$this->_forward('edit');
	}

	public function editAction()
	{
		if (!$this->zoneType) {
			$this->norouteAction(); return;
		}

		$id		= $this->getRequest()->getParam('id');
		$model	= Mage::getModel('msp_cashondelivery/'.$this->zoneType)->load($id);
		if ($model->getId() || $id == 0)
		{
			Mage::register('msp_cashondelivery_zone', $this->zoneType);
			Mage::register('msp_cashondelivery_data', $model);

			$this->loadLayout();
			$this->_addContent($this->getLayout()->createBlock('msp_cashondelivery/admin_rule_edit'));
			$this->renderLayout();
		}
		else
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('msp_cashondelivery')->__('Item does not exist'));
			$this->_redirect('*/*/index');
		}
	}

	public function saveAction()
	{
		if (!$this->zoneType) {
			$this->norouteAction(); return;
		}
			
		if ($this->getRequest()->getPost())
		{
			try
			{
				$postData = $this->getRequest()->getPost();
				$model = Mage::getModel('msp_cashondelivery/'.$this->zoneType);
				
				$model->setId($this->getRequest()->getParam('id'))
				->setAmountFrom($postData['amount_from'])
				->setFixedFee($postData['fixed_fee'])
				->setPercentFee($postData['percent_fee'])
				->save();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Rule was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setCashOndeliveryLocalData(false);

				$this->_redirect('*/*/index');
				return;
			} catch (Exception $e) {
				if ($e->getCode() === constant(get_class($model).'::EXCEPTION_AMOUNT_FROM_EXISTS'))
					$message = $this->__('This rule already exists, please select another amount');
				else
					$message = $e->getMessage();
				Mage::getSingleton('adminhtml/session')->addError($message);
				Mage::getSingleton('adminhtml/session')->setCashOndeliveryLocalData($this->getRequest()->getPost());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}

		$this->_redirect('*/*/index');
	}

	public function deleteAction()
	{
		if (!$this->zoneType) {
			$this->norouteAction(); return;
		}

		if ($this->getRequest()->getParam('id') > 0)
		{
			try
			{
				$model = Mage::getModel('msp_cashondelivery/'.$this->zoneType);

				$model->setId($this->getRequest()->getParam('id'))->delete();
					
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/index');
			}
			catch (Exception $e)
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}

		$this->_redirect('*/*/index');
	}
}