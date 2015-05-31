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

class Demarsico_RegionManagerPlus_Block_Adminhtml_Renderer_Enabled extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 
	public function render(Varien_Object $row)
	{
		$value =  (bool)$row->getData($this->getColumn()->getIndex());
		return ($value)?
		'<span style="color:green;">'.Mage::helper('demarsico_regionmanagerplus')->__('Yes').'</span>':
		'<span style="color:red;">'.Mage::helper('demarsico_regionmanagerplus')->__('No').'</span>';	 
	}
 
}
