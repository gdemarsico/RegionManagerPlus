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

 class Demarsico_RegionManagerPlus_Model_Resource_Region_Collection extends  Mage_Core_Model_Resource_Db_Collection_Abstract{
    
    protected function _construct()
    {
        $this->_init('demarsico_regionmanagerplus/region');
    }
}