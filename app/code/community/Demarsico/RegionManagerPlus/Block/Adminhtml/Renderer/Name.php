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

class Demarsico_RegionManagerPlus_Block_Adminhtml_Renderer_Name extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    public function render(Varien_Object $row)
    {
        $html = '';
        $locales = Mage::helper('demarsico_regionmanagerplus')->getLocales();

        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $regionName = $resource->getTableName('directory/country_region_name');

        $select = $read->select()->from(array('region' => $regionName))->where('region.region_id=?', $row->getRegionId());
        $data = $read->fetchAll($select);
        foreach ($data as $row) {
            $arr[$row['locale']] = $row['name'];
        }
        foreach ($locales as $locale) {
            $name = isset($arr[$locale])?$arr[$locale]:false;
            if (!$name) {
                $name = 'EMPTY';
            }
            $html[] = '<span>' . $locale . '</span> => <span class="' . $locale . '_name">' . $name . '</span>';
        }
        $html = implode('<br />', $html);

        if ($html == '') {
            $html = '&nbsp;';
        }

        return $html;
    }
}