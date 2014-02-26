<?php

namespace Ts\SearchBundle\Domain;

class LinkDomainCollection extends AbstractCollection {

    /**
     * @param LinkDomain $linkDomain
     */
    public function add(LinkDomain $linkDomain) {
        $this->storage->offsetSet($linkDomain->getDomainName(),$linkDomain);
    }

    /**
     * @param string $domainName
     */
    public function hasDomainName($domainName) {
        return $this->storage->offsetExists($domainName);
    }

    /**
     * @return LinkDomain
     */
    public function getByDomainName($domainName) {
        return $this->storage->offsetGet($domainName);
    }

    /**
     * @return int
     */
    public function getCount() {
        return $this->storage->count();
    }

    /**
     * @return array
     */
    public function getAsArray() {
        return $this->storage->getArrayCopy();
    }
}