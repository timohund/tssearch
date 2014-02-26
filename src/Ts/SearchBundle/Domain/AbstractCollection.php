<?php

namespace Ts\SearchBundle\Domain;

class AbstractCollection {

    /**
     * @var \ArrayObject
     */
    protected $storage;

    /**
     *
     */
    public function __construct() {
        $this->storage = new \ArrayObject();
    }
}