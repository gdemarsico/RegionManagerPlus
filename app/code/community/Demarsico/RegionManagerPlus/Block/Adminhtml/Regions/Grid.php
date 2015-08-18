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

class Demarsico_RegionManagerPlus_Block_Adminhtml_Regions_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('demarsico/regionmanagerplus/widget/grid.phtml');
        $this->setId('demarsico_regions_grid');
        $this->setDefaultSort('region_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {        
        $collection = Mage::getModel('demarsico_regionmanagerplus/region')->getCollection();
        $this->setCollection($collection);

        $this->setLocales(Mage::helper('demarsico_regionmanagerplus')->getLocales());
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'region_id', array(
                              'header' => Mage::helper('demarsico_regionmanagerplus')->__('ID'),
                              'align'  => 'left',
                              'width'  => '50px',
                              'index'  => 'region_id',
                              'column_css_class' => 'row_id'
                         )
        );

        $this->addColumn(
            'country_id', array(
                               'header' => Mage::helper('demarsico_regionmanagerplus')->__('Country Code'),
                               'align'  => 'left',
                               'width'  => '110px',
                               'index'  => 'country_id',
                               'type'   => 'country',
                          )
        );
        $this->addColumn(
            'code', array(
                         'header' => Mage::helper('demarsico_regionmanagerplus')->__('Region Code'),
                         'align' => 'left',
                         'width' => '110px',
                         'index' => 'code',
                         //'editable' =>true,
                         'column_css_class' => 'code_td'
                    )
        );
        $this->addColumn(
            'default_name', array(
                                 'header' => Mage::helper('demarsico_regionmanagerplus')->__('Default Name'),
                                 'align'  => 'left',
                                 'width'  => '200px',
                                 'index'  => 'default_name',
                                 //'editable' =>true,
                                 'column_css_class' => 'default_name'
                            )
        );
        $this->addColumn(
            'enabled', array(
                                 'header' => Mage::helper('demarsico_regionmanagerplus')->__('Enabled'),
                                 'align'  => 'left',
                                 'width'  => '50px',
                                 'index'  => 'enabled',
                                 'renderer'  => 'Demarsico_RegionManagerPlus_Block_Adminhtml_Renderer_Enabled',
                                 'type'      => 'options',
                                 'options'   => array('0' => Mage::helper('demarsico_regionmanagerplus')->__('No'),
                                                      '1' => Mage::helper('demarsico_regionmanagerplus')->__('Yes')),
                            )
        );
        $this->addColumn(
            'action',
            array(
                 'header'    => Mage::helper('demarsico_regionmanagerplus')->__('Action'),
                 'width'     => '50px',
                 'type'      => 'action',
                 'getter'    => 'getRegionId',
                 'actions'   => array(
                     array(
                         'caption' => Mage::helper('demarsico_regionmanagerplus')->__('Edit'),
                         'url'     => array('base' => '*/*/edit'),
                         'field'   => 'region_id'
                     )
                 ),
                 'filter'    => false,
                 'sortable'  => false,
                 'index'     => 'region_id',
                 'is_system' => true,
            )
        );
        $this->setAdditionalJavaScript($this->getScripts());
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return '';
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('region_id');
        $this->getMassactionBlock()->setUseSelectAll(false);
        $this->getMassactionBlock()->setFormFieldName('demarsico_regionmanagerplus');
        $this->getMassactionBlock()->addItem(
            'enable', array(
                           'label' => Mage::helper('demarsico_regionmanagerplus')->__('Enable'),
                           'url'   => $this->getUrl('*/*/massEnable', array('_current' => true)),
                      )
        );
        $this->getMassactionBlock()->addItem(
            'disable', array(
                           'label' => Mage::helper('demarsico_regionmanagerplus')->__('Disable'),
                           'url'   => $this->getUrl('*/*/massDisable', array('_current' => true)),
                      )
        );
        $this->getMassactionBlock()->addItem(
            'delete', array(
                           'label' => Mage::helper('demarsico_regionmanagerplus')->__('Delete'),
                           'url'   => $this->getUrl('*/*/massDelete', array('_current' => true)),
                      )
        );
        return $this;
    }

    public function getScripts()
    {
        $locales = Mage::helper('demarsico_regionmanagerplus')->getLocales();
        
        $url = $this->getUrl('*/*/getRegions');        
        $nameUrl = $this->getUrl('*/*/saveName');
        $codeUrl = $this->getUrl('*/*/saveCode');
        $saveLabel = Mage::helper('demarsico_regionmanagerplus')->__('Save');
        $cancelLabel = Mage::helper('demarsico_regionmanagerplus')->__('Cancel');
        $js
            = '
        function getNameUrl(e)
        {
            return "' . $nameUrl . 'region_id/"+getId(e);
        }
        function getCodeUrl(e)
        {
            return "' . $codeUrl . 'region_id/"+getId(e);
        }
        function getNameLocaleUrl(e,url)
        {
            return url+"region_id/"+getId(e);
        }
        function getId(e)
        {
            id = e.up("tr").down("td.row_id").innerHTML;
            return id.trim();
        }
        ';
        $js
            .= <<<EOF
        document.observe('dom:loaded', function() {
            $$('.default_name').each(function(el){
                if(el.down('span')){return ;}
                idx = getId(el);
                el.update('<span id='+idx+'>'+el.innerHTML.trim()+'</span>');
                new Ajax.InPlaceEditor(el.down('span'), getNameUrl(el),{formId:idx,okText: '$saveLabel',cancelText: '$cancelLabel'} );
            });
            $$('.code_td').each(function(el){
                if(el.down('span')){return ;}
                idx = getId(el);
                el.update('<span id='+idx+'>'+el.innerHTML.trim()+'</span>');
                new Ajax.InPlaceEditor(el.down('span'), getCodeUrl(el),{formId:idx,okText: '$saveLabel',cancelText: '$cancelLabel'} );
            });

EOF;
        foreach($locales as $locale)
        {
            $nameLocaleUrl = $this->getUrl('*/*/saveNameLocale',array('locale'=>$locale));
            $e_name = $locale.'_name';
            $js
                .= <<<EOF
                $$('.$e_name').each(function(el){
                idx = getId(el);
                el.update('<span id='+idx+'>'+el.innerHTML.trim()+'</span>');
                new Ajax.InPlaceEditor(el.down('span'), getNameLocaleUrl(el,'$nameLocaleUrl'),{formId:idx,okText: '$saveLabel',cancelText: '$cancelLabel'} );
            });

EOF;

        }
        $js .='});';
        
        return $js;

    }

}