<?php

namespace Ts\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Exception\Exception;
use Ts\SearchBundle\Domain\WebsiteRepository;

class DefaultController extends Controller {

	/**
	 * @Route("/")
	 * @Template()
	 */
	public function indexAction() {
		return array();
	}

	/**
	 * @Route("/search")
	 * @Template()
	 */
	public function searchAction() {
		$url = $this->getRequest()->get('url');

		if($url != '') {
            try {
                $websiteRepository =$this->get("ts.search.domain.websiterepository");
                $documents = $websiteRepository->findByUrl($url);


                return $this->render(
                    'TsSearchBundle:Default:search.html.twig',
                    array('documents' => $documents )
                );
            } catch (Exception $e) {
                var_dump($e->getMessage());
                die();
            }
		}
	}
}
