<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\TemplateService;
use Framework\Controller;
use Model\FileQuery;
use Model\ImageQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MaintenanceController extends Controller
{
    public function infosAction(): JsonResponse
    {
        return new JsonResponse([
            'version' => BIBLYS_VERSION,
        ]);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws PropelException
     */
    public function diskUsageAction(
        CurrentUser     $currentUser,
        CurrentSite     $currentSite,
        TemplateService $templateService,
    ): Response
    {
        $currentUser->authAdmin();

        $publisherFilter = $currentSite->getOption("publisher_filter");

        $articlesQuery = ImageQuery::create()
            ->filterByType("cover")
            ->withColumn('COUNT(`id`)', 'count')
            ->withColumn('SUM(`fileSize`)', 'size')
            ->select(['count', 'size']);

        if ($publisherFilter) {
            $allowedPublisherIds = explode(",", $publisherFilter);
            $articlesQuery = $articlesQuery
                ->joinWithArticle()
                ->useArticleQuery()
                    ->filterByPublisherId($allowedPublisherIds)
                ->endUse();
        }

        $articles = $articlesQuery->find()->getData()[0];

        $downloadableFiles = ["count" => 0, "size" => 0];
        if ($publisherFilter) {
            $downloadableFiles = FileQuery::create()
                ->withColumn('COUNT(`file_id`)', 'count')
                ->withColumn('SUM(`file_size`)', 'size')
                ->select(['count', 'size'])
                ->joinWithArticle()
                ->useArticleQuery()
                    ->filterByPublisherId($allowedPublisherIds)
                ->endUse()
                ->find()
                ->getData()[0];
        }

        $stockItems = ImageQuery::create()
            ->filterByType("photo")
            ->filterBySite($currentSite->getSite())
            ->withColumn('COUNT(`id`)', 'count')
            ->withColumn('SUM(`fileSize`)', 'size')
            ->select(['count', 'size'])
            ->find()
            ->getData()[0];

        $totalCount = $articles["count"] + $downloadableFiles["count"] + $stockItems["count"];
        $totalSize = $articles["size"] + $downloadableFiles["size"] + $stockItems["size"];

        return $templateService->renderResponse("AppBundle:Maintenance:disk-usage.html.twig", [
            "articlesCount" => $articles["count"],
            "articlesSize" => $this->_convertToGigabytes($articles["size"]),
            "stockItemsCount" => $stockItems["count"],
            "stockItemsSize" => $this->_convertToGigabytes($stockItems["size"]),
            "downloadableFilesCount" => $downloadableFiles["count"],
            "downloadableFilesSize" => $this->_convertToGigabytes($downloadableFiles["size"]),
            "totalCount" => $totalCount,
            "totalSize" => $this->_convertToGigabytes($totalSize),
        ]);
    }

    private function _convertToGigabytes(mixed $bytes): float
    {
        return number_format($bytes / 1073741824, 3);
    }
}
