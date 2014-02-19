<?php

namespace Ts\SearchBundle\Domain;

class IncomingLinkTextCollection {

    /**
     * @var
     */
    protected $storage;

    /**
     * @return void
     */
    public function __construct() {
        $this->storage = new \ArrayObject();
    }

    /**
     * @param IncomingLinkText $linkText
     */
    public function set(IncomingLinkText $linkText) {
        $this->storage->offsetSet($linkText->getLinkText(),$linkText);
    }

    /**
     * @param string $linkText
     * @return IncomingLinkText
     */
    public function getByLinkText($linkText) {
        return $this->storage->offsetGet($linkText);
    }

    /**
     * @param $linkText
     * @return bool
     */
    public function hasLinkText($linkText) {
        return $this->storage->offsetExists($linkText);
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