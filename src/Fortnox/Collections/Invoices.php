<?php

namespace SI\API\Fortnox\Collections;

use SI\API\Fortnox\AbstractResourceCollection;

/**
 * Class Invoices
 *
 * @package SI\API\Fortnox\Collections
 */
class Invoices extends AbstractResourceCollection
{
    /**
     * Fetch the invoices.
     * 
     * @inheritdoc
     */
    public function fetchDetails(callable $callback = null)
    {
        $invoices = [];

        foreach($this->data as $invoiceData)
        {
            $invoice = (new \SI\API\Fortnox\Invoices($this->httpClient))
                ->get($invoiceData['DocumentNumber']);

            if($callback) $callback($invoice);

            $invoices[] = $invoice;
        }

        return $invoices;
    }
}