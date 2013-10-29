<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AsignaturaCodigo
 *
 * @ORM\Table(name="asignatura_codigo")
 * @ORM\Entity
 */
class AsignaturaCodigo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \SI\SigueBundle\Entity\Codigos
     *
     * @ORM\ManyToOne(targetEntity="SI\SigueBundle\Entity\Codigos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_codigo", referencedColumnName="idcodigos")
     * })
     */
    private $idCodigo;

    /**
     * @var \SI\SigueBundle\Entity\AsignaturaAlumno
     *
     * @ORM\ManyToOne(targetEntity="SI\SigueBundle\Entity\AsignaturaAlumno")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_asignatura_alumno", referencedColumnName="id_asignatura_alumno")
     * })
     */
    private $idAsignaturaAlumno;



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
}