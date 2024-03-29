<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AsignaturaAlumno
 * @ManyToOne(targetEntity="Alumnos", mappedBy="idalumno", cascade={"persist"})
 */
class AsignaturaAlumno
{
    /**
     * @var integer
     */
    private $idAsignaturaAlumno;

    /**
     * @var \SI\SigueBundle\Entity\Asignaturas
     */
    private $idAsignatura;

    /**
     * @var \SI\SigueBundle\Entity\Alumnos
     */
    private $idAlumno;

    /**
     * @var integer
     */
    private $num;
    
    /**
     * Get num
     *
     * @return integer 
     */
    public function getNum()
    {
        return $this->num;
    }
    
    /**
     * Set num
     *
     * @param integer $num
     * @return AsignaturaAlumno 
     */
    public function setNum($num)
    {
        $this->num = $num;
        
        return $this;
    }

    /**
     * Get idAsignaturaAlumno
     *
     * @return integer 
     */
    public function getIdAsignaturaAlumno()
    {
        return $this->idAsignaturaAlumno;
    }

    /**
     * Set idAsignatura
     *
     * @param \SI\SigueBundle\Entity\Asignaturas $idAsignatura
     * @return AsignaturaAlumno
     */
    public function setIdAsignatura(\SI\SigueBundle\Entity\Asignaturas $idAsignatura = null)
    {
        $this->idAsignatura = $idAsignatura;
    
        return $this;
    }

    /**
     * Get idAsignatura
     *
     * @return \SI\SigueBundle\Entity\Asignaturas 
     */
    public function getIdAsignatura()
    {
        return $this->idAsignatura;
    }

    /**
     * Set idAlumno
     *
     * @param \SI\SigueBundle\Entity\Alumnos $idAlumno
     * @return AsignaturaAlumno
     */
    public function setIdAlumno(\SI\SigueBundle\Entity\Alumnos $idAlumno = null)
    {
        $this->idAlumno = $idAlumno;
    
        return $this;
    }

    /**
     * Get idAlumno
     *
     * @return \SI\SigueBundle\Entity\Alumnos 
     */
    public function getIdAlumno()
    {
        return $this->idAlumno;
    }
}