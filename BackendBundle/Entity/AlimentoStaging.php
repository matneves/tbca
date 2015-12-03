<?php

namespace TBCA\BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AlimentoStaging
 *
 * @ORM\Table(name="alimento_staging")
 * @ORM\Entity
 */
class AlimentoStaging
{
    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="valores", type="text", nullable=false)
     */
    private $valores;

    /**
     * @var string
     *
     * @ORM\Column(name="editor", type="string", length=255, nullable=false)
     */
    private $editor;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set nome
     *
     * @param string $nome
     * @return AlimentoStaging
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
     * Set valores
     *
     * @param string $valores
     * @return AlimentoStaging
     */
    public function setValores($valores)
    {
        $this->valores = $valores;

        return $this;
    }

    /**
     * Get valores
     *
     * @return string 
     */
    public function getValores()
    {
        return $this->valores;
    }

    /**
     * Set editor
     *
     * @param string $editor
     * @return AlimentoStaging
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Get editor
     *
     * @return string 
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
