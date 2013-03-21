<?php

namespace TNE\OperatorBundle\QueryService;

use Vich\GeographicalBundle\QueryService\QueryServiceInterface;
use Vich\GeographicalBundle\QueryService\QueryResult;

/**
 * GoogleQueryService.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class MockQueryService implements QueryServiceInterface
{
    /**
     * Queries google for the coordinates.
     * 
     * @param QueryResult $query The query
     */
    public function queryCoordinates($query)
    {
        $result = new QueryResult();
        $result->setLatitude($query[0]);
        $result->setLongitude($query[1]);

        
        return $result;
    }
}