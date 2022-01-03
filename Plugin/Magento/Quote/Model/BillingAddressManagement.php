<?php
/**
 * Copyright © Boostsales B.V. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Boostsales\ExtraCheckoutAddressFields\Plugin\Magento\Quote\Model;

use Magento\Quote\Api\Data\AddressInterface;
use Psr\Log\LoggerInterface;

class BillingAddressManagement
{
    /**
     * @var \Boostsales\ExtraCheckoutAddressFields\Helper\Data
     */
    protected $helper;

    protected $logger;

    /**
     * BillingAddressManagement constructor.
     *
     * @param \Boostsales\ExtraCheckoutAddressFields\Helper\Data $helper
     */
    public function __construct(
        \Boostsales\ExtraCheckoutAddressFields\Helper\Data $helper,
        LoggerInterface $logger
    ) {
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Quote\Model\BillingAddressManagement $subject
     * @param $cartId
     * @param AddressInterface $address
     * @param false $useForShipping
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeAssign(
        \Magento\Quote\Model\BillingAddressManagement $subject,
        $cartId,
        AddressInterface $address,
        $useForShipping = false
    ) {
        $extAttributes = $address->getExtensionAttributes();
        $this->logger->info("extension value billing:",["value" => $extAttributes->getDepartment()]);
        $this->logger->info("extension value billng:",["value" => $extAttributes->getDeptExtraInfo()]);
        $this->logger->info("extension value billng:",["value" => $extAttributes->getInvoiceEmail()]);
        if (!empty($extAttributes)) {
            try{
                $address->setDepartment($extAttributes->getDepartment());
                $address->setDeptExtraInfo($extAttributes->getDeptExtraInfo());
                $address->setInvoiceEmail($extAttributes->getInvoiceEmail());
            }catch(\Exception $e){
                $this->logger->critical($e->getMessage());
            }
        }
    }
}
