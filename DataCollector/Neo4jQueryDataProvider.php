<?php

namespace Neo4j\OGM\OGMBundle\DataCollector;

use HireVoice\Neo4j\EntityManager;
use HireVoice\Neo4j\Event\PostStmtExecute;
use HireVoice\Neo4j\Event\PreStmtExecute;
use HireVoice\Neo4j\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class Neo4jQueryDataProvider
 *
 * @author Maximilian Ruta <mr@xtain.net>
 * @package Neo4j\OGM\OGMBundle\DataCollector
 */
class Neo4jQueryDataProvider
{
    /**
     * @var array
     */
    protected $queries = array();

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var float
     */
    protected $time;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $em = $entityManager->getEventManager();
        $em->addEventListener(Events::postStmtExecute, $this);
    }

    /**
     * @param GetResponseEvent $event
     *
     * @author Maximilian Ruta <mr@xtain.net>
     */
    public function onKernelRequest(GetResponseEvent $event) {

    }

    /**
     * @param PostStmtExecute $stm
     *
     * @author Maximilian Ruta <mr@xtain.net>
     */
    public function postStmtExecute(PostStmtExecute $stm)
    {
        $q = $stm->getQuery();
        $e = new \Exception();
        $trace = $e->getTraceAsString();
        $params = null;
        if (method_exists($q, 'getParameters')) {
            $params = $q->getParameters();
        }
        $time = $stm->getTime();
        $this->time = $this->time + $time;
        $this->count++;
        $this->queries[] = array(
            'query' => $q->getQuery(),
            'trace' => $trace,
            'params' => $params,
            'time' => $time
        );
    }

    /**
     * @return array
     * @author Maximilian Ruta <mr@xtain.net>
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * @return float
     * @author Maximilian Ruta <mr@xtain.net>
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return int
     * @author Maximilian Ruta <mr@xtain.net>
     */
    public function getCount()
    {
        return $this->count;
    }
}