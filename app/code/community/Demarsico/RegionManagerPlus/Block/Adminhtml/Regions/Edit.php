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

class Demarsico_RegionManagerPlus_Block_Adminhtml_Regions_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'region_id';
        $this->_blockGroup = 'demarsico_regionmanagerplus';
        $this->_controller = 'adminhtml_regions';
        $this->_updateButton('save', 'label', Mage::helper('demarsico_regionmanagerplus')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('demarsico_regionmanagerplus')->__('Delete Item'));

    }

    public function getHeaderText()
    {
        if (Mage::registry('region_data') && Mage::registry('region_data')->getRegionId()) {
            return Mage::helper('demarsico_regionmanagerplus')->__("Edit Item '%s'", $this->escapeHtml(Mage::registry('region_data')->getRegionId()));
        } else {
            return Mage::helper('demarsico_regionmanagerplus')->__('Add Item');
        }
    }
}
