<?php

namespace Neo4j\OGM\OGMBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

/**
 * Class Neo4jQueryDataCollector
 *
 * @author Maximilian Ruta <mr@xtain.net>
 * @package Neo4j\OGM\OGMBundle\DataCollector
 */
class Neo4jQueryDataCollector implements DataCollectorInterface
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var Neo4jQueryDataProvider
     */
    protected $provider;

    /**
     * @param Neo4jQueryDataProvider $provider
     */
    public function __construct(Neo4jQueryDataProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request $request A Request instance
     * @param Response $response A Response instance
     * @param \Exception $exception An Exception instance
     *
     * @api
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array(
            'queries' => $this->provider->getQueries(),
            'time' => $this->provider->getTime(),
            'count' => $this->provider->getCount()
        );
    }

    /**
     * @return string
     *
     * @author Maximilian Ruta <mr@xtain.net>
     */
    public function getQueries()
    {
        return $this->data['queries'];
    }

    /**
     * @return int
     *
     * @author Maximilian Ruta <mr@xtain.net>
     */
    public function getCount()
    {
        return $this->data['count'];
    }

    /**
     * @return float
     *
     * @author Maximilian Ruta <mr@xtain.net>
     */
    public function getTime()
    {
        return $this->data['time'];
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     *
     * @api
     */
    public function getName()
    {
        return 'neo4j';
    }
}