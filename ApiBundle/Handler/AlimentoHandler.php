<?php

namespace TBCA\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

use TBCA\ApiBundle\Handler\HandlerInterface;

class AlimentoHandler extends AbstractHandler implements HandlerInterface
{
	private $om;
	private $entityClass;
	private $repository;
	private $formFactory;
	private $request;
	protected $content;

	public function __construct(ObjectManager $om, $entityClass)
	{
		$this->om = $om;
		$this->entityClass = $entityClass;
		$this->repository = $this->om->getRepository($this->entityClass);

		$this->request = Request::createFromGlobals();
		$this->content = $this->request->headers->get('accept');
	}

	/**
	 * Get resource.
	 *
	 * @param mixed $id (optional)
	 *
	 * @return AlimentoInterface
	 */
	public function get($id = null)
	{
		$alimentosArray = array();
		
		if($this->request->query->get('nutriente')){
			$attrName = $this->request->query->get('nutriente');
			$attrValue = $this->request->query->get('valor');

			if(null !== $this->request->query->get('margem')){
				$margin = $this->request->query->get('margem');
			} else {
				$margin = null;
			}

			$alimentos = $this->getBySimilarity($attrName, $attrValue, $margin);

			foreach ($alimentos as $alimento) {
				$alimentosArray['alimentos'][] = $alimento->getDataArray();
			}
		} elseif($this->request->query->get('nome')){
			$alimentos = $this->getByName($this->request->query->get('nome'));
			
			foreach ($alimentos as $alimento) {
				$alimentosArray['alimentos'][] = $alimento->getDataArray();
			}
		} elseif($id == null){
			// Carrega o banco de dados
			$alimentos = $this->repository->findAll();

			foreach ($alimentos as $alimento) {
				$alimentosArray['alimentos'][] = $alimento->getDataArray();
			}
		} else {
			$alimentos = $this->repository->find($id);

			if($alimentos == null){
				return $alimentos;
			}

			// Cria o vetor
			$alimentosArray['alimentos'] = $alimentos->getDataArray();
		}

		$result = $this->serveFormat($alimentosArray, 'alimentos');

		return $result;
	}

	/**
	 * Update resource.
	 *
    * @param mixed $id
    * @param mixed $data
	 *
	 * @return boolean
	 */
	public function update($id, $data)
	{

	}

	/**
	 * Delete resource.
	 *
	 * @param mixed $id
	 *
	 * @return boolean
	 */
	public function delete($id)
	{

	}

	/**
	 * Create resource.
	 *
	 * @return int
	 */
	public function create()
	{

	}

	/**
	 * Get resource with generic attribute.
	 *
	 * @param mixed $id
	 * @param string $genericAttribute
	 *
	 * @return AlimentoInterface
	 */
	public function getWithAttribute($idAlimento, $genericAttribute)
	{
		$alimentos = $this->repository->find($idAlimento);

		if($alimentos == null){
			return $alimentos;
		}

		$alimentosArray['alimento'] = $alimentos->getDataArray();

		$attributeName = substr($genericAttribute, 0, strlen($genericAttribute)-1);

		$attributesRepository = $this->om
		->getRepository('TBCAApiBundle:'.ucfirst($attributeName));

		switch ($attributeName) {
			case 'categoria':
				$attributes = $attributesRepository->find($alimentosArray['alimento']['id_categoria']);

				$attributesArray = $attributes->getDataArray();
				$alimentosArray['alimento']['categorias'] = $attributesArray;
				break;

			case 'nutriente':
				$foodAttributeRepository = $this->om
				->getRepository('TBCAApiBundle:AlimentoNutriente');

				$idAlimento = $alimentosArray['alimento']['id'];
				$foodAttribute = $foodAttributeRepository->findBy(array('idAlimento' => $idAlimento));

				$attributesIdArray = array();
				$atrributesValueArray = array();
				foreach ($foodAttribute as $foodAttrRel) {
					$foodAttrRelArray = $foodAttrRel->getDataArray();

					$attributesIdArray[] = $foodAttrRelArray['id_nutriente'];
					$attributesValueArray[$foodAttrRelArray['id_nutriente']] = $foodAttrRelArray['valor'];
				}

				$attributes = $attributesRepository->findByArray($attributesIdArray);
				$atrributesArray = array();
				foreach ($attributes as $attribute) {
					$attributesArray[] = array(
						"id" => $attribute['id'],
						"nome" => $attribute['nome'],
						"unidade" => $attribute['unidade'],
						"valor" => $attributesValueArray[$attribute['id']]
						);
				}

				$alimentosArray['alimento']['nutrientes'] = $attributesArray;
				break;
			
			default:
				return null;
				break;
		}

		if($attributes == null){
			return $attributes;
		}

		$result = $this->serveFormat($alimentosArray, 'alimentos');

		return $result;
	}

	/**
	 * Get resource by its name.
	 *
	 * @param string $name
	 *
	 * @return AlimentoInterface
	 */
	private function getByName($name){
		// Carrega o banco de dados
		return $this->repository->findByName($name);
	}

	private function getBySimilarity($attrName, $attrValue, $margin = null){
		if($margin == null){
			$margin = 5;
		}

		return $this->repository->findBySimilarity($attrName, $attrValue, $margin);
	}
}