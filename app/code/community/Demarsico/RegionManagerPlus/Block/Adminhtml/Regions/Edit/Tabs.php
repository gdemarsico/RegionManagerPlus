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

class Demarsico_RegionManagerPlus_Block_Adminhtml_Regions_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('demarsico_regionmanagerplus_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('demarsico_regionmanagerplus')->__('State Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('demarsico_regionmanagerplus')->__('State Information'),
            'title'     => Mage::helper('demarsico_regionmanagerplus')->__('State Information'),
            'content'   => $this->getLayout()->createBlock('demarsico_regionmanagerplus/adminhtml_regions_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}