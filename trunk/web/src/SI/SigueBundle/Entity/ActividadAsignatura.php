<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActividadAsignatura
 */
class ActividadAsignatura
{
    /**
     * @var float
     */
    private $nota;

    /**
     * @var float
     */
    private $peso;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var string
     */
    private $observaciones;

    /**
     * @var \DateTime
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     */
    private $fechaLimite;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \SI\SigueBundle\Entity\Alumnos
     */
    private $idAlumno;

    /**
     * @var \SI\SigueBundle\Entity\Asignaturas
     */
    private $idAsignatura;


    /**
     * Set nota
     *
     * @param float $nota
     * @return ActividadAsignatura
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
    
        return $this;
    }

    /**
     * Get nota
     *
     * @return float 
     */
    public function getNota()
    {
        if(is_null($this->nota)){
            return 0;
        }
        return $this->nota;
    }

    /**
     * Set peso
     *
     * @param float $peso
     * @return ActividadAsignatura
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;
    
        return $this;
    }

    /**
     * Get peso
     *
     * @return float 
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return ActividadAsignatura
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return ActividadAsignatura
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
     * Set observaciones
     *
     * @param string $observaciones
     * @return ActividadAsignatura
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return ActividadAsignatura
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
     * Set fechaLimite
     *
     * @param \DateTime $fechaLimite
     * @return ActividadAsignatura
     */
    public function setFechaLimite($fechaLimite)
    {
        $this->fechaLimite = $fechaLimite;
    
        return $this;
    }

    /**
     * Get fechaLimite
     *
     * @return \DateTime 
     */
    public function getFechaLimite()
    {
        return $this->fechaLimite;
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
     * Set idAlumno
     *
     * @param \SI\SigueBundle\Entity\Alumnos $idAlumno
     * @return ActividadAsignatura
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

    /**
     * Set idAsignatura
     *
     * @param \SI\SigueBundle\Entity\Asignaturas $idAsignatura
     * @return ActividadAsignatura
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
}
