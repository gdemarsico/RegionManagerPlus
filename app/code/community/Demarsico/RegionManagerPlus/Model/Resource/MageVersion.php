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

 if(version_compare(Mage::getVersion(), '1.5.0.1', '<=')){
 	class Demarsico_RegionManagerPlus_Model_Resource_MageVersion extends Mage_Core_Model_Mysql4_Abstract{

	 	protected function _construct()
	    {
	        $this->_init('demarsico_regionmanagerplus/region', 'region_id');
	    }
 	}
 }
 else{
	class Demarsico_RegionManagerPlus_Model_Resource_MageVersion extends Mage_Core_Model_Resource_Db_Abstract{

		protected function _construct()
	    {
	        $this->_init('demarsico_regionmanagerplus/region', 'region_id');
	    }
	}
}