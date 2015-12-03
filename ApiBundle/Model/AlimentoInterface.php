<?php

namespace TBCA\ApiBundle\Model;

interface AlimentoInterface
{
	/**
     * Get id
     *
     * @return integer 
     */
    public function getId();

    /**
     * Set nome
     *
     * @param string $nome
     * @return Alimento
     */
    public function setNome($nome);

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome();

    /**
     * Set idCategoria
     *
     * @param integer $idCategoria
     * @return Alimento
     */
    public function setIdCategoria($idCategoria);

    /**
     * Get idCategoria
     *
     * @return integer 
     */
    public function getIdCategoria();

    /**
     * Get dataArray
     *
     * @return array 
     */
    public function getDataArray();
}