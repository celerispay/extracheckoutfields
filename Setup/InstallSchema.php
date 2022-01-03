<?php
/**
 * A Magento 2 module named Boostsales/ExtraCheckoutAddressFieldsTest
 * Copyright (C) 2019  Boostsales
 *
 * This file is part of Boostsales/ExtraCheckoutAddressFieldsTest.
 *
 * Boostsales/ExtraCheckoutAddressFieldsTest is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Boostsales\ExtraCheckoutAddressFields\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->getConnection()->addColumn(
            $setup->getTable('quote_address'),
            'department',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Department'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('quote_address'),
            'dept_extra_info',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Extra department info'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('quote_address'),
            'invoice_email',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Invoice email'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_address'),
            'department',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Department'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_address'),
            'dept_extra_info',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Extra department info'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('sales_order_address'),
            'invoice_email',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Invoice email'
            ]
        );

    }
}