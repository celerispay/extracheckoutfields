<?php
/**
 * Copyright © Boostsales B.V. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Boostsales\ExtraCheckoutAddressFields\Plugin\Magento\Quote\Model;

use Boostsales\ExtraCheckoutAddressFields\Helper\Data;
use Exception;
use Magento\Quote\Api\Data\AddressInterface;
use Psr\Log\LoggerInterface;

class ShippingAddressManagement
{
    /**
     * @var Data
     */
    protected $helper;

    protected $logger;

    /**
     * ShippingAddressManagement constructor.
     *
     * @param Data $helper
     */
    public function __construct(
        Data $helper,LoggerInterface $logger
    ) {
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @param \Magento\Quote\Model\ShippingAddressManagement $subject
     * @param $cartId
     * @param AddressInterface $address
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeAssign(
        \Magento\Quote\Model\ShippingAddressManagement $subject,
        $cartId,
        AddressInterface $address
    ) {
        $extAttributes = $address->getExtensionAttributes();
        $this->logger->info("extension value shipping:",["value" => $extAttributes->getDepartment()]);
        $this->logger->info("extension value shipping:",["value" => $extAttributes->getDeptExtraInfo()]);
        $this->logger->info("extension value shipping:",["value" => $extAttributes->getInvoiceEmail()]);
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
