<?php

namespace TBCA\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class AlimentoController extends Controller
{
	public function indexAction()
	{
		$result = $this->container
		->get('tbca_api.alimento.handler')
		->get();

		if ($result) {
			if(gettype($result) == 'object'){
				return $result;
			}
			
			return $this->render(
				'TBCAApiBundle:Default:alimentos.html.twig', 
				array('alimentos' => $result)
				);
		} else {
			throw new NotFoundHttpException(sprintf('The resource was not found.'));
		}
	}

	public function showAction($id)
	{
		$result = $this->container
		->get('tbca_api.alimento.handler')
		->get($id);

		if ($result) {
			if(gettype($result) == 'object'){
				return $result;
			}

			return $this->render(
				'TBCAApiBundle:Default:alimentos.html.twig', 
				array('alimentos' => $result)
				);
		} else {
			throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.', $id));
		}
	}

	public function showWithAttributeAction($id, $genericAttribute)
	{
		$result = $this->container
		->get('tbca_api.alimento.handler')
		->getWithAttribute($id, $genericAttribute);

		if ($result) {
			if(gettype($result) == 'object'){
				return $result;
			}

			return $this->render(
				'TBCAApiBundle:Default:alimentos.html.twig', 
				array('alimentos' => $result)
				);
		} else {
			throw new NotFoundHttpException(sprintf('The resource was not found.'));
		}
	}

	public function updateAction($id, $data)
	{
		$result = $this->container
		->get('tbca_api.alimento.handler')
		->update($id, $data);

		return $this->render(
			'TBCAApiBundle:Default:alimentos.html.twig', 
			array('alimentos' => $result)
			);
	}
}