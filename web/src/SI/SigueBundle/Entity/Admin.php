<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Admin
 */
class Admin
{
    /**
     * @var string
     */
    private $correo;

    /**
     * @var string
     */
    private $password;

    /**
     * @var integer
     */
    private $idadmin;


    /**
     * Set correo
     *
     * @param string $correo
     * @return Admin
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
     * @return Admin
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
     * Get idadmin
     *
     * @return integer 
     */
    public function getIdadmin()
    {
        return $this->idadmin;
    }
}