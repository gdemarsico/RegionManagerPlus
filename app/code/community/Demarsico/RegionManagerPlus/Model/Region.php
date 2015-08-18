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

 class Demarsico_RegionManagerPlus_Model_Region extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('demarsico_regionmanagerplus/region');
    }

    public function addRegions($regions){
    	foreach ($regions as $region) {
    		$new_region = Mage::getModel('demarsico_regionmanagerplus/region');
    		$new_region->setCountryId($region->country_id);
    		$new_region->setCode($region->code);
    		$new_region->setDefaultName($region->default_name);
            $new_region->setEnabled(1);
    		$new_region->save();
    	}

    }
}