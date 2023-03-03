<?php

namespace Biblys\Service;

use Biblys\Exception\GleephAPIException;
use Biblys\Gleeph\GleephAPI;
use Model\Article;
use Model\ArticleQuery;
use Propel\Runtime\Exception\PropelException;
use Psr\Http\Client\ClientExceptionInterface;

class GleephService
{
    private GleephAPI $api;
    private CurrentSite $currentSiteService;

    public function __construct(GleephAPI $api, CurrentSite $currentSiteService)
    {
        $this->api = $api;
        $this->currentSiteService = $currentSiteService;
    }

    /**
     * @return Article[]
     * @throws ClientExceptionInterface
     * @throws PropelException
     */
    public function getSimilarArticlesByEan(string $ean, int $numberOfSuggestions = 3): array
    {
        try {
            $eans = $this->api->getSimilarBooksByEan($ean);
        } catch (GleephAPIException) {
            return [];
        }

        return ArticleQuery::create()
            ->filterForCurrentSite($this->currentSiteService)
            ->findByEan($eans)
            ->getData();
    }
}