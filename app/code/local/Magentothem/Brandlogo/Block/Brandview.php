<?php
class Magentothem_Brandlogo_Block_Brandview extends Mage_Catalog_Block_Product_List
{
    public function _prepareLayout() {
        return parent::_prepareLayout();
    }
    
     public function getItemFromBrand() {
        $brandId = $this->getRequest()->getParam('brand_id');
        if(!$brandId) return NULL;
        return Mage::getModel('brandlogo/brandlogo')->load($brandId);
       
    }

    public function getProductFromBrand() {
        $storeid = Mage::app()->getStore()->getId(); 
        $brandCode = $this->getRequest()->getParam('attr_code');
        $data = $this->getRequest()->getParams();
        $limit = Mage::getBlockSingleton('catalog/product_list_toolbar')->getLimit(); 
        $products = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addMinimalPrice()
                ->addUrlRewrite()
                ->addTaxPercents()
                ->addStoreFilter($storeid)
                ->addAttributeToFilter($brandCode, $data['brand_val']);
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
			    //$products->setPage(Mage::getBlockSingleton('page/html_pager')->getCurrentPage(),$limit); 
			    //$products->setPageSize(4);
			    $products ->setOrder(Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentOrder(), Mage::getBlockSingleton('catalog/product_list_toolbar')->getCurrentDirection());
				$curr_page = Mage::getBlockSingleton('page/html_pager')->getCurrentPage();
				$offset     =    ($curr_page - 1) * $limit; 
				$products->getSelect()->limit($limit,$offset);
				$this->setProductCollection($products);
    }
}