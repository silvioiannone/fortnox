<?php

namespace SI\API\Fortnox;

use SI\API\Fortnox;

/**
 * Class Accounts
 *
 * Vouchers resource.
 *
 * @package SI\API\Fortnox
 */
class Accounts extends AbstractResource
{
    /**
     * Return all the invoices.
     *
     * @inheritdoc
     *
     * @return Fortnox\Collections\Invoices
     */
    public function fetch($options = [])
    {
        return parent::fetch($options);
    }
}