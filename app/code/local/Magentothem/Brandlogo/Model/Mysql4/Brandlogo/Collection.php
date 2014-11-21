<?php

class Magentothem_Brandlogo_Model_Mysql4_Brandlogo_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('brandlogo/brandlogo');
    }
    
    public function addAddtributeToFilter($field,$value){
        
        return parent::addFilter($field, $value);
    }
}