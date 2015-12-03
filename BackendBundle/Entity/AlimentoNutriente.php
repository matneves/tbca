<?php

namespace TBCA\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AlimentoNutriente
 *
 * @ORM\Table(name="alimento_nutriente", indexes={@ORM\Index(name="fk_id_atributo", columns={"id_nutriente"})})
 * @ORM\Entity
 */
class AlimentoNutriente
{
    /**
     * @var string
     *
     * @ORM\Column(name="valor", type="string", length=255, nullable=false)
     */
    private $valor;

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
     * Set valor
     *
     * @param string $valor
     * @return AlimentoNutriente
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
     * Set idAlimento
     *
     * @param integer $idAlimento
     * @return AlimentoNutriente
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
     * @return AlimentoNutriente
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
}
