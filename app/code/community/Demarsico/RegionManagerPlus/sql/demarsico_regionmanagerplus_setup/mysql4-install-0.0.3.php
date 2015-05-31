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

$installer = $this;
 
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('demarsico_regionmanagerplus/region'),
    'enabled',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'nullable' => false,
        'default' => 1,
        'comment' => 'Enabled'
    )
);
 
$installer->endSetup();