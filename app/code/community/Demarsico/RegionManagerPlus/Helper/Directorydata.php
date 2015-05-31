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

 class Demarsico_RegionManagerPlus_Helper_Directorydata extends Mage_Directory_Helper_Data {

    /**
     * Get Regions for specific Countries
     * @param string $storeId
     * @return array|null
     */
    protected function _getRegions($storeId)
    {
        $countryIds = array();

        $countryCollection = $this->getCountryCollection()->loadByStore($storeId);
        foreach ($countryCollection as $country) {
            $countryIds[] = $country->getCountryId();
        }

        /** @var $regionModel Mage_Directory_Model_Region */
        $regionModel = $this->_factory->getModel('directory/region');
        /** @var $collection Mage_Directory_Model_Resource_Region_Collection */
        $collection = $regionModel->getResourceCollection()
            ->addCountryFilter($countryIds)
            ->addFieldToFilter('enabled',1)
            ->load();

        $regions = array(
            'config' => array(
                'show_all_regions' => $this->getShowNonRequiredState(),
                'regions_required' => $this->getCountriesWithStatesRequired()
            )
        );
        foreach ($collection as $region) {
            if (!$region->getRegionId()) {
                continue;
            }
            $regions[$region->getCountryId()][$region->getRegionId()] = array(
                'code' => $region->getCode(),
                'name' => $this->__($region->getName())
            );
        }
        return $regions;
    }

 }