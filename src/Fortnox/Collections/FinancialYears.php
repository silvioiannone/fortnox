<?php

namespace SI\API\Fortnox\Collections;

use SI\API\Fortnox\AbstractResourceCollection;

/**
 * Class FinancialYears
 *
 * @package SI\API\Fortnox\Collections
 */
class FinancialYears extends AbstractResourceCollection
{
    /**
     * Fetch the financial years.
     * 
     * @inheritdoc
     */
    public function fetchDetails(callable $callback = null)
    {
        $financialYears = [];

        foreach($this->data as $financialYearData)
        {
            $financialYear = (new \SI\API\Fortnox\FinancialYears($this->httpClient))
                ->get($financialYearData['Id']);

            if($callback) $callback($financialYear);

            $financialYears[] = $financialYear;
        }

        return $financialYears;
    }
}