<?php

namespace Boostsales\ExtraCheckoutAddressFields\Model\Source;

class Departments extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => __('Select Department'), 'value' => ''],
            ['label' => __('Logistics'), 'value' => 'logistics'],
            ['label' => __('Healthcare'), 'value' => 'healthcare'],
            ['label' => __('Automotive'), 'value' => 'automotive'],
            ['label' => __('Industrial Automation'), 'value' => 'industrial automation'],
            ['label' => __('Machine and System'), 'value' => 'machine and system'],
            ['label' => __('Energy and Utilites'), 'value' => 'energy and utilities'],
            ['label' => __('ICT BV'), 'value' => 'ict bv'],
            ['label' => __('ICT NV'), 'value' => 'ict nv']
        ];

        return $this->_options;
    }
}