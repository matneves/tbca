<?php

namespace TBCA\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class CategoriaController extends Controller
{
	public function indexAction()
	{
		$result = $this->container
		->get('tbca_api.categoria.handler')
		->get();

		if ($result) {
			if(gettype($result) == 'object'){
				return $result;
			}
			
			return $this->render(
				'TBCAApiBundle:Default:categorias.html.twig', 
				array('categorias' => $result)
				);
		} else {
			throw new NotFoundHttpException(sprintf('The resource was not found.'));
		}
	}

	public function showAction($id)
	{
		$result = $this->container
		->get('tbca_api.categoria.handler')
		->get($id);

		if ($result) {
			if(gettype($result) == 'object'){
				return $result;
			}

			return $this->render(
				'TBCAApiBundle:Default:categorias.html.twig', 
				array('categorias' => $result)
				);
		} else {
			throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $id));
		}
	}
}