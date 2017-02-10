<?php

namespace SI\API\Fortnox;

use SI\API\Fortnox;

/**
 * Class articles
 *
 * Articles resource.
 *
 * @package SI\API\Fortnox
 */
class Articles extends AbstractResource
{
    /**
     * Return all the articles.
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