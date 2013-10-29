<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Asignaturas
 *
 * @ORM\Table(name="asignaturas")
 * @ORM\Entity
 */
class Asignaturas
{
    /**
     * @var string
     *
     * @ORM\Column(name="curso", type="string", length=45, nullable=true)
     */
    private $curso;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=45, nullable=true)
     */
    private $grupo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
}