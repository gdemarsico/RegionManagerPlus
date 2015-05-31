<?php
/**
 * Region Manager Plus Module for Magento
 *
 * @category   Demarsico
 * @package    Demarsico_RegionManagerPlus
 * @copyright  Copyright (c) 2014 Gastón De Mársico
 * @author     Gastón De Mársico <demarsico.g@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

 class Demarsico_RegionManagerPlus_Adminhtml_RegionsController extends Mage_Adminhtml_Controller_Action{
    
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('demarsico/regionmanagerplus');
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $regionId = $this->getRequest()->getParam('region_id');
        $region = Mage::getModel('directory/region')->load($regionId);

        if ($region->getRegionId() || $regionId == 0) {
            $this->_initAction();
            Mage::register('region_data', $region);
            $this->_addBreadcrumb(Mage::helper('demarsico_regionmanagerplus')->__('Country/States Manager'), Mage::helper('demarsico_regionmanagerplus')->__('Item Manager'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('demarsico_regionmanagerplus/adminhtml_regions_edit'))
                ->_addLeft($this->getLayout()->createBlock('demarsico_regionmanagerplus/adminhtml_regions_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('demarsico_regionmanagerplus')->__('Region does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        $request = $this->getRequest();

        if ($this->getRequest()->getPost()) {
            $id = $request->getParam('id');
            $code = $request->getParam('code');
            $name = $request->getParam('default_name');
            $enabled = $request->getParam('enabled');
            $countryId = $request->getParam('country_id');
            if (!$name || !$code) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please fill the required fields'));
                $this->_redirect('*/*/');
                return;
            }
            $region = Mage::getModel('demarsico_regionmanagerplus/region')->getCollection()
                ->addFieldToFilter('code', $code)
                ->addFieldToFilter('country_id', $countryId)
                ->getAllIds();
            if (count($region) > 0 && !in_array($id, $region)) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('State/Country combination must be unique'));
                $this->_redirect('*/*/edit', array('region_id' => $id));
                return;
            }

            try {
                $region = Mage::getModel('demarsico_regionmanagerplus/region');
                $region->setRegionId($id)
                    ->setCode($code)
                    ->setCountryId($countryId)
                    ->setDefaultName($name)
                    ->setEnabled($enabled)
                    ->save();
                if ($region->getRegionId()) {
                    $locales = Mage::helper('demarsico_regionmanagerplus')->getLocales();
                    $resource = Mage::getSingleton('core/resource');
                    $write = $resource->getConnection('core_write');
                    $regionName = $resource->getTableName('directory/country_region_name');
                    $write->delete($regionName, array('region_id =' . $region->getRegionId()));
                    foreach ($locales as $locale) {
                        $localeName = $request->getParam('name_' . $locale);
                        if ($localeName) {
                            $write->insert($regionName, array('region_id' => $region->getRegionId(), 'locale' => $locale, 'name' => trim($localeName)));
                        }
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->getStateData(false);
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setStateData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('region_id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $regionIds = $this->getRequest()->getParam('demarsico_regionmanagerplus');
        
        if (!is_array($regionIds)) {
            Mage::getSingleton('adminhtml/session')
                ->addError(Mage::helper('adminhtml')
                ->__('Please select region(s).'));
        } 
        else {
            try {
                $collection = Mage::getModel('directory/region')->getCollection()
                    ->addFieldToFilter('main_table.region_id', array('in' => $regionIds));;
                foreach ($collection as $region) {
                    $region->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were deleted.', count($regionIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');

    }

    public function massEnableAction()
    {
        $regionIds = $this->getRequest()->getParam('demarsico_regionmanagerplus');
        if (!is_array($regionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select region(s).'));
        } else {
            try {
                $region_model = Mage::getModel('directory/region');
                foreach ($regionIds as $regionId) {
                    $region = $region_model->load($regionId);
                    if(!$region->getEnabled()){
                        $region->setEnabled(1);
                        $region->save();
                    }                    
                }
                Mage::app()->getCacheInstance()->clean();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were enabled.', count($regionIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');

    }

    public function massDisableAction()
    {
        $regionIds = $this->getRequest()->getParam('demarsico_regionmanagerplus');
        if (!is_array($regionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select region(s).'));
        } else {
            try {
                $region_model = Mage::getModel('directory/region');
                foreach ($regionIds as $regionId) {
                    $region = $region_model->load($regionId);
                    if($region->getEnabled()){
                        $region->setEnabled(0);
                        $region->save();
                    }                    
                }
                Mage::app()->getCacheInstance()->clean();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were disabled.', count($regionIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');

    }

    public function getRegionsAction(){
        $layout = Mage::getSingleton('core/layout');

        $html = $layout
            ->createBlock('demarsico_regionmanagerplus/adminhtml_api_regions')
            ->setTemplate('demarsico_regionmanagerplus/getregions.phtml')
            ->toHtml();

        echo $html;
    }

    public function saveNameAction()
    {
        $request = $this->getRequest();
        $editorId = $request->getParam('editorId');
        $value = $request->getParam('value');
        if (!$editorId) {
            echo $this->__('Unable to Save.');
            return;
        }
        if (!$value) {
            echo $this->__('Value can not be empty.');
            return;
        }
        $model = Mage::getModel('demarsico_regionmanagerplus/region')->load($editorId);
        $model->setDefaultName(trim($value));
        try {
            $model->save();
        } catch (Exception $e) {
            echo $e->getCode() . '-' . $e->getMessage();
        }
        echo $model->getDefaultName();

    }

    public function saveCodeAction()
    {
        $request = $this->getRequest();
        $editorId = $request->getParam('editorId');
        $value = $request->getParam('value');
        if (!$editorId) {
            echo $this->__('Unable to Save.');
            return;
        }
        if (!$value) {
            echo $this->__('Value can not be empty.');
            return;
        }
        $row = Mage::getModel('demarsico_regionmanagerplus/region')->getCollection()
            ->addFieldToFilter('code', $value)
            ->setPageSize(1)
            ->getFirstItem();
        if (($row->getRegionId() == $editorId) && (trim($value) == $row->getCode())) {
            echo $row->getCode() . ' not updated';
            return;
        }
        if ($row->getRegionId()) {
            echo $this->__('Region code must be unique.');
            return;
        }

        $model = Mage::getModel('demarsico_regionmanagerplus/region')->load($editorId);
        $model->setCode(trim($value));
        try {
            $model->save();
        } catch (Exception $e) {
            echo $e->getCode() . '-' . $e->getMessage();
        }
        echo $model->getCode();
    }

    public function getAvailableCountriesAction(){
        $version = (string)Mage::helper('demarsico_regionmanagerplus')->getModuleVersion();        
        $url = Mage::getStoreConfig('demarsico/regionmanagerplus/api_url');
        $soap_options = array(
            'soap_version'   => SOAP_1_2,
            'trace'          => 1,
            'cache_wsdl' => WSDL_CACHE_NONE, 
            'exceptions'     => 0);
        try{
            $client = new SoapClient($url, $soap_options ); 
        }
        catch(Exception $e){
            echo $e->getCode() . '-' . $e->getMessage();
        }
        $results = $client->getCountries($version);
        echo json_encode($results);
    }

    public function getRegionsByConutryAction(){
        $version = Mage::helper('demarsico_regionmanagerplus')->getModuleVersion();
        $coutryCode = $this->getRequest()->getParam('country_code');
        $url = Mage::getStoreConfig('demarsico/regionmanagerplus/api_url');
        $soap_options = array(
            'soap_version'   => SOAP_1_2,
            'trace'          => 1,
            'cache_wsdl' => WSDL_CACHE_NONE, 
            'exceptions'     => 0);
        $client = new SoapClient($url, $soap_options ); 
        $results = $client->getRegions($version, $coutryCode);
        Mage::getSingleton('demarsico_regionmanagerplus/region')->addRegions($results->data);
        echo json_encode($results);
        Mage::getSingleton('core/session')->addSuccess('Regions added Successfully'); 
    }

    public function sendCommentAction(){
        $version = Mage::helper('demarsico_regionmanagerplus')->getModuleVersion();
        $comment = $this->getRequest()->getParam('feedback');
        $url = Mage::getStoreConfig('demarsico/regionmanagerplus/api_url');
        $soap_options = array(
            'soap_version'   => SOAP_1_2,
            'trace'          => 1,
            'cache_wsdl' => WSDL_CACHE_NONE, 
            'exceptions'     => 0);
        $client = new SoapClient($url, $soap_options ); 
        $results = $client->sendComment($version, $comment);
        echo json_encode($results);
    }

}