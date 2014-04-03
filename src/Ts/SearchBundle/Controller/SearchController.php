<?php

namespace Ts\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Exception\Exception;
use Ts\SearchBundle\Domain\WebsiteRepository;

class SearchController extends Controller {

	/**
	 * @Route("/")
	 * @Template()
	 */
	public function indexAction() {
        $websiteRepository =$this->get("ts.search.domain.websiterepository");

        return $this->render(
            'TsSearchBundle:Search:index.html.twig',
            array('count' =>  $websiteRepository->countAll())
        );

	}

	/**
	 * @Route("/searchUrl")
	 * @Template()
	 */
	public function searchUrlAction() {
		$url = urldecode($this->getRequest()->get('url'));

		if($url != '') {
            try {
                $websiteRepository =$this->get("ts.search.domain.websiterepository");
                $resultSet = $websiteRepository->findByUrl($url);

                return $this->render(
                    'TsSearchBundle:Search:search.html.twig',
                    array(
                        'documents' => $resultSet->getDocuments(),
                        'numFound' => $resultSet->getNumFound(),
                        'url' => $url
                    )
                );
            } catch (Exception $e) {
                var_dump($e->getMessage());
                die();
            }
		}
	}

	/**
	 * @Route("/about")
	 * @Template()
	 */
	public function aboutAction() {
		return $this->render(
			'TsSearchBundle:Search:about.html.twig'
		);

	}
}
