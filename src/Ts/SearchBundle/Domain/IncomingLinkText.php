<?php

namespace Ts\SearchBundle\Domain;

class IncomingLinkText {

    /**
     * @var integer
     */
    protected $count;

    /**
     * @var string
     */
    protected $linkText;

    /**
     * @return int
     */
    public function getCount() {
        return $this->count;
    }

    /**
     * @return void
     */
    public function incrementCount() {
        $this->count++;
    }

    /**
     * @param string $linkText
     */
    public function setLinkText($linkText) {
        $this->linkText = $linkText;
    }

    /**
     * @return string
     */
    public function getLinkText() {
        return $this->linkText;
    }
}