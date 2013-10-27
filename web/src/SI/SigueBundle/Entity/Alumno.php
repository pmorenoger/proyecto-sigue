<?php

// src/Acme/StoreBundle/Entity/Product.php
namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="alumnos")
 */
class Alumno
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $idalumno;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $nombre;
    
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $apellidos;

    /**
     * @ORM\Column(type="string", length=10,nullable=true)
     */
    protected $dni;

     /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $correo;
    
     /**
     * @ORM\Column(type="string", length=45,nullable=true)
     */
    protected $password;

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
     * Set nombre
     *
     * @param string $nombre
     * @return Alumno
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
     * @return Alumno
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
     * @return Alumno
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
     * @return Alumno
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
     * @return Alumno
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
}