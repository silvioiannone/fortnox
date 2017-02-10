<?php

namespace SI\API\Fortnox;

use SI\API\Fortnox;
use Carbon\Carbon;

/**
 * Class FinancialYears
 *
 * Financial years resource.
 *
 * @package SI\API\Fortnox
 */
class FinancialYears extends AbstractResource
{
    /**
     * Return all the financial years.
     *
     * @inheritdoc
     *
     * @return Fortnox\Collections\FinancialYears
     */
    public function fetch($options = [])
    {
        return parent::fetch($options);
    }

    /**
     * @inheritdoc
     *
     * Dev note: in this case this class needs to override the parent class
     *           since it's not possible to search using 'lastmodified' but
     *           instead we need to use 'date'.
     */
    public function from($datetime)
    {
        if(!$datetime) return $this;
        
        $this->parameters['date'] = Carbon::parse($datetime)->toDateString();

        return $this;
    }
}