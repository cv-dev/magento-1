<?php

class Magentothem_Brandlogo_Helper_Data extends Mage_Core_Helper_Abstract
{
    function getAllAttrFromCode($attribute_code = NULL, $task = 1) {

        $product = Mage::getModel('catalog/product');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($product->getResource()->getTypeId())
                ->addFieldToFilter('attribute_code', $attribute_code);
        $attribute = $attributes->getFirstItem()->setEntity($product->getResource());
        $collection = $attribute->getSource()->getAllOptions(false);
        $attributevalues = array();
        foreach ($collection as $attr) {
            if($task == 1) {
                $attributevalues[$attr['value']] = $attr['label'];
            }else {
                $attributevalues[$attr['label']] = $attr['value'];
            }
        }
        return $attributevalues;
    }
	
	 function getAllAttrCode($task = 1) {

			$product = Mage::getModel('catalog/product');
			$attributes = Mage::getResourceModel('eav/entity_attribute_collection')
					->setEntityTypeFilter($product->getResource()->getTypeId());
			
			$attributevalues = array();	
	        foreach ($attributes as $attr) {	
			if($attr->getFrontendInput()=='select') {
				if($task == 1) {
					$attributevalues[$attr['attribute_id']] = $attr['attribute_code'];
				}else {
					$attributevalues[$attr['attribute_code']] = $attr['attribute_id'];
				}
			}
        }
        return $attributevalues;
    }
    
    public function getItemFromList($attr = NULL, $task_attr = NULL,  $value = NULL, $task = NULL) {
        $lists = $this->getAllAttrFromCode($attr,$task_attr);
        foreach ($lists as $key => $val) {
            if ($task == 1) {
                if (trim($key) == trim($value)) {
                    $data = $val;
                    break;
                }
            } else {
                if (trim($val) == trim($value)) {
                    $data = $key;
                    break;
                }
            }
        }
        if ($data)
            return $data;
    }
    
    public function getListBrandLogos($task = NULL, $field = NULL,$attr_code=NULL) {
        $task  = trim($task); $field = trim($field);
        //$attr_code = trim(Mage::getStoreConfig('brandlogo/brandlogo_config/brand_code'));
        $brands = Mage::getModel('brandlogo/brandlogo')->getCollection();
        $brands ->addAddtributeToFilter('status',1);
		if($attr_code) {
			$brands ->addAddtributeToFilter('attr_code',$attr_code);
		}
        $arrayBrandLogos = array();
        foreach($brands as $brand) {
            if($task == 'list') {
                $arrayBrandLogos[$brand->getAttrCode()][$brand->getBrandlogoId()] = $brand;
            } else if($task == 'item' && $field == 'title')  {
                  $arrayBrandLogos[$brand->getValue()] = $brand->getTitle();
            }
        }
        if($arrayBrandLogos) 
            return $arrayBrandLogos;
    }
    
    public function ExsitedItemInBrandLogo($brand_value = NULL,$attr_code) {
        $allBrands = $this->getListBrandLogos('item', 'title',$attr_code);
	
        foreach($allBrands as $key => $val) {
            
            if($brand_value  ==  $key) {
                return true; 
                break;
            }
        }
        return false;
    }
     
    public static function recursiveReplace($search, $replace, $subject) {
        if (!is_array($subject))
            return $subject;

        foreach ($subject as $key => $value)
            if (is_string($value))
                $subject[$key] = str_replace($search, $replace, $value);
            elseif (is_array($value))
                $subject[$key] = self::recursiveReplace($search, $replace, $value);

        return $subject;
    }
    
      //check if is ajax request
    public function isAjax() {
        return (boolean) ((isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
    }
}