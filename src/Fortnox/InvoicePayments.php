<?php

namespace SI\API\Fortnox;

use SI\API\Fortnox;

/**
 * Class InvoicePayments
 *
 * Invoice payments resource.
 *
 * @package SI\API\Fortnox
 */
class InvoicePayments extends AbstractResource
{
    /**
     * Return all the invoices.
     *
     * @inheritdoc
     *
     * @return Fortnox\Collections\InvoicePayments
     */
    public function fetch($options = [])
    {
        return parent::fetch($options);
    }
}