<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MetodosEvaluacion
 */
class MetodosEvaluacion
{
    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var integer
     */
    private $ideval;


    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return MetodosEvaluacion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Get ideval
     *
     * @return integer 
     */
    public function getIdeval()
    {
        return $this->ideval;
    }
}