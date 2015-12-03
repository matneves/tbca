<?php

namespace TBCA\ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NutrienteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NutrienteRepository extends EntityRepository
{
	public function findByArray($data){
		$ids = join(',', $data);

		return $this->_em->createQueryBuilder()
		->select("n.nome, n.id, n.unidade")
		->from($this->_entityName, "n")
		->where("n.id IN ($ids)")
		->getQuery()
		->getResult();
	}
}
