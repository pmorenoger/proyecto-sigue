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
     * @var \DateTime
     */
    private $fechaCreacion;
    
    /**
     * @var \DateTime
     */
    private $fechaAlta;
    
    /**
     * @var \SI\SigueBundle\Entity\Asignaturas
     */
    private $id;
    
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
    
    /**
     * Set id
     *
     * @param \SI\SigueBundle\Entity\Asignaturas $id
     * @return Codigos
     */
    public function setId(\SI\SigueBundle\Entity\Asignaturas $id = null)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * Get id
     *
     * @return \SI\SigueBundle\Entity\Asignaturas 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Codigos
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;
    
        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }
    
    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     * @return Codigos
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;
    
        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime 
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }
}