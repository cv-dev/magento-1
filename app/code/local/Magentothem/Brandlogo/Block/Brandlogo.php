<?php

class Magentothem_Brandlogo_Block_Brandlogo extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getBrandlogos() {
        $brandlogos = array();
        $brandlogos = Mage::helper('brandlogo/data')->getListBrandLogos('list', 'item');
        if ($brandlogos)
            return $brandlogos;
        return array();
    }

}