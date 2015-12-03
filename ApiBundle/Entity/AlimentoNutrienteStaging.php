<?php

namespace TBCA\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AlimentoNutrienteStaging
 *
 * @ORM\Table(name="alimento_nutriente_staging", indexes={@ORM\Index(name="fk_id_nutriente_staging", columns={"id_nutriente"})})
 * @ORM\Entity
 */
class AlimentoNutrienteStaging
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_alimento", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idAlimento;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_nutriente", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idNutriente;

    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="string", length=255, nullable=false)
     */
    private $valor;

    /**
     * @var integer
     *
     * @ORM\Column(name="deletado", type="integer", nullable=false)
     */
    private $deletado;



    /**
     * Set idAlimento
     *
     * @param integer $idAlimento
     * @return AlimentoNutrienteStaging
     */
    public function setIdAlimento($idAlimento)
    {
        $this->idAlimento = $idAlimento;

        return $this;
    }

    /**
     * Get idAlimento
     *
     * @return integer 
     */
    public function getIdAlimento()
    {
        return $this->idAlimento;
    }

    /**
     * Set idNutriente
     *
     * @param integer $idNutriente
     * @return AlimentoNutrienteStaging
     */
    public function setIdNutriente($idNutriente)
    {
        $this->idNutriente = $idNutriente;

        return $this;
    }

    /**
     * Get idNutriente
     *
     * @return integer 
     */
    public function getIdNutriente()
    {
        return $this->idNutriente;
    }

    /**
     * Set valor
     *
     * @param string $valor
     * @return AlimentoNutrienteStaging
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set deletado
     *
     * @param integer $deletado
     * @return AlimentoNutrienteStaging
     */
    public function setDeletado($deletado)
    {
        $this->deletado = $deletado;

        return $this;
    }

    /**
     * Get deletado
     *
     * @return integer 
     */
    public function getDeletado()
    {
        return $this->deletado;
    }
}
