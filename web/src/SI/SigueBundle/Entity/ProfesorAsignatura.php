<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProfesorAsignatura
 */
class ProfesorAsignatura
{
    /**
     * @var integer
     */
    private $idAsignatura;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \SI\SigueBundle\Entity\Profesor
     */
    private $idProfesor;


    /**
     * Set idAsignatura
     *
     * @param integer $idAsignatura
     * @return ProfesorAsignatura
     */
    public function setIdAsignatura($idAsignatura)
    {
        $this->idAsignatura = $idAsignatura;
    
        return $this;
    }

    /**
     * Get idAsignatura
     *
     * @return integer 
     */
    public function getIdAsignatura()
    {
        return $this->idAsignatura;
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

    /**
     * Set idProfesor
     *
     * @param \SI\SigueBundle\Entity\Profesor $idProfesor
     * @return ProfesorAsignatura
     */
    public function setIdProfesor(\SI\SigueBundle\Entity\Profesor $idProfesor = null)
    {
        $this->idProfesor = $idProfesor;
    
        return $this;
    }

    /**
     * Get idProfesor
     *
     * @return \SI\SigueBundle\Entity\Profesor 
     */
    public function getIdProfesor()
    {
        return $this->idProfesor;
    }
}