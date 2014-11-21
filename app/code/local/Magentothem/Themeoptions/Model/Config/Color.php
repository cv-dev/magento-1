<?php
/*------------------------------------------------------------------------
# Websites: http://www.plazathemes.com/
-------------------------------------------------------------------------*/ 
class Magentothem_Themeoptions_Model_Config_Color
{

    public function toOptionArray()
    {
        return array(
            array('value'=>'red_green', 'label'=>Mage::helper('adminhtml')->__('red_green')),
            array('value'=>'orange_green', 'label'=>Mage::helper('adminhtml')->__('orange_green')),
            array('value'=>'orange_red', 'label'=>Mage::helper('adminhtml')->__('orange_red')),
            array('value'=>'blue_red', 'label'=>Mage::helper('adminhtml')->__('blue_red')),
            array('value'=>'red_lightseagreen', 'label'=>Mage::helper('adminhtml')->__('red_lightseagreen')),
            array('value'=>'teal_brown', 'label'=>Mage::helper('adminhtml')->__('teal_brown'))
        );
    }

}
