<?php

namespace TBCA\ApiBundle\Handler;

use TBCA\ApiBundle\Model\AlimentoInterface;

interface HandlerInterface
{
    /**
    * Get resource.
    *
    * @param mixed $id (optional)
    *
    * @return ResourceInterface
    */
    public function get($id);

    /**
    * Update resource.
    *
    * @param mixed $id
    * @param mixed $data
    *
    * @return boolean
    */
    public function update($id, $data);

	/**
    * Delete resource.
    *
    * @param mixed $id
    *
    * @return boolean
    */
	public function delete($id);

	/**
    * Create resource.
    *
    * @return int
    */
	public function create();
}