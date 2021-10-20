<?php
/**
 * Copyright © Boostsales B.V. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Boostsales\ExtraCheckoutAddressFields\Block\Checkout;

use Boostsales\ExtraCheckoutAddressFields\Helper\Data;
use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Store\Model\StoreManagerInterface;

class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * Form element mapping
     *
     * @var array
     */
    private $formElementMap = [
        'text' => 'input',
        'hidden' => 'input',
        'boolean' => 'checkbox',
    ];
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    private $attributeRepository;

    protected $storeManager;

    protected $websiteId;

    /**
     * LayoutProcessor constructor.
     *
     * @param Data $helper
     * @param \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        Data $helper,
        \Magento\Eav\Api\AttributeRepositoryInterface $attributeRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->helper = $helper;
        $this->attributeRepository = $attributeRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param array $result
     * @return array
     */
    public function process($result)
    {
        $this->websiteId = $this->storeManager->getStore()->getWebsiteId();

            $result = $this->getShippingFormFields($result);
            $result = $this->getBillingFormFields($result);

        return $result;
    }

    /**
     * @param string $addressType
     * @return array
     */
    public function getAdditionalFields($addressType='shipping')
    {
        if ($addressType=='shipping') {
            return $this->helper->getExtraCheckoutAddressFields('extra_checkout_shipping_address_fields');
        }
        return  $this->helper->getExtraCheckoutAddressFields('extra_checkout_billing_address_fields');
    }

    /**
     * @param $result
     * @return mixed
     */
    public function getShippingFormFields($result)
    {
        if (isset($result['components']['checkout']['children']['steps']['children']
                ['shipping-step']['children']['shippingAddress']['children']
                ['shipping-address-fieldset'])
        ) {
            $shippingPostcodeFields = $this->getFields('shippingAddress.custom_attributes', 'shipping');

            $shippingFields = $result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'];

            if (isset($shippingFields['street'])) {
                unset($shippingFields['street']['children'][1]['validation']);
                unset($shippingFields['street']['children'][2]['validation']);
            }

            $shippingFields = array_replace_recursive($shippingFields, $shippingPostcodeFields);

            $result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'] = $shippingFields;
        }

        return $result;
    }

    /**
     * @param $result
     * @return mixed
     */
    public function getBillingFormFields($result)
    {
        if (isset($result['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list'])) {
            $paymentForms = $result['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list']['children'];

            foreach ($paymentForms as $paymentMethodForm => $paymentMethodValue) {
                $paymentMethodCode = str_replace('-form', '', $paymentMethodForm);

                if (!isset($result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form'])) {
                    continue;
                }

                $billingFields = $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'];

                $billingPostcodeFields = $this->getFields('billingAddress' . $paymentMethodCode . '.custom_attributes', 'billing');

                $billingFields = array_replace_recursive($billingFields, $billingPostcodeFields);

                $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'] = $billingFields;
            }
        }

        return $result;
    }

    /**
     * @param $scope
     * @param $addressType
     * @return array
     */
    public function getFields($scope, $addressType)
    {
        $fields = [];
        foreach ($this->getAdditionalFields($addressType) as $field) {
            $fields[$field] = $this->getField($field, $scope);
        }
        return $fields;
    }

    /**
     * @param $attributeCode
     * @param $scope
     * @return array
     */
    public function getField($attributeCode, $scope)
    {
        $attribute = $this->attributeRepository->get('customer_address', $attributeCode);
        $inputType = $attribute->getFrontendInput();
        if (isset($this->formElementMap[$inputType])) {
            $inputType = $this->formElementMap[$inputType];
        }
        if($this->websiteId == 3){
            $visible = true;
        }else {
            $visible = false;
        }
        return [
            'component' => 'Magento_Ui/js/form/element/select',
            'config' => [
                'customScope' => $scope,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select'
            ],
            'dataScope' => $scope . '.' . $attributeCode,
            'sortOrder' => $attribute->getSortOrder(),
            'visible' => $visible,
            'provider' => 'checkoutProvider',
            'validation' => $attribute->getValidationRules(),
            'options' => $attribute->getOptions(),
            'label' => __('Departments'),
            'options' => [
                [
                    ['label' => __('Select Department'), 'value' => ''],
                    ['label' => __('Logistics'), 'value' => 'logistics'],
                    ['label' => __('Healthcare'), 'value' => 'healthcare'],
                    ['label' => __('Automotive'), 'value' => 'automotive'],
                    ['label' => __('Industrial Automation'), 'value' => 'industrial automation'],
                    ['label' => __('Machine and System'), 'value' => 'machine and system'],
                    ['label' => __('Energy and Utilites'), 'value' => 'energy and utilities'],
                    ['label' => __('ICT BV'), 'value' => 'ict bv'],
                    ['label' => __('ICT NV'), 'value' => 'ict nv']
                ]
            ]
        ];
    }
}
