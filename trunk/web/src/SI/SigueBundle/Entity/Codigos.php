<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Codigos
 */
class Codigos
{
    /**
     * @var string
     */
    private $codigo;

    /**
     * @var integer
     */
    private $idcodigo;


    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Codigos
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Get idcodigo
     *
     * @return integer 
     */
    public function getIdcodigo()
    {
        return $this->idcodigo;
    }
}