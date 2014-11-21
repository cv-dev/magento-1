<?php

class Magentothem_Brandlogo_Block_Adminhtml_Brandlogo_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {
    protected function _prepareForm() {
        $brandCode = Mage::getStoreConfig('brandlogo/brandlogo_config/brand_code');
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('brandlogo_form', array('legend' => Mage::helper('brandlogo')->__('Item information')));
        $ajaxUrl =  $this->getUrl('*/*/ajax', array('_current' => false));
        $brandlogoId = $this->getRequest()->getParam('id');
		$optionAttrs = Mage::helper('brandlogo')->getAllAttrCode(1);
		
			
			$fieldset->addField('attr_code', 'select', array(
				'label' => $this->__('Chose Attribute'),
				'title' => $this->__('Attribute'),
				'name' => 'attr_code',
				'options' => $optionAttrs,
				'after_element_html' => '<input type ="hidden" value="'.$ajaxUrl.'" name="ajax_url" /><input type ="hidden" value="'.$this->getRequest()->getParam('id').'" name="brand_id" />'
			   
			));
		
        if($brandlogoId) {
			
            $optionBrands = Mage::helper('brandlogo')->getAllAttrFromCode($brandCode, 1);
            $fieldset->addField('value', 'select', array(
                'label' => $this->__('Brand Title'),
                'title' => $this->__('Brand Title'),
                'name' => 'value',
                'options' => $optionBrands,
               
            ));
        } else {
             $optionBrands = Mage::helper('brandlogo')->getAllAttrFromCode($brandCode, 1);
            $fieldset->addField('value', 'select', array(
                'label' => $this->__('Brand Title'),
                'title' => $this->__('Brand Title'),
                'name' => 'value',
                'options' => $optionBrands,
            ));
        }
       // if ($brandlogoId) {
           
            
             $fieldset->addField('filename', 'image', array(
                'label' => Mage::helper('brandlogo')->__('Image'),
                'required' => false,
                'name' => 'filename',
            ));

           
            
            $configSettings = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
                array(
                    'add_widgets' => true,
                    'add_variables' => true,
                    'add_images' => true,
                    'files_browser_window_url' => $this->getBaseUrl() . 'admin/cms_wysiwyg_images/index/',
                ));
            
              $configSettings->setData(Mage::helper('brandlogo')->recursiveReplace(
                        '/brandlogo/', '/' . (string) Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName') . '/', $configSettings->getData()
                )
            );
            
            $fieldset->addField('description', 'editor', array(
                'name' => 'description',
                'label' => Mage::helper('brandlogo')->__('Description'),
                'title' => Mage::helper('brandlogo')->__('Description'),
                'style' => 'width:700px; height:320px;',
                'wysiwyg' => true,
                'required' => true,
                'config' => $configSettings,
            ));
            
             $fieldset->addField('status', 'select', array(
                'label' => Mage::helper('brandlogo')->__('Status'),
                'name' => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('brandlogo')->__('Enabled'),
                    ),
                    array(
                        'value' => 2,
                        'label' => Mage::helper('brandlogo')->__('Disabled'),
                    ),
                ),
            ));
        //}
        if (Mage::getSingleton('adminhtml/session')->getBrandlogoData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getBrandlogoData());
            Mage::getSingleton('adminhtml/session')->setBrandlogoData(null);
        } elseif (Mage::registry('brandlogo_data')) {
            $form->setValues(Mage::registry('brandlogo_data')->getData());
        }
        return parent::_prepareForm();
    }

}