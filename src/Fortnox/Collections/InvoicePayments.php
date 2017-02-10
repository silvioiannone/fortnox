<?php

namespace SI\API\Fortnox\Collections;

use SI\API\Fortnox\AbstractResourceCollection;

/**
 * Class InvoicePayments
 *
 * @package SI\API\Fortnox\Collections
 */
class InvoicePayments extends AbstractResourceCollection
{
    /**
     * Fetch the invoices.
     * 
     * @inheritdoc
     */
    public function fetchDetails(callable $callback = null)
    {
        $invoicePayments = [];

        foreach($this->data as $invoicePaymentData)
        {
            $invoicePayment = (new \SI\API\Fortnox\InvoicePayments($this->httpClient))
                ->get($invoicePaymentData['Number']);

            if($callback) $callback($invoicePayment);

            $invoicePayments[] = $invoicePayment;
        }

        return $invoicePayments;
    }
}