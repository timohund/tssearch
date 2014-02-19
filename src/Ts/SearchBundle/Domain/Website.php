<?php

namespace Ts\SearchBundle\Domain;

use Solarium\QueryType\Select\Result\Document;

class Website extends Document {

    protected $incomingLinkTextCollection;

    public function __construct(array $fields) {
        parent::__construct($fields);
        $this->incomingLinkTextCollection = new IncomingLinkTextCollection();
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
}