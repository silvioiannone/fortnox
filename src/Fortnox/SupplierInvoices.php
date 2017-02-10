<?php

namespace SI\API\Fortnox;

use SI\API\Fortnox;

/**
 * Class SupplierInvoices
 *
 * Supplier invoices resource.
 *
 * @package SI\API\Fortnox
 */
class SupplierInvoices extends AbstractResource
{
    /**
     * Return all the invoices.
     *
     * @inheritdoc
     *
     * @return Fortnox\Collections\SupplierInvoices
     */
    public function fetch($options = [])
    {
        return parent::fetch($options);
    }
}