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

class Demarsico_RegionManagerPlus_Block_Adminhtml_Api_Regions extends Mage_Adminhtml_Block_Widget_Form {
 
    protected function _prepareForm() {
 
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save'),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
                )
        );
 
        $base_fieldset = $form->addFieldset(
                'base', array(
            	'legend' => Mage::helper('demarsico_regionmanagerplus')->__('Test data'),
                )
        );
 
 
        $base_fieldset->addField(
                'authorize_btn', 'button', array(
            'name' => 'authorize_btn',
            'label' => Mage::helper('demarsico_regionmanagerplus')->__(
                    'Click on folowing link to test popup Dialog:'
            ),
            'value' => $this->helper('demarsico_regionmanagerplus')->__('Test popup dialog >>'),
            'class' => 'form-button',
            'onclick' => 'javascript:openMyPopup()'
                )
        );
 
        $form->setUseContainer(true);
        $this->setForm($form);
 
        parent::_prepareForm();
    }
 
}