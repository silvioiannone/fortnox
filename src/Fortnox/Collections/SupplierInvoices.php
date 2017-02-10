<?php

namespace SI\API\Fortnox\Collections;

use SI\API\Fortnox\AbstractResourceCollection;

/**
 * Class SupplierInvoices
 *
 * @package SI\API\Fortnox\Collections
 */
class SupplierInvoices extends AbstractResourceCollection
{
    /**
     * Fetch the invoices.
     * 
     * @inheritdoc
     */
    public function fetchDetails(callable $callback = null)
    {
        $supplierInvoices = [];

        foreach($this->data as $invoiceData)
        {
            $supplierInvoice = (new \SI\API\Fortnox\SupplierInvoices($this->httpClient))
                ->get($invoiceData['GivenNumber']);

            if($callback) $callback($supplierInvoice);

            $supplierInvoices[] = $supplierInvoice;
        }

        return $supplierInvoices;
    }
}