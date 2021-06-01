<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\List as ChildList;
use Model\ListQuery as ChildListQuery;
use Model\Map\ListTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'lists' table.
 *
 *
 *
 * @method     ChildListQuery orderById($order = Criteria::ASC) Order by the list_id column
 * @method     ChildListQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildListQuery orderBySiteId($order = Criteria::ASC) Order by the site_id column
 * @method     ChildListQuery orderByTitle($order = Criteria::ASC) Order by the list_title column
 * @method     ChildListQuery orderByUrl($order = Criteria::ASC) Order by the list_url column
 * @method     ChildListQuery orderByCreatedAt($order = Criteria::ASC) Order by the list_created column
 * @method     ChildListQuery orderByUpdatedAt($order = Criteria::ASC) Order by the list_updated column
 * @method     ChildListQuery orderByDeletedAt($order = Criteria::ASC) Order by the list_deleted column
 *
 * @method     ChildListQuery groupById() Group by the list_id column
 * @method     ChildListQuery groupByUserId() Group by the user_id column
 * @method     ChildListQuery groupBySiteId() Group by the site_id column
 * @method     ChildListQuery groupByTitle() Group by the list_title column
 * @method     ChildListQuery groupByUrl() Group by the list_url column
 * @method     ChildListQuery groupByCreatedAt() Group by the list_created column
 * @method     ChildListQuery groupByUpdatedAt() Group by the list_updated column
 * @method     ChildListQuery groupByDeletedAt() Group by the list_deleted column
 *
 * @method     ChildListQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildListQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildListQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildListQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildListQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildListQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildList|null findOne(ConnectionInterface $con = null) Return the first ChildList matching the query
 * @method     ChildList findOneOrCreate(ConnectionInterface $con = null) Return the first ChildList matching the query, or a new ChildList object populated from the query conditions when no match is found
 *
 * @method     ChildList|null findOneById(int $list_id) Return the first ChildList filtered by the list_id column
 * @method     ChildList|null findOneByUserId(int $user_id) Return the first ChildList filtered by the user_id column
 * @method     ChildList|null findOneBySiteId(int $site_id) Return the first ChildList filtered by the site_id column
 * @method     ChildList|null findOneByTitle(string $list_title) Return the first ChildList filtered by the list_title column
 * @method     ChildList|null findOneByUrl(string $list_url) Return the first ChildList filtered by the list_url column
 * @method     ChildList|null findOneByCreatedAt(string $list_created) Return the first ChildList filtered by the list_created column
 * @method     ChildList|null findOneByUpdatedAt(string $list_updated) Return the first ChildList filtered by the list_updated column
 * @method     ChildList|null findOneByDeletedAt(string $list_deleted) Return the first ChildList filtered by the list_deleted column *

 * @method     ChildList requirePk($key, ConnectionInterface $con = null) Return the ChildList by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildList requireOne(ConnectionInterface $con = null) Return the first ChildList matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildList requireOneById(int $list_id) Return the first ChildList filtered by the list_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildList requireOneByUserId(int $user_id) Return the first ChildList filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildList requireOneBySiteId(int $site_id) Return the first ChildList filtered by the site_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildList requireOneByTitle(string $list_title) Return the first ChildList filtered by the list_title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildList requireOneByUrl(string $list_url) Return the first ChildList filtered by the list_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildList requireOneByCreatedAt(string $list_created) Return the first ChildList filtered by the list_created column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildList requireOneByUpdatedAt(string $list_updated) Return the first ChildList filtered by the list_updated column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildList requireOneByDeletedAt(string $list_deleted) Return the first ChildList filtered by the list_deleted column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildList[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildList objects based on current ModelCriteria
 * @method     ChildList[]|ObjectCollection findById(int $list_id) Return ChildList objects filtered by the list_id column
 * @method     ChildList[]|ObjectCollection findByUserId(int $user_id) Return ChildList objects filtered by the user_id column
 * @method     ChildList[]|ObjectCollection findBySiteId(int $site_id) Return ChildList objects filtered by the site_id column
 * @method     ChildList[]|ObjectCollection findByTitle(string $list_title) Return ChildList objects filtered by the list_title column
 * @method     ChildList[]|ObjectCollection findByUrl(string $list_url) Return ChildList objects filtered by the list_url column
 * @method     ChildList[]|ObjectCollection findByCreatedAt(string $list_created) Return ChildList objects filtered by the list_created column
 * @method     ChildList[]|ObjectCollection findByUpdatedAt(string $list_updated) Return ChildList objects filtered by the list_updated column
 * @method     ChildList[]|ObjectCollection findByDeletedAt(string $list_deleted) Return ChildList objects filtered by the list_deleted column
 * @method     ChildList[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ListQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\ListQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Model\\List', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildListQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildListQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildListQuery) {
            return $criteria;
        }
        $query = new ChildListQuery();
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
     * @return ChildList|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ListTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ListTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildList A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT list_id, user_id, site_id, list_title, list_url, list_created, list_updated, list_deleted FROM lists WHERE list_id = :p0';
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
            /** @var ChildList $obj */
            $obj = new ChildList();
            $obj->hydrate($row);
            ListTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildList|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildListQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ListTableMap::COL_LIST_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildListQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ListTableMap::COL_LIST_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the list_id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE list_id = 1234
     * $query->filterById(array(12, 34)); // WHERE list_id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE list_id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildListQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ListTableMap::COL_LIST_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ListTableMap::COL_LIST_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListTableMap::COL_LIST_ID, $id, $comparison);
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
     * @return $this|ChildListQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(ListTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(ListTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the site_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySiteId(1234); // WHERE site_id = 1234
     * $query->filterBySiteId(array(12, 34)); // WHERE site_id IN (12, 34)
     * $query->filterBySiteId(array('min' => 12)); // WHERE site_id > 12
     * </code>
     *
     * @param     mixed $siteId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildListQuery The current query, for fluid interface
     */
    public function filterBySiteId($siteId = null, $comparison = null)
    {
        if (is_array($siteId)) {
            $useMinMax = false;
            if (isset($siteId['min'])) {
                $this->addUsingAlias(ListTableMap::COL_SITE_ID, $siteId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($siteId['max'])) {
                $this->addUsingAlias(ListTableMap::COL_SITE_ID, $siteId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListTableMap::COL_SITE_ID, $siteId, $comparison);
    }

    /**
     * Filter the query on the list_title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE list_title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE list_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildListQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListTableMap::COL_LIST_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the list_url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE list_url = 'fooValue'
     * $query->filterByUrl('%fooValue%', Criteria::LIKE); // WHERE list_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildListQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListTableMap::COL_LIST_URL, $url, $comparison);
    }

    /**
     * Filter the query on the list_created column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE list_created = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE list_created = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE list_created > '2011-03-13'
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
     * @return $this|ChildListQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ListTableMap::COL_LIST_CREATED, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ListTableMap::COL_LIST_CREATED, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListTableMap::COL_LIST_CREATED, $createdAt, $comparison);
    }

    /**
     * Filter the query on the list_updated column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE list_updated = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE list_updated = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE list_updated > '2011-03-13'
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
     * @return $this|ChildListQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ListTableMap::COL_LIST_UPDATED, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ListTableMap::COL_LIST_UPDATED, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListTableMap::COL_LIST_UPDATED, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the list_deleted column
     *
     * Example usage:
     * <code>
     * $query->filterByDeletedAt('2011-03-14'); // WHERE list_deleted = '2011-03-14'
     * $query->filterByDeletedAt('now'); // WHERE list_deleted = '2011-03-14'
     * $query->filterByDeletedAt(array('max' => 'yesterday')); // WHERE list_deleted > '2011-03-13'
     * </code>
     *
     * @param     mixed $deletedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildListQuery The current query, for fluid interface
     */
    public function filterByDeletedAt($deletedAt = null, $comparison = null)
    {
        if (is_array($deletedAt)) {
            $useMinMax = false;
            if (isset($deletedAt['min'])) {
                $this->addUsingAlias(ListTableMap::COL_LIST_DELETED, $deletedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($deletedAt['max'])) {
                $this->addUsingAlias(ListTableMap::COL_LIST_DELETED, $deletedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ListTableMap::COL_LIST_DELETED, $deletedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildList $list Object to remove from the list of results
     *
     * @return $this|ChildListQuery The current query, for fluid interface
     */
    public function prune($list = null)
    {
        if ($list) {
            $this->addUsingAlias(ListTableMap::COL_LIST_ID, $list->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the lists table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ListTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ListTableMap::clearInstancePool();
            ListTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ListTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ListTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ListTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ListTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildListQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ListTableMap::COL_LIST_UPDATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildListQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ListTableMap::COL_LIST_UPDATED);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildListQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ListTableMap::COL_LIST_UPDATED);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildListQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ListTableMap::COL_LIST_CREATED);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildListQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ListTableMap::COL_LIST_CREATED, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildListQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ListTableMap::COL_LIST_CREATED);
    }

} // ListQuery
