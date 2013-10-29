<?php

namespace SI\SigueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Codigos
 *
 * @ORM\Table(name="codigos")
 * @ORM\Entity
 */
class Codigos
{
    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="text", nullable=true)
     */
    private $codigo;

    /**
     * @var integer
     *
     * @ORM\Column(name="idcodigos", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcodigos;



    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Codigos
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Get idcodigos
     *
     * @return integer 
     */
    public function getIdcodigos()
    {
        return $this->idcodigos;
    }
}