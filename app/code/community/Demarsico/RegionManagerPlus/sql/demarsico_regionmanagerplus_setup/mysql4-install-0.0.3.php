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

$table = $this->getTable('demarsico_regionmanagerplus/region');

$installer->getConnection()->query(
    "ALTER TABLE  " . $table . "  ADD  enabled TINYINT NOT NULL DEFAULT  1 COMMENT 'Enabled'"
    );
 
$installer->endSetup();