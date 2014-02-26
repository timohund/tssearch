<?php

namespace Ts\SearchBundle\Domain;

class Link {

    /**
     * @var string
     */
    protected $url;

    /**
     * @param string $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }
}