<?php

namespace Ts\SearchBundle\Domain;

use Solarium;

class WebsiteRepository {

    /**
     * @var \Solarium\Client
     */
    protected $client;

    /**
     * @param string $hostname
     * @param integer $port
     * @param string $path
     * @param string $core
     */
    public function __construct($hostname, $port, $path, $core) {
        $config = array(
            'endpoint' => array(
                $hostname => array(
                    'host' => $hostname,
                    'port' => $port,
                    'path' => $path,
                    'core' => $core
                )
            )
        );

        $this->client = new Solarium\Client($config);
        $this->client->setAdapter('Solarium\Core\Client\Adapter\Http');
    }

    /**
     * @param string $url
     * @return array
     */
    public function findByUrl($url)  {
        $query = $this->client->createSelect();
        $query->setDocumentClass("\Ts\SearchBundle\Domain\Website");

        $query->setQuery("");
        $fq = $query->createFilterQuery();
        $fq->setKey('url');
        $fq->setQuery('url:'.str_replace(':','\\:',$url));
        $query->addFilterQuery($fq);


        // this executes the query and returns the result
        $resultset = $this->client->select($query);

        return $resultset;
    }

    /**
     * @return integer
     */
    public function countAll() {
        $query = $this->client->createSelect();
        $query->setQuery("");
        $resultset = $this->client->select($query);

        return $resultset->getNumFound();
    }
}