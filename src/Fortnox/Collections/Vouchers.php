<?php

namespace SI\API\Fortnox\Collections;

use SI\API\Fortnox\AbstractResourceCollection;

/**
 * Class VouchersCollection
 *
 * Vouchers collection.
 *
 * @package SI\API\Fortnox\Vouchers
 */
class Vouchers extends AbstractResourceCollection
{
    /**
     * Fetch vouchers details.
     *
     * @inheritdoc
     */
    public function fetchDetails(callable $callback = null)
    {
        $vouchers = [];

        foreach($this->data as $voucherData)
        {
            $voucher = (new \SI\API\Fortnox\Vouchers($this->httpClient))
                ->includeParameters([
                    'financialyear' => $voucherData['Year']
                ])
                ->get($voucherData['VoucherSeries'] . $voucherData['VoucherNumber']);

            if($callback) $callback($voucher);

            $vouchers[] = $voucher;
        }

        return $vouchers;
    }
}