<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProfesorAsignatura
 *
 * @ORM\Table(name="profesor_asignatura")
 * @ORM\Entity
 */
class ProfesorAsignatura
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_asignatura", type="integer", nullable=true)
     */
    private $idAsignatura;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \SI\SigueBundle\Entity\Profesor
     *
     * @ORM\ManyToOne(targetEntity="SI\SigueBundle\Entity\Profesor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_profesor", referencedColumnName="idprofesor")
     * })
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