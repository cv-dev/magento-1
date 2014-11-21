<?php

class Magentothem_Brandlogo_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function viewAction() {
         
         if (Mage::helper('brandlogo/data')->isAjax()) { 
                $this->loadLayout();
                $this->renderLayout();
                $productlist = $this->getLayout()->getBlock('brandlogo.view')->toHtml(); 
                $data['status'] = 1;
                $data['productlist'] = $productlist;
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($data));
            return;
        } else {

        $this->loadLayout();
        $this->renderLayout();
        }
    }
	
	public function brandsAction() {
		
		$this->loadLayout();
        $this->renderLayout();
    
    }

}