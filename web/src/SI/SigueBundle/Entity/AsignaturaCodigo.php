<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AsignaturaCodigo
 */
class AsignaturaCodigo
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \SI\SigueBundle\Entity\Codigos
     */
    private $idCodigo;

    /**
     * @var \SI\SigueBundle\Entity\AsignaturaAlumno
     */
    private $idAsignaturaAlumno;
    
    /**
     *  @integer
     */
    private $num;


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
     * Set idCodigo
     *
     * @param \SI\SigueBundle\Entity\Codigos $idCodigo
     * @return AsignaturaCodigo
     */
    public function setIdCodigo(\SI\SigueBundle\Entity\Codigos $idCodigo = null)
    {
        $this->idCodigo = $idCodigo;
    
        return $this;
    }

    /**
     * Get idCodigo
     *
     * @return \SI\SigueBundle\Entity\Codigos 
     */
    public function getIdCodigo()
    {
        return $this->idCodigo;
    }

    /**
     * Set idAsignaturaAlumno
     *
     * @param \SI\SigueBundle\Entity\AsignaturaAlumno $idAsignaturaAlumno
     * @return AsignaturaCodigo
     */
    public function setIdAsignaturaAlumno(\SI\SigueBundle\Entity\AsignaturaAlumno $idAsignaturaAlumno = null)
    {
        $this->idAsignaturaAlumno = $idAsignaturaAlumno;
    
        return $this;
    }

    /**
     * Get idAsignaturaAlumno
     *
     * @return \SI\SigueBundle\Entity\AsignaturaAlumno 
     */
    public function getIdAsignaturaAlumno()
    {
        return $this->idAsignaturaAlumno;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getNum()
    {
        return $this->num;
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function setNum($num)
    {
        $this->num = $num;
    }
}