<?php

namespace SI\API\Fortnox\Collections;

use SI\API\Fortnox\AbstractResourceCollection;

/**
 * Class Accounts
 *
 * @package SI\API\Fortnox\Collections
 */
class Accounts extends AbstractResourceCollection
{
    /**
     * Fetch the invoices.
     * 
     * @inheritdoc
     */
    public function fetchDetails(callable $callback = null)
    {
        $accounts = [];

        foreach($this->data as $accountData)
        {
            $account = (new \SI\API\Fortnox\Accounts($this->httpClient))
                ->get($accountData['Number']);

            if($callback) $callback();

            $accounts[] = $account;
        }

        return $accounts;
    }
}