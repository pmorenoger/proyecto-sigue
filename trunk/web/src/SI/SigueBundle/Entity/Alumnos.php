<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alumnos
 * @OneToMany(targetEntity="AsignaturaAlumno", mappedBy="idalumno", cascade={"persist"})
 */
class Alumnos
{
    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $apellidos;

    /**
     * @var string
     */
    private $dni;

    /**
     * @var string
     */
    private $correo;

    /**
     * @var string
     */
    private $password;
    
    /**
     * @var string
     */
    private $salt;
    
    /**
     * @var string
     */
    private $codigo_id;

    /**
     * @var integer
     */
    private $idalumno;

    
    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Alumnos
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return Alumnos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    
        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set dni
     *
     * @param string $dni
     * @return Alumnos
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    
        return $this;
    }

    /**
     * Get dni
     *
     * @return string 
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set correo
     *
     * @param string $correo
     * @return Alumnos
     */
    public function setCorreo($correo)
    {
        $this->correo = $correo;
    
        return $this;
    }

    /**
     * Get correo
     *
     * @return string 
     */
    public function getCorreo()
    {
        return $this->correo;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Alumnos
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Alumnos
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }
    
    /**
     * Get idalumno
     *
     * @return integer 
     */
    public function getIdalumno()
    {
        return $this->idalumno;
    }

    /**
     * Set codigo_id
     *
     * @param string $codigoId
     * @return Alumnos
     */
    public function setCodigoId($codigoId)
    {
        $this->codigo_id = $codigoId;
    
        return $this;
    }

    /**
     * Get codigo_id
     *
     * @return string 
     */
    public function getCodigoId()
    {
        return $this->codigo_id;
    }

    /**
     * Get codigo_id
     * 
     * @return string
     */
    public function getCodigo_id()
    {
        return $this->codigo_id;
    }
    
    /**
     * Set codigo_id
     *
     * @param string $codigo_id
     * @return Alumnos
     */
    public function setCodigo_id($codigo_id)
    {
        $this->codigo_id = $codigo_id;
        
        return $this;
    }
    /**
     * Set idalumno
     *
     * @param integer $idalumno
     * @return Alumnos
     */
    public function setIdalumno($idalumno)
    {
        $this->idalumno = $idalumno;
    
        return $this;
    }
}