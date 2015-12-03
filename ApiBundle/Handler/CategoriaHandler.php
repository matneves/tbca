<?php

namespace TBCA\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

use TBCA\ApiBundle\Handler\HandlerInterface;

class CategoriaHandler extends AbstractHandler implements HandlerInterface
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

	public function get($id = null)
	{
		$categoriasArray = array();

		if($id == null){
			// Carrega o banco de dados
			$categorias = $this->repository->findAll();

			// Cria o vetor
			foreach ($categorias as $categoria) {
				$categoriasArray['categorias'][] = $categoria->getDataArray();
			}
		} else {
			// Carrega o banco de dados
			$categorias = $this->repository->find($id);

			// Cria o vetor
			$categoriasArray['categorias'] = $categorias->getDataArray();
		}

		$result = $this->serveFormat($categoriasArray, 'categorias');

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
}