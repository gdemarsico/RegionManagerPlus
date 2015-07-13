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

class Demarsico_RegionManagerPlus_Block_Adminhtml_Regions_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $countries = Mage::getSingleton('directory/country')->getCollection()->loadData()->toOptionArray(false);
        $id = $this->getRequest()->getParam('region_id');



        $fieldSet = $form->addFieldset('demarsico_regionmanagerplus_form', array('legend' => Mage::helper('demarsico_regionmanagerplus')->__('State Information')));
        $fieldSet->addField(
            'country_id', 'select', array(
                                         'label'    => Mage::helper('demarsico_regionmanagerplus')->__('Country'),
                                         'name'     => 'country_id',
                                         'required' => true,
                                         'values'   => $countries
                                    )
        );

        $fieldSet->addField(
            'code', 'text', array(
                                 'label'    => Mage::helper('demarsico_regionmanagerplus')->__('Code'),
                                 'class'    => 'required-entry',
                                 'required' => true,
                                 'name'     => 'code',
                            )
        );
        $fieldSet->addField(
            'default_name', 'text', array(
                                         'label'    => Mage::helper('demarsico_regionmanagerplus')->__('Default Name'),
                                         'class'    => 'required-entry',
                                         'required' => true,
                                         'name'     => 'default_name',
                                    )
        );
        $fieldSet->addField(
            'enabled', 'select', array(
                                         'label'    => Mage::helper('demarsico_regionmanagerplus')->__('Enabled'),
                                         'class'    => 'required-entry',
                                         'required' => true,
                                         'name'     => 'enabled',
                                         'values'   => array('0' => Mage::helper('demarsico_regionmanagerplus')->__('No'),
                                                      '1' => Mage::helper('demarsico_regionmanagerplus')->__('Yes')),
                                    )
        );
        $locales = Mage::helper('demarsico_regionmanagerplus')->getLocales();        
        foreach ($locales as $locale) {
            $fieldSet{$locale} = $form->addFieldset('demarsico_regionmanagerplus_form_' . $locale, array('legend' => Mage::helper('demarsico_regionmanagerplus')->__('Locale ' . $locale)));
            $fieldSet{$locale}->addField(
                'name_'.$locale, 'text', array(
                                     'label' => Mage::helper('demarsico_regionmanagerplus')->__('Name'),
                                     'name'  => 'name_'.$locale,
                                )
            );
        }
        if (Mage::getSingleton('adminhtml/session')->getStateData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getStateData());
            Mage::getSingleton('adminhtml/session')->setStateData(null);
        } elseif (Mage::registry('region_data')) {
            $form->setValues(Mage::registry('region_data')->getData());
        }
        if($id){
            $resource = Mage::getSingleton('core/resource');
            $read = $resource->getConnection('core_read');
            $table = $resource->getTableName('directory/country_region_name');            
            foreach($locales as $key =>$value)
            {
                $query = 'SELECT name FROM ' . $table. ' WHERE region_id = '.$id.
                ' AND locale = ';
                $query .= '"'.$key.'" LIMIT 1';
                $name = $read->fetchOne($query);                
                $form->addValues(array('name_'.$key=> $name));
            }
        }
        return parent::_prepareForm();

    }
}
