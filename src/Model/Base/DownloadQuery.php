<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Download as ChildDownload;
use Model\DownloadQuery as ChildDownloadQuery;
use Model\Map\DownloadTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'downloads' table.
 *
 *
 *
 * @method     ChildDownloadQuery orderById($order = Criteria::ASC) Order by the download_id column
 * @method     ChildDownloadQuery orderByFileId($order = Criteria::ASC) Order by the file_id column
 * @method     ChildDownloadQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 * @method     ChildDownloadQuery orderByBookId($order = Criteria::ASC) Order by the book_id column
 * @method     ChildDownloadQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildDownloadQuery orderByFiletype($order = Criteria::ASC) Order by the download_filetype column
 * @method     ChildDownloadQuery orderByVersion($order = Criteria::ASC) Order by the download_version column
 * @method     ChildDownloadQuery orderByIp($order = Criteria::ASC) Order by the download_ip column
 * @method     ChildDownloadQuery orderByDate($order = Criteria::ASC) Order by the download_date column
 * @method     ChildDownloadQuery orderByCreatedAt($order = Criteria::ASC) Order by the download_created column
 * @method     ChildDownloadQuery orderByUpdatedAt($order = Criteria::ASC) Order by the download_updated column
 *
 * @method     ChildDownloadQuery groupById() Group by the download_id column
 * @method     ChildDownloadQuery groupByFileId() Group by the file_id column
 * @method     ChildDownloadQuery groupByArticleId() Group by the article_id column
 * @method     ChildDownloadQuery groupByBookId() Group by the book_id column
 * @method     ChildDownloadQuery groupByUserId() Group by the user_id column
 * @method     ChildDownloadQuery groupByFiletype() Group by the download_filetype column
 * @method     ChildDownloadQuery groupByVersion() Group by the download_version column
 * @method     ChildDownloadQuery groupByIp() Group by the download_ip column
 * @method     ChildDownloadQuery groupByDate() Group by the download_date column
 * @method     ChildDownloadQuery groupByCreatedAt() Group by the download_created column
 * @method     ChildDownloadQuery groupByUpdatedAt() Group by the download_updated column
 *
 * @method     ChildDownloadQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDownloadQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDownloadQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDownloadQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDownloadQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDownloadQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDownload|null findOne(ConnectionInterface $con = null) Return the first ChildDownload matching the query
 * @method     ChildDownload findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDownload matching the query, or a new ChildDownload object populated from the query conditions when no match is found
 *
 * @method     ChildDownload|null findOneById(string $download_id) Return the first ChildDownload filtered by the download_id column
 * @method     ChildDownload|null findOneByFileId(int $file_id) Return the first ChildDownload filtered by the file_id column
 * @method     ChildDownload|null findOneByArticleId(int $article_id) Return the first ChildDownload filtered by the article_id column
 * @method     ChildDownload|null findOneByBookId(int $book_id) Return the first ChildDownload filtered by the book_id column
 * @method     ChildDownload|null findOneByUserId(int $user_id) Return the first ChildDownload filtered by the user_id column
 * @method     ChildDownload|null findOneByFiletype(string $download_filetype) Return the first ChildDownload filtered by the download_filetype column
 * @method     ChildDownload|null findOneByVersion(string $download_version) Return the first ChildDownload filtered by the download_version column
 * @method     ChildDownload|null findOneByIp(string $download_ip) Return the first ChildDownload filtered by the download_ip column
 * @method     ChildDownload|null findOneByDate(string $download_date) Return the first ChildDownload filtered by the download_date column
 * @method     ChildDownload|null findOneByCreatedAt(string $download_created) Return the first ChildDownload filtered by the download_created column
 * @method     ChildDownload|null findOneByUpdatedAt(string $download_updated) Return the first ChildDownload filtered by the download_updated column *

 * @method     ChildDownload requirePk($key, ConnectionInterface $con = null) Return the ChildDownload by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDownload requireOne(ConnectionInterface $con = null) Return the first ChildDownload matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDownload requireOneById(string $download_id) Return the first ChildDownload filtered by the download_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDownload requireOneByFileId(int $file_id) Return the first ChildDownload filtered by the file_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDownload requireOneByArticleId(int $article_id) Return the first ChildDownload filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDownload requireOneByBookId(int $book_id) Return the first ChildDownload filtered by the book_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDownload requireOneByUserId(int $user_id) Return the first ChildDownload filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDownload requireOneByFiletype(string $download_filetype) Return the first ChildDownload filtered by the download_filetype column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDownload requireOneByVersion(string $download_version) Return the first ChildDownload filtered by the download_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDownload requireOneByIp(string $download_ip) Return the first ChildDownload filtered by the download_ip column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDownload requireOneByDate(string $download_date) Return the first ChildDownload filtered by the download_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDownload requireOneByCreatedAt(string $download_created) Return the first ChildDownload filtered by the download_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDownload requireOneByUpdatedAt(string $download_updated) Return the first ChildDownload filtered by the download_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDownload[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDownload objects based on current ModelCriteria
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> find(ConnectionInterface $con = null) Return ChildDownload objects based on current ModelCriteria
 * @method     ChildDownload[]|ObjectCollection findById(string $download_id) Return ChildDownload objects filtered by the download_id column
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> findById(string $download_id) Return ChildDownload objects filtered by the download_id column
 * @method     ChildDownload[]|ObjectCollection findByFileId(int $file_id) Return ChildDownload objects filtered by the file_id column
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> findByFileId(int $file_id) Return ChildDownload objects filtered by the file_id column
 * @method     ChildDownload[]|ObjectCollection findByArticleId(int $article_id) Return ChildDownload objects filtered by the article_id column
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> findByArticleId(int $article_id) Return ChildDownload objects filtered by the article_id column
 * @method     ChildDownload[]|ObjectCollection findByBookId(int $book_id) Return ChildDownload objects filtered by the book_id column
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> findByBookId(int $book_id) Return ChildDownload objects filtered by the book_id column
 * @method     ChildDownload[]|ObjectCollection findByUserId(int $user_id) Return ChildDownload objects filtered by the user_id column
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> findByUserId(int $user_id) Return ChildDownload objects filtered by the user_id column
 * @method     ChildDownload[]|ObjectCollection findByFiletype(string $download_filetype) Return ChildDownload objects filtered by the download_filetype column
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> findByFiletype(string $download_filetype) Return ChildDownload objects filtered by the download_filetype column
 * @method     ChildDownload[]|ObjectCollection findByVersion(string $download_version) Return ChildDownload objects filtered by the download_version column
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> findByVersion(string $download_version) Return ChildDownload objects filtered by the download_version column
 * @method     ChildDownload[]|ObjectCollection findByIp(string $download_ip) Return ChildDownload objects filtered by the download_ip column
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> findByIp(string $download_ip) Return ChildDownload objects filtered by the download_ip column
 * @method     ChildDownload[]|ObjectCollection findByDate(string $download_date) Return ChildDownload objects filtered by the download_date column
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> findByDate(string $download_date) Return ChildDownload objects filtered by the download_date column
 * @method     ChildDownload[]|ObjectCollection findByCreatedAt(string $download_created) Return ChildDownload objects filtered by the download_created column
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> findByCreatedAt(string $download_created) Return ChildDownload objects filtered by the download_created column
 * @method     ChildDownload[]|ObjectCollection findByUpdatedAt(string $download_updated) Return ChildDownload objects filtered by the download_updated column
 * @psalm-method ObjectCollection&\Traversable<ChildDownload> findByUpdatedAt(string $download_updated) Return ChildDownload objects filtered by the download_updated column
 * @method     ChildDownload[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildDownload> paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DownloadQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\DownloadQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\Download', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDownloadQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildDownloadQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDownloadQuery) {
            return $criteria;
        }
        $query = new ChildDownloadQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildDownload|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DownloadTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = DownloadTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDownload A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT download_id, file_id, article_id, book_id, user_id, download_filetype, download_version, download_ip, download_date, download_created, download_updated FROM downloads WHERE download_id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildDownload $obj */
            $obj = new ChildDownload();
            $obj->hydrate($row);
            DownloadTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildDownload|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the download_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE download_id = 1234
     * $query->filterById(array(12, 34)); // WHERE download_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE download_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_ID, $id, $comparison);
    }

    /**
     * Filter the query on the file_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFileId(1234); // WHERE file_id = 1234
     * $query->filterByFileId(array(12, 34)); // WHERE file_id IN (12, 34)
     * $query->filterByFileId(array('min' => 12)); // WHERE file_id > 12
     * </code>
     *
     * @param     mixed $fileId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByFileId($fileId = null, $comparison = null)
    {
        if (is_array($fileId)) {
            $useMinMax = false;
            if (isset($fileId['min'])) {
                $this->addUsingAlias(DownloadTableMap::COL_FILE_ID, $fileId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileId['max'])) {
                $this->addUsingAlias(DownloadTableMap::COL_FILE_ID, $fileId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DownloadTableMap::COL_FILE_ID, $fileId, $comparison);
    }

    /**
     * Filter the query on the article_id column
     *
     * Example usage:
     * <code>
     * $query->filterByArticleId(1234); // WHERE article_id = 1234
     * $query->filterByArticleId(array(12, 34)); // WHERE article_id IN (12, 34)
     * $query->filterByArticleId(array('min' => 12)); // WHERE article_id > 12
     * </code>
     *
     * @param     mixed $articleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByArticleId($articleId = null, $comparison = null)
    {
        if (is_array($articleId)) {
            $useMinMax = false;
            if (isset($articleId['min'])) {
                $this->addUsingAlias(DownloadTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(DownloadTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DownloadTableMap::COL_ARTICLE_ID, $articleId, $comparison);
    }

    /**
     * Filter the query on the book_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBookId(1234); // WHERE book_id = 1234
     * $query->filterByBookId(array(12, 34)); // WHERE book_id IN (12, 34)
     * $query->filterByBookId(array('min' => 12)); // WHERE book_id > 12
     * </code>
     *
     * @param     mixed $bookId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByBookId($bookId = null, $comparison = null)
    {
        if (is_array($bookId)) {
            $useMinMax = false;
            if (isset($bookId['min'])) {
                $this->addUsingAlias(DownloadTableMap::COL_BOOK_ID, $bookId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookId['max'])) {
                $this->addUsingAlias(DownloadTableMap::COL_BOOK_ID, $bookId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DownloadTableMap::COL_BOOK_ID, $bookId, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(DownloadTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(DownloadTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DownloadTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the download_filetype column
     *
     * Example usage:
     * <code>
     * $query->filterByFiletype('fooValue');   // WHERE download_filetype = 'fooValue'
     * $query->filterByFiletype('%fooValue%', Criteria::LIKE); // WHERE download_filetype LIKE '%fooValue%'
     * </code>
     *
     * @param     string $filetype The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByFiletype($filetype = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($filetype)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_FILETYPE, $filetype, $comparison);
    }

    /**
     * Filter the query on the download_version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion('fooValue');   // WHERE download_version = 'fooValue'
     * $query->filterByVersion('%fooValue%', Criteria::LIKE); // WHERE download_version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $version The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($version)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_VERSION, $version, $comparison);
    }

    /**
     * Filter the query on the download_ip column
     *
     * Example usage:
     * <code>
     * $query->filterByIp('fooValue');   // WHERE download_ip = 'fooValue'
     * $query->filterByIp('%fooValue%', Criteria::LIKE); // WHERE download_ip LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ip The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByIp($ip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ip)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_IP, $ip, $comparison);
    }

    /**
     * Filter the query on the download_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE download_date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE download_date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE download_date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the download_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE download_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE download_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE download_created > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the download_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE download_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE download_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE download_updated > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDownload $download Object to remove from the list of results
     *
     * @return $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function prune($download = null)
    {
        if ($download) {
            $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_ID, $download->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the downloads table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DownloadTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DownloadTableMap::clearInstancePool();
            DownloadTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DownloadTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DownloadTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DownloadTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DownloadTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(DownloadTableMap::COL_DOWNLOAD_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(DownloadTableMap::COL_DOWNLOAD_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(DownloadTableMap::COL_DOWNLOAD_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(DownloadTableMap::COL_DOWNLOAD_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildDownloadQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(DownloadTableMap::COL_DOWNLOAD_CREATED);
    }

} // DownloadQuery
