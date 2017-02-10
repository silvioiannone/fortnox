<?php

namespace SI\API\Fortnox;

use SI\API\Fortnox;

/**
 * Class SupplierInvoicePayments
 *
 * Supplier invoices resource.
 *
 * @package SI\API\Fortnox
 */
class SupplierInvoicePayments extends AbstractResource
{
    /**
     * Return all the invoices.
     *
     * @inheritdoc
     *
     * @return Fortnox\Collections\SupplierInvoicePayments
     */
    public function fetch($options = [])
    {
        return parent::fetch($options);
    }
}