<?php

namespace ApiBundle\Controller;

use Biblys\Contributor\Contributor;
use Biblys\Contributor\Job;
use Biblys\Contributor\UnknownJobException;
use Framework\Controller;
use Framework\Exception\AuthException;
use Model\ArticleQuery;
use Model\PublisherQuery;
use Model\Role;
use Model\RoleQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContributionController extends Controller
{
    /**
     * @route GET /api/admin/articles/{articleId}/contributions
     * @throws AuthException
     * @throws PropelException
     * @throws UnknownJobException
     */
    public function index(Request $request, $articleId): JsonResponse
    {
        $article = ArticleQuery::create()->findPk($articleId);
        $articlePublisher = PublisherQuery::create()->findPk($article->getPublisherId());
        $contributions = $article->getRolesJoinPeople();

        self::authPublisher($request, $articlePublisher);

        $contributors = array_map(function(Role $contribution) {
            $contributor = new Contributor(
                $contribution->getPeople(),
                Job::getById($contribution->getJobId()),
                $contribution->getId()
            );

            $gender = $contribution->getPeople()->getGender();
            $jobsForGender = $this->_getJobsForGender($gender);

            return [
                "contribution_id" => $contributor->getContributionId(),
                "contributor_name" => $contributor->getName(),
                "contributor_role" => $contributor->getRole(),
                "contributor_job_id" => $contributor->getJobId(),
                "job_options" => $jobsForGender,
            ];
        }, $contributions->getData());


        return new JsonResponse([
            "contributors" => $contributors,
        ]);
    }

    /**
     * @route POST /api/admin/articles/{articleId}/contributions
     * @throws AuthException
     * @throws PropelException
     * @throws UnknownJobException
     */
    public function create(Request $request, int $articleId): JsonResponse
    {
        $article = ArticleQuery::create()->findPk($articleId);
        $articlePublisher = PublisherQuery::create()->findPk($article->getPublisherId());
        self::authPublisher($request, $articlePublisher);

        $encodedContent = $request->getContent();
        $params = json_decode($encodedContent, true);

        $contribution = new Role();
        $contribution->setArticleId($articleId);
        $contribution->setPeopleId($params["people_id"]);
        $contribution->setJobId($params["job_id"]);
        $contribution->save();

        $contributor = new Contributor(
            $contribution->getPeople(),
            Job::getById($contribution->getJobId()),
            $contribution->getId()
        );

        $gender = $contribution->getPeople()->getGender();
        $jobsForGender = $this->_getJobsForGender($gender);

        $authorNamesAsString = $this->_getAuthorNamesAsString($contribution);
        return new JsonResponse([
            "contributor" => [
                "contribution_id" => $contributor->getContributionId(),
                "contributor_name" => $contributor->getName(),
                "contributor_role" => $contributor->getRole(),
                "contributor_job_id" => $contributor->getJobId(),
                "job_options" => $jobsForGender,
            ],
            "authors" => $authorNamesAsString
        ]);
    }

    /**
     * @route PUT /api/admin/articles/{articleId}/contributions/{id}
     * @throws PropelException
     * @throws AuthException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $encodedContent = $request->getContent();
        $params = json_decode($encodedContent, true);
        $jobId = $params["job_id"];

        $contribution = RoleQuery::create()->findPk($id);

        $articlePublisher = PublisherQuery::create()->findPk($contribution->getArticle()->getPublisherId());
        self::authPublisher($request, $articlePublisher);

        $contribution->setJobId($jobId);
        $contribution->save();

        $authorNamesAsString = $this->_getAuthorNamesAsString($contribution);
        return new JsonResponse(["authors" => $authorNamesAsString]);
    }

    /**
     * @route DELETE /api/admin/articles/{articleId}/contributions/{id}
     * @throws PropelException
     * @throws AuthException
     */
    public function delete(Request $request, int $id): JsonResponse
    {
        $contribution = RoleQuery::create()->findPk($id);

        $articlePublisher = PublisherQuery::create()->findPk($contribution->getArticle()->getPublisherId());
        self::authPublisher($request, $articlePublisher);

        $contribution->delete();

        $authorNamesAsString = $this->_getAuthorNamesAsString($contribution);
        return new JsonResponse(["authors" => $authorNamesAsString]);
    }

    /**
     * @param Role $contribution
     * @return string
     * @throws PropelException
     */
    private function _getAuthorNamesAsString(Role $contribution): string
    {
        $article = $contribution->getArticle();
        $contributionsByAuthors = RoleQuery::create()
            ->filterByArticle($article)
            ->filterByJobId(1)
            ->find();
        $authorNames = array_map(function (Role $contribution) {
            return $contribution->getPeople()->getName();
        }, $contributionsByAuthors->getData());

        return join(", ", $authorNames);
    }

    /**
     * @param string|null $gender
     * @return array|array[]
     */
    private function _getJobsForGender(?string $gender): array
    {
        $jobs = Job::getAll();
        return array_map(function (Job $job) use ($gender) {

            if ($gender === "F") {
                $jobName = $job->getFeminineName();
            } elseif ($gender === "M") {
                $jobName = $job->getMasculineName();
            } else {
                $jobName = $job->getNeutralName();
            }

            return [
                "job_id" => $job->getId(),
                "job_name" => $jobName,
            ];
        }, $jobs);
    }
}
