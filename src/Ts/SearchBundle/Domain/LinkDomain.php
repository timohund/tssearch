<?php

namespace Ts\SearchBundle\Domain;

class LinkDomain {

    /**
     * @var string
     */
    protected $domainName = '';

    /**
     * @var ArrayObject
     */
    protected $links;

    public function __construct() {
        $this->links = new \ArrayObject();
    }

    /**
     * @param string $domainName
     */
    public function setDomainName($domainName) {
        $this->domainName = $domainName;
    }

    /**
     * @return string
     */
    public function getDomainName() {
        return $this->domainName;
    }

    /**
     * @param \ArrayObject $links
     */
    public function setLinks($links) {
        $this->links = $links;
    }

    /**
     * @param Link $link
     */
    public function addLink(Link $link) {
        $this->links->append($link);
    }

    /**
     * @return \ArrayObject
     */
    public function getLinks() {
        return $this->links;
    }
}