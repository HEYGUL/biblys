<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\File as ChildFile;
use Model\FileQuery as ChildFileQuery;
use Model\Map\FileTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'files' table.
 *
 *
 *
 * @method     ChildFileQuery orderById($order = Criteria::ASC) Order by the file_id column
 * @method     ChildFileQuery orderByArticleId($order = Criteria::ASC) Order by the article_id column
 * @method     ChildFileQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildFileQuery orderByTitle($order = Criteria::ASC) Order by the file_title column
 * @method     ChildFileQuery orderByType($order = Criteria::ASC) Order by the file_type column
 * @method     ChildFileQuery orderByAccess($order = Criteria::ASC) Order by the file_access column
 * @method     ChildFileQuery orderByVersion($order = Criteria::ASC) Order by the file_version column
 * @method     ChildFileQuery orderByHash($order = Criteria::ASC) Order by the file_hash column
 * @method     ChildFileQuery orderBySize($order = Criteria::ASC) Order by the file_size column
 * @method     ChildFileQuery orderByEan($order = Criteria::ASC) Order by the file_ean column
 * @method     ChildFileQuery orderByInserted($order = Criteria::ASC) Order by the file_inserted column
 * @method     ChildFileQuery orderByUploaded($order = Criteria::ASC) Order by the file_uploaded column
 * @method     ChildFileQuery orderByUpdatedAt($order = Criteria::ASC) Order by the file_updated column
 * @method     ChildFileQuery orderByCreatedAt($order = Criteria::ASC) Order by the file_created column
 *
 * @method     ChildFileQuery groupById() Group by the file_id column
 * @method     ChildFileQuery groupByArticleId() Group by the article_id column
 * @method     ChildFileQuery groupByUserId() Group by the user_id column
 * @method     ChildFileQuery groupByTitle() Group by the file_title column
 * @method     ChildFileQuery groupByType() Group by the file_type column
 * @method     ChildFileQuery groupByAccess() Group by the file_access column
 * @method     ChildFileQuery groupByVersion() Group by the file_version column
 * @method     ChildFileQuery groupByHash() Group by the file_hash column
 * @method     ChildFileQuery groupBySize() Group by the file_size column
 * @method     ChildFileQuery groupByEan() Group by the file_ean column
 * @method     ChildFileQuery groupByInserted() Group by the file_inserted column
 * @method     ChildFileQuery groupByUploaded() Group by the file_uploaded column
 * @method     ChildFileQuery groupByUpdatedAt() Group by the file_updated column
 * @method     ChildFileQuery groupByCreatedAt() Group by the file_created column
 *
 * @method     ChildFileQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFileQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFileQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFileQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildFileQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildFileQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildFile|null findOne(?ConnectionInterface $con = null) Return the first ChildFile matching the query
 * @method     ChildFile findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildFile matching the query, or a new ChildFile object populated from the query conditions when no match is found
 *
 * @method     ChildFile|null findOneById(int $file_id) Return the first ChildFile filtered by the file_id column
 * @method     ChildFile|null findOneByArticleId(int $article_id) Return the first ChildFile filtered by the article_id column
 * @method     ChildFile|null findOneByUserId(int $user_id) Return the first ChildFile filtered by the user_id column
 * @method     ChildFile|null findOneByTitle(string $file_title) Return the first ChildFile filtered by the file_title column
 * @method     ChildFile|null findOneByType(string $file_type) Return the first ChildFile filtered by the file_type column
 * @method     ChildFile|null findOneByAccess(boolean $file_access) Return the first ChildFile filtered by the file_access column
 * @method     ChildFile|null findOneByVersion(string $file_version) Return the first ChildFile filtered by the file_version column
 * @method     ChildFile|null findOneByHash(string $file_hash) Return the first ChildFile filtered by the file_hash column
 * @method     ChildFile|null findOneBySize(string $file_size) Return the first ChildFile filtered by the file_size column
 * @method     ChildFile|null findOneByEan(string $file_ean) Return the first ChildFile filtered by the file_ean column
 * @method     ChildFile|null findOneByInserted(string $file_inserted) Return the first ChildFile filtered by the file_inserted column
 * @method     ChildFile|null findOneByUploaded(string $file_uploaded) Return the first ChildFile filtered by the file_uploaded column
 * @method     ChildFile|null findOneByUpdatedAt(string $file_updated) Return the first ChildFile filtered by the file_updated column
 * @method     ChildFile|null findOneByCreatedAt(string $file_created) Return the first ChildFile filtered by the file_created column *

 * @method     ChildFile requirePk($key, ?ConnectionInterface $con = null) Return the ChildFile by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOne(?ConnectionInterface $con = null) Return the first ChildFile matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFile requireOneById(int $file_id) Return the first ChildFile filtered by the file_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByArticleId(int $article_id) Return the first ChildFile filtered by the article_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByUserId(int $user_id) Return the first ChildFile filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByTitle(string $file_title) Return the first ChildFile filtered by the file_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByType(string $file_type) Return the first ChildFile filtered by the file_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByAccess(boolean $file_access) Return the first ChildFile filtered by the file_access column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByVersion(string $file_version) Return the first ChildFile filtered by the file_version column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByHash(string $file_hash) Return the first ChildFile filtered by the file_hash column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneBySize(string $file_size) Return the first ChildFile filtered by the file_size column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByEan(string $file_ean) Return the first ChildFile filtered by the file_ean column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByInserted(string $file_inserted) Return the first ChildFile filtered by the file_inserted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByUploaded(string $file_uploaded) Return the first ChildFile filtered by the file_uploaded column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByUpdatedAt(string $file_updated) Return the first ChildFile filtered by the file_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFile requireOneByCreatedAt(string $file_created) Return the first ChildFile filtered by the file_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFile[]|Collection find(?ConnectionInterface $con = null) Return ChildFile objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildFile> find(?ConnectionInterface $con = null) Return ChildFile objects based on current ModelCriteria
 * @method     ChildFile[]|Collection findById(int $file_id) Return ChildFile objects filtered by the file_id column
 * @psalm-method Collection&\Traversable<ChildFile> findById(int $file_id) Return ChildFile objects filtered by the file_id column
 * @method     ChildFile[]|Collection findByArticleId(int $article_id) Return ChildFile objects filtered by the article_id column
 * @psalm-method Collection&\Traversable<ChildFile> findByArticleId(int $article_id) Return ChildFile objects filtered by the article_id column
 * @method     ChildFile[]|Collection findByUserId(int $user_id) Return ChildFile objects filtered by the user_id column
 * @psalm-method Collection&\Traversable<ChildFile> findByUserId(int $user_id) Return ChildFile objects filtered by the user_id column
 * @method     ChildFile[]|Collection findByTitle(string $file_title) Return ChildFile objects filtered by the file_title column
 * @psalm-method Collection&\Traversable<ChildFile> findByTitle(string $file_title) Return ChildFile objects filtered by the file_title column
 * @method     ChildFile[]|Collection findByType(string $file_type) Return ChildFile objects filtered by the file_type column
 * @psalm-method Collection&\Traversable<ChildFile> findByType(string $file_type) Return ChildFile objects filtered by the file_type column
 * @method     ChildFile[]|Collection findByAccess(boolean $file_access) Return ChildFile objects filtered by the file_access column
 * @psalm-method Collection&\Traversable<ChildFile> findByAccess(boolean $file_access) Return ChildFile objects filtered by the file_access column
 * @method     ChildFile[]|Collection findByVersion(string $file_version) Return ChildFile objects filtered by the file_version column
 * @psalm-method Collection&\Traversable<ChildFile> findByVersion(string $file_version) Return ChildFile objects filtered by the file_version column
 * @method     ChildFile[]|Collection findByHash(string $file_hash) Return ChildFile objects filtered by the file_hash column
 * @psalm-method Collection&\Traversable<ChildFile> findByHash(string $file_hash) Return ChildFile objects filtered by the file_hash column
 * @method     ChildFile[]|Collection findBySize(string $file_size) Return ChildFile objects filtered by the file_size column
 * @psalm-method Collection&\Traversable<ChildFile> findBySize(string $file_size) Return ChildFile objects filtered by the file_size column
 * @method     ChildFile[]|Collection findByEan(string $file_ean) Return ChildFile objects filtered by the file_ean column
 * @psalm-method Collection&\Traversable<ChildFile> findByEan(string $file_ean) Return ChildFile objects filtered by the file_ean column
 * @method     ChildFile[]|Collection findByInserted(string $file_inserted) Return ChildFile objects filtered by the file_inserted column
 * @psalm-method Collection&\Traversable<ChildFile> findByInserted(string $file_inserted) Return ChildFile objects filtered by the file_inserted column
 * @method     ChildFile[]|Collection findByUploaded(string $file_uploaded) Return ChildFile objects filtered by the file_uploaded column
 * @psalm-method Collection&\Traversable<ChildFile> findByUploaded(string $file_uploaded) Return ChildFile objects filtered by the file_uploaded column
 * @method     ChildFile[]|Collection findByUpdatedAt(string $file_updated) Return ChildFile objects filtered by the file_updated column
 * @psalm-method Collection&\Traversable<ChildFile> findByUpdatedAt(string $file_updated) Return ChildFile objects filtered by the file_updated column
 * @method     ChildFile[]|Collection findByCreatedAt(string $file_created) Return ChildFile objects filtered by the file_created column
 * @psalm-method Collection&\Traversable<ChildFile> findByCreatedAt(string $file_created) Return ChildFile objects filtered by the file_created column
 * @method     ChildFile[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildFile> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class FileQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\FileQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\File', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFileQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFileQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildFileQuery) {
            return $criteria;
        }
        $query = new ChildFileQuery();
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
     * @return ChildFile|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FileTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = FileTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildFile A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT file_id, article_id, user_id, file_title, file_type, file_access, file_version, file_hash, file_size, file_ean, file_inserted, file_uploaded, file_updated, file_created FROM files WHERE file_id = :p0';
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
            /** @var ChildFile $obj */
            $obj = new ChildFile();
            $obj->hydrate($row);
            FileTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildFile|array|mixed the result, formatted by the current formatter
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
     * @param array $keys Primary keys to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        $this->addUsingAlias(FileTableMap::COL_FILE_ID, $key, Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array|int $keys The list of primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        $this->addUsingAlias(FileTableMap::COL_FILE_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the file_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE file_id = 1234
     * $query->filterById(array(12, 34)); // WHERE file_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE file_id > 12
     * </code>
     *
     * @param mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterById($id = null, ?string $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_ID, $id, $comparison);

        return $this;
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
     * @param mixed $articleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByArticleId($articleId = null, ?string $comparison = null)
    {
        if (is_array($articleId)) {
            $useMinMax = false;
            if (isset($articleId['min'])) {
                $this->addUsingAlias(FileTableMap::COL_ARTICLE_ID, $articleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($articleId['max'])) {
                $this->addUsingAlias(FileTableMap::COL_ARTICLE_ID, $articleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_ARTICLE_ID, $articleId, $comparison);

        return $this;
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
     * @param mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUserId($userId = null, ?string $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(FileTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(FileTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_USER_ID, $userId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE file_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE file_title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE file_title IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $title The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitle($title = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE file_type = 'fooValue'
     * $query->filterByType('%fooValue%', Criteria::LIKE); // WHERE file_type LIKE '%fooValue%'
     * $query->filterByType(['foo', 'bar']); // WHERE file_type IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $type The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByType($type = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_TYPE, $type, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_access column
     *
     * Example usage:
     * <code>
     * $query->filterByAccess(true); // WHERE file_access = true
     * $query->filterByAccess('yes'); // WHERE file_access = true
     * </code>
     *
     * @param bool|string $access The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByAccess($access = null, ?string $comparison = null)
    {
        if (is_string($access)) {
            $access = in_array(strtolower($access), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_ACCESS, $access, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion('fooValue');   // WHERE file_version = 'fooValue'
     * $query->filterByVersion('%fooValue%', Criteria::LIKE); // WHERE file_version LIKE '%fooValue%'
     * $query->filterByVersion(['foo', 'bar']); // WHERE file_version IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $version The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByVersion($version = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($version)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_VERSION, $version, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_hash column
     *
     * Example usage:
     * <code>
     * $query->filterByHash('fooValue');   // WHERE file_hash = 'fooValue'
     * $query->filterByHash('%fooValue%', Criteria::LIKE); // WHERE file_hash LIKE '%fooValue%'
     * $query->filterByHash(['foo', 'bar']); // WHERE file_hash IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $hash The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByHash($hash = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($hash)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_HASH, $hash, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_size column
     *
     * Example usage:
     * <code>
     * $query->filterBySize(1234); // WHERE file_size = 1234
     * $query->filterBySize(array(12, 34)); // WHERE file_size IN (12, 34)
     * $query->filterBySize(array('min' => 12)); // WHERE file_size > 12
     * </code>
     *
     * @param mixed $size The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySize($size = null, ?string $comparison = null)
    {
        if (is_array($size)) {
            $useMinMax = false;
            if (isset($size['min'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_SIZE, $size['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($size['max'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_SIZE, $size['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_SIZE, $size, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_ean column
     *
     * Example usage:
     * <code>
     * $query->filterByEan(1234); // WHERE file_ean = 1234
     * $query->filterByEan(array(12, 34)); // WHERE file_ean IN (12, 34)
     * $query->filterByEan(array('min' => 12)); // WHERE file_ean > 12
     * </code>
     *
     * @param mixed $ean The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEan($ean = null, ?string $comparison = null)
    {
        if (is_array($ean)) {
            $useMinMax = false;
            if (isset($ean['min'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_EAN, $ean['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ean['max'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_EAN, $ean['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_EAN, $ean, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_inserted column
     *
     * Example usage:
     * <code>
     * $query->filterByInserted('2011-03-14'); // WHERE file_inserted = '2011-03-14'
     * $query->filterByInserted('now'); // WHERE file_inserted = '2011-03-14'
     * $query->filterByInserted(array('max' => 'yesterday')); // WHERE file_inserted > '2011-03-13'
     * </code>
     *
     * @param mixed $inserted The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByInserted($inserted = null, ?string $comparison = null)
    {
        if (is_array($inserted)) {
            $useMinMax = false;
            if (isset($inserted['min'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_INSERTED, $inserted['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($inserted['max'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_INSERTED, $inserted['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_INSERTED, $inserted, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_uploaded column
     *
     * Example usage:
     * <code>
     * $query->filterByUploaded('2011-03-14'); // WHERE file_uploaded = '2011-03-14'
     * $query->filterByUploaded('now'); // WHERE file_uploaded = '2011-03-14'
     * $query->filterByUploaded(array('max' => 'yesterday')); // WHERE file_uploaded > '2011-03-13'
     * </code>
     *
     * @param mixed $uploaded The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUploaded($uploaded = null, ?string $comparison = null)
    {
        if (is_array($uploaded)) {
            $useMinMax = false;
            if (isset($uploaded['min'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_UPLOADED, $uploaded['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($uploaded['max'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_UPLOADED, $uploaded['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_UPLOADED, $uploaded, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE file_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE file_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE file_updated > '2011-03-13'
     * </code>
     *
     * @param mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, ?string $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_UPDATED, $updatedAt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the file_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE file_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE file_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE file_created > '2011-03-13'
     * </code>
     *
     * @param mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, ?string $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FileTableMap::COL_FILE_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FileTableMap::COL_FILE_CREATED, $createdAt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildFile $file Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($file = null)
    {
        if ($file) {
            $this->addUsingAlias(FileTableMap::COL_FILE_ID, $file->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the files table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FileTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FileTableMap::clearInstancePool();
            FileTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FileTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FileTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FileTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FileTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param int $nbDays Maximum age of the latest update in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        $this->addUsingAlias(FileTableMap::COL_FILE_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by update date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        $this->addDescendingOrderByColumn(FileTableMap::COL_FILE_UPDATED);

        return $this;
    }

    /**
     * Order by update date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        $this->addAscendingOrderByColumn(FileTableMap::COL_FILE_UPDATED);

        return $this;
    }

    /**
     * Order by create date desc
     *
     * @return $this The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        $this->addDescendingOrderByColumn(FileTableMap::COL_FILE_CREATED);

        return $this;
    }

    /**
     * Filter by the latest created
     *
     * @param int $nbDays Maximum age of in days
     *
     * @return $this The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        $this->addUsingAlias(FileTableMap::COL_FILE_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);

        return $this;
    }

    /**
     * Order by create date asc
     *
     * @return $this The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        $this->addAscendingOrderByColumn(FileTableMap::COL_FILE_CREATED);

        return $this;
    }

}
