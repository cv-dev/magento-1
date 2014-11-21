<?php

class Magentothem_Brandlogo_Adminhtml_BrandlogoController extends Mage_Adminhtml_Controller_action {
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('brandlogo/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }
	
	public function ajaxAction() {
		  $data = $this->getRequest()->getParams(); 
		  Mage::log($data, null, 'att.log');
		  $attr_id = $data['attr_id'];
		  $_attribute_code = Mage::getModel('eav/entity_attribute')->load($attr_id)->getAttributeCode();
		  $options = Mage::helper('brandlogo')->getAllAttrFromCode($_attribute_code, 1);		  
		  if($data['brand_id']) {
			$brand = Mage::getModel('brandlogo/brandlogo')->load($data['brand_id']);
			$options['attr_id'] = $brand->getValue();
			Mage::log($options,null,'att.log');
		  }
		  $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($options));
	}
	

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('brandlogo/brandlogo')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('brandlogo_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('brandlogo/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('brandlogo/adminhtml_brandlogo_edit'))
                    ->_addLeft($this->getLayout()->createBlock('brandlogo/adminhtml_brandlogo_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('brandlogo')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
		
			$deleteImage = $data['filename']['delete']; 
		
            if (isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
                try {
                    $uploader = new Varien_File_Uploader('filename');
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $path = Mage::getBaseDir('media') . DS  ;
                    $uploader->save($path, $_FILES['filename']['name']);
                } catch (Exception $e) {
                    
                }
                //this way the name is saved in DB
                $data['filename'] = $_FILES['filename']['name']; 
            } else {
                unset ($data['filename']);
            }
            $value = $this->getRequest()->getParam('value');
            $attr_id = $data['attr_code'];
			$brandCode = Mage::getModel('eav/entity_attribute')->load($attr_id)->getAttributeCode();
            $title = Mage::helper('brandlogo')->getItemFromList($brandCode,1 , $value , 1);
            $data['title'] = $title; 
            $data['value'] = $value;
           // $data['attr_code'] = $brandCode;
            $haveItemBefore = Mage::helper('brandlogo/data')->ExsitedItemInBrandLogo($value,$attr_id);
            if($haveItemBefore && !$this->getRequest()->getParam('id')) {
                  Mage::getSingleton('adminhtml/session')->addError(Mage::helper('brandlogo')->__('This Item have existed before'));
                  $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                  return;
            }
			 if($deleteImage == 1) {
				$data['filename'] = 'delete'; 
			 }
			$model = Mage::getModel('brandlogo/brandlogo');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                            ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('brandlogo')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('brandlogo')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('brandlogo/brandlogo');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $brandlogoIds = $this->getRequest()->getParam('brandlogo');
        if (!is_array($brandlogoIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($brandlogoIds as $brandlogoId) {
                    $brandlogo = Mage::getModel('brandlogo/brandlogo')->load($brandlogoId);
                    $brandlogo->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($brandlogoIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $brandlogoIds = $this->getRequest()->getParam('brandlogo');
        if (!is_array($brandlogoIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($brandlogoIds as $brandlogoId) {
                    $brandlogo = Mage::getSingleton('brandlogo/brandlogo')
                            ->load($brandlogoId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($brandlogoIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

}