<?php

namespace SI\API\Fortnox\Collections;

use SI\API\Fortnox\AbstractResourceCollection;

/**
 * Class Articles
 *
 * @package SI\API\Fortnox\Collections
 */
class Articles extends AbstractResourceCollection
{
    /**
     * Fetch the articles.
     * 
     * @inheritdoc
     */
    public function fetchDetails(callable $callback = null)
    {
        $articles = [];

        foreach($this->data as $accountData)
        {
            $article = (new \SI\API\Fortnox\Articles($this->httpClient))
                ->get($accountData['ArticleNumber']);

            if($callback) $callback();

            $articles[] = $article;
        }

        return $articles;
    }
}