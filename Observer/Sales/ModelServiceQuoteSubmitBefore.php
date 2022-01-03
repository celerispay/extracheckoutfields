<?php
/**
 * Copyright © Boostsales B.V. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Boostsales\ExtraCheckoutAddressFields\Observer\Sales;

use Boostsales\ExtraCheckoutAddressFields\Helper\Data;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepository;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;

class ModelServiceQuoteSubmitBefore implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var QuoteRepository
     */
    protected $quoteRepository;

    /**
     * ModelServiceQuoteSubmitBefore constructor.
     *
     * @param QuoteRepository $quoteRepository
     * @param LoggerInterface $logger
     * @param Data $helper
     */
    public function __construct(
        QuoteRepository $quoteRepository,
        LoggerInterface $logger,
        Data $helper
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {

        /** @var Order $order */
        $order = $observer->getOrder();
        $quote = $this->quoteRepository->get($order->getQuoteId());

        $billDepartment = $quote->getBillingAddress()->getDepartment();
        $shipDepartment = $quote->getShippingAddress()->getDepartment();
        $billDeptExtra = $quote->getBillingAddress()->getDeptExtraInfo();
        $shipDeptExtra = $quote->getShippingAddress()->getDeptExtraInfo();
        $shipInvoiceEmail = $quote->getShippingAddress()->getInvoiceEmail();
        $billInvoiceEmail = $quote->getBillingAddress()->getInvoiceEmail();

        $this->logger->info("extension attribute is:",["shipping" => $shipDepartment,"billing" => $billDepartment]);
        $this->logger->info("extension attribute is:",["shipping" => $shipDeptExtra, "billing" => $billDeptExtra]);
        $this->logger->info("extension attribute is:",["shipping" => $shipInvoiceEmail, "billing" => $billInvoiceEmail]);

        try {
            $order->getBillingAddress()->setDepartment($billDepartment);
            $order->getShippingAddress()->setDepartment($shipDepartment);
            $order->getBillingAddress()->setDeptExtraInfo($billDeptExtra);
            $order->getShippingAddress()->setDeptExtraInfo($shipDeptExtra);
            $order->getShippingAddress()->setInvoiceEmail($shipInvoiceEmail);
            $order->getBillingAddress()->setInvoiceEmail($billInvoiceEmail);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
