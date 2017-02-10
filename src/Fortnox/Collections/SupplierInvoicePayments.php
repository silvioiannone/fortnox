<?php

namespace SI\API\Fortnox\Collections;

use SI\API\Fortnox\AbstractResourceCollection;

/**
 * Class SupplierInvoicePayments
 *
 * @package SI\API\Fortnox\Collections
 */
class SupplierInvoicePayments extends AbstractResourceCollection
{
    /**
     * Fetch the invoices.
     * 
     * @inheritdoc
     */
    public function fetchDetails(callable $callback = null)
    {
        $supplierInvoicePayments = [];

        foreach($this->data as $invoiceData)
        {
            $supplierInvoicePayment = (new \SI\API\Fortnox\SupplierInvoicePayments($this->httpClient))
                ->get($invoiceData['Number']);

            if($callback) $callback($supplierInvoicePayment);

            $supplierInvoicePayments[] = $supplierInvoicePayment;
        }

        return $supplierInvoicePayments;
    }
}