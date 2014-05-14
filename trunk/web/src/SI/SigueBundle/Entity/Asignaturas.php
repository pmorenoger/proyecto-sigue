<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asignaturas
 */
class Asignaturas
{
    /**
     * @var string
     */
    private $curso;

    /**
     * @var string
     */
    private $grupo;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set curso
     *
     * @param string $curso
     * @return Asignaturas
     */
    public function setCurso($curso)
    {
        $this->curso = $curso;
    
        return $this;
    }

    /**
     * Get curso
     *
     * @return string 
     */
    public function getCurso()
    {
        return $this->curso;
    }

    /**
     * Set grupo
     *
     * @param string $grupo
     * @return Asignaturas
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    
        return $this;
    }

    /**
     * Get grupo
     *
     * @return string 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Asignaturas
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @var \SI\SigueBundle\Entity\MetodosEvaluacion
     */
    private $ideval;


    /**
     * Set ideval
     *
     * @param \SI\SigueBundle\Entity\MetodosEvaluacion $ideval
     * @return Asignaturas
     */
    public function setIdeval(\SI\SigueBundle\Entity\MetodosEvaluacion $ideval = null)
    {
        $this->ideval = $ideval;
    
        return $this;
    }

    /**
     * Get ideval
     *
     * @return \SI\SigueBundle\Entity\MetodosEvaluacion 
     */
    public function getIdeval()
    {
        return $this->ideval;
    }
    /**
     * @var string
     */
    private $parameval;


    /**
     * Set parameval
     *
     * @param string $parameval
     * @return Asignaturas
     */
    public function setParameval($parameval)
    {
        $this->parameval = $parameval;
    
        return $this;
    }

    /**
     * Get parameval
     *
     * @return string 
     */
    public function getParameval()
    {
        return $this->parameval;
    }
}