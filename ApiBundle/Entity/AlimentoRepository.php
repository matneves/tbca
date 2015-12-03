<?php

namespace TBCA\ApiBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AlimentoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AlimentoRepository extends EntityRepository
{
	public function findByName($string){
		$names = explode(" ", $string);

		$first = true;
		foreach ($names as $name) {
			if($first){
				$whereString = "a.nome LIKE '%$name%'";
				$first = false;
			}
			else
				$whereString .= " AND a.nome LIKE '%$name%'";
		}

		$result = $this->_em->createQueryBuilder()
			->select("a.id")
			->from($this->_entityName, "a")
			->where($whereString)
			->getQuery()
			->getResult();

		$objectArray = array();
		foreach ($result as $key => $value) {
			$objectArray[] = $this->find($value['id']);
		}

		return $objectArray;
	}

	public function findBySimilarity($attrName, $attrValue, $margin){
		$whereString = "n.nome = '".$attrName."'";
		$result = $this->_em->createQueryBuilder()
			->select("n.id")
			->from("TBCA\ApiBundle\Entity\Nutriente", "n")
			->where($whereString)
			->getQuery()
			->getResult();

		unset($whereString);

		$min = $attrValue-$margin;
		$max = $attrValue+$margin;

		$whereString = "an.idNutriente = ".$result[0]['id']." AND an.valor >= ".$min." AND an.valor <= ".$max;

		unset($result);

		$result = $this->_em->createQueryBuilder()
			->select("an.idAlimento")
			->from("TBCA\ApiBundle\Entity\AlimentoNutriente", "an")
			->where($whereString)
			->getQuery()
			->getResult();

		$objectArray = array();
		foreach ($result as $key => $value) {
			$objectArray[] = $this->find($value['idAlimento']);
		}

		return $objectArray;
	}
}
