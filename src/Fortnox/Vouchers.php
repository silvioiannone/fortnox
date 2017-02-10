<?php

namespace SI\API\Fortnox;

use SI\API\AbstractFortnox;
use SI\API\Fortnox;

/**
 * Class Vouchers
 *
 * Vouchers resource.
 *
 * @package SI\API\Fortnox
 */
class Vouchers extends AbstractResource
{
    /**
     * Get a single voucher.
     *
     * @param string $voucherId VoucherSeries + VoucherNumber: "A1".
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get($voucherId)
    {
        $matches = [];
        preg_match_all('/([A-ZÖÄÅ]+)(\d+)/', $voucherId, $matches);

        $voucherSeries = $matches[1][0];
        $voucherNumber = $matches[2][0];

        return json_decode($this->httpClient->get(
            AbstractFortnox::API_BASE_URL.'/' .
            $this->getResourceName().'/' .
            $voucherSeries . '/'. $voucherNumber .
            $this->buildURIParametersString()
        )
        ->getBody()
        ->getContents());
    }

    /**
     * Return all the vouchers.
     *
     * @inheritdoc
     */
    public function fetch()
    {
        return parent::fetch();
    }
}