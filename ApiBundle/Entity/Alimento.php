<?php

namespace TBCA\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use TBCA\ApiBundle\Model\AlimentoInterface;

/**
 * Alimento
 *
 * @ORM\Table(name="alimento", indexes={@ORM\Index(name="fk_id_categoria", columns={"id_categoria"})})
 * @ORM\Entity
 */
class Alimento implements AlimentoInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255, nullable=false)
     */
    private $nome;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_categoria", type="integer", nullable=false)
     */
    private $idCategoria;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return Alimento
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set idCategoria
     *
     * @param integer $idCategoria
     * @return Alimento
     */
    public function setIdCategoria($idCategoria)
    {
        $this->idCategoria = $idCategoria;

        return $this;
    }

    /**
     * Get idCategoria
     *
     * @return integer 
     */
    public function getIdCategoria()
    {
        return $this->idCategoria;
    }

    /**
     * Get dataArray
     *
     * @return array 
     */
    public function getDataArray()
    {   
        $array = array(
            'id' => $this->getId(), 
            'nome' => $this->getNome(), 
            'id_categoria' => $this->getIdCategoria()
            );

        return $array;
    }
}
