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

 class Demarsico_RegionManagerPlus_Helper_Data extends Mage_Core_Helper_Abstract {
    
    public function getLocales()
    {
        $stores = Mage::app()->getStores();
        $locales = array();
        foreach ($stores as $store) {
            $v = Mage::getStoreConfig('general/locale/code', $store->getId());
            $locales[$v] = $v;
        }
        return $locales;
    }

    public function getModuleVersion(){
        return Mage::getConfig()->getModuleConfig('Demarsico_RegionManagerPlus')->version;
    }
}