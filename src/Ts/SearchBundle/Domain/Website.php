<?php

namespace Ts\SearchBundle\Domain;

use Solarium\QueryType\Select\Result\Document;

class Website extends Document {

    /**
     * @var IncomingLinkTextCollection
     */
    protected $incomingLinkTextCollection;

    /**
     * @var LinkDomainCollection
     */
    protected $linkDomainCollection;

    public function __construct(array $fields) {
        parent::__construct($fields);
        $this->incomingLinkTextCollection = new IncomingLinkTextCollection();
        $this->linkDomainCollection = new LinkDomainCollection();
    }

    /**
     * @return array
     */
    public function getIncomingLinkTexts() {
        if($this->incomingLinkTextCollection->getCount() == 0) {
            $linkTexts = $this["incominglinktexts_tm"];
            foreach($linkTexts as $linkTextString) {
                $linkTextString = trim($linkTextString);

                if($linkTextString != "") {
                    if($this->incomingLinkTextCollection->hasLinkText($linkTextString)) {
                        $linkText = $this->incomingLinkTextCollection->getByLinkText($linkTextString);
                        $linkText->incrementCount();
                        $this->incomingLinkTextCollection->set($linkText);
                    } else {
                        $linkText = new IncomingLinkText();
                        $linkText->setLinkText($linkTextString);
                        $linkText->incrementCount();
                        $this->incomingLinkTextCollection->set($linkText);
                    }
                }
            }
        }

        return $this->incomingLinkTextCollection->getAsArray();
    }

    /**
     * @return
     */
    public function getIncomingLinkDomains() {
        if($this->linkDomainCollection->getCount() == 0) {
            $linkStrings = $this["incominglinkurls_sm"];
            foreach($linkStrings as $linkString) {
                $linkString = trim($linkString);

                if($linkString != "") {
                    $linkParts  = parse_url($linkString);
                    $hostName   = $linkParts['host'];

                    if($this->linkDomainCollection->hasDomainName($hostName)) {
                        $linkDomain = $this->linkDomainCollection->getByDomainName($hostName);

                    } else {
                        $linkDomain = new LinkDomain();
                        $linkDomain->setDomainName($hostName);
                        $this->linkDomainCollection->add($linkDomain);

                    }

                    $linkObject = new Link();
                    $linkObject->setUrl($linkString);
                    $linkDomain->addLink($linkObject);
                }
            }
        }

        return $this->linkDomainCollection->getAsArray();
    }
}