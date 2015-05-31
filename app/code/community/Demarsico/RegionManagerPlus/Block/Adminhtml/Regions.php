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

class Demarsico_RegionManagerPlus_Block_Adminhtml_Regions extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {    	
        $this->_controller = 'adminhtml_regions';
        $this->_blockGroup = 'demarsico_regionmanagerplus';
        $this->_headerText = Mage::helper('demarsico_regionmanagerplus')->__('Region Manager Plus');       
        parent::__construct();  
         $this->_addButton('get_regions', array(
            'label'     => 'Get Regions',
            'onclick'   => 'js:showPopup()',
            'class'     => 'add',
        ),1);      
    }
}