<?php

namespace TBCA\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nutriente
 *
 * @ORM\Table(name="nutriente")
 * @ORM\Entity
 */
class Nutriente
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
     * @var string
     *
     * @ORM\Column(name="unidade", type="string", length=255, nullable=false)
     */
    private $unidade;



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
     * @return Nutriente
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
     * Set unidade
     *
     * @param string $unidade
     * @return Nutriente
     */
    public function setUnidade($unidade)
    {
        $this->unidade = $unidade;

        return $this;
    }

    /**
     * Get unidade
     *
     * @return string 
     */
    public function getUnidade()
    {
        return $this->unidade;
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
            'unidade' => $this->getUnidade()
            );

        return $array;
    }
}
