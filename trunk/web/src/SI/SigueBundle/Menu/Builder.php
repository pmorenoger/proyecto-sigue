<?php
// src/Acme/DemoBundle/Menu/Builder.php
namespace SI\SigueBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'si_sigue_homepage'));
        $menu->addChild('About Me', array(
            'route' => 'si_sigue_homepage',
            'routeParameters' => array('id' => 42)
        ));
        // ... add more children

        return $menu;
    }
    
    public function profesorMenu(FactoryInterface $factory, array $options, $asignaturas)
    {
        $menu = $factory->createItem('root');
        foreach ($asignaturas as $as){
            foreach ($as as $asignatura){            
                $menu->addChild($asignatura->getNombre())                        
                        ->setChildrenAttribute('id', 'bloque_asginatura_'.$asignatura->getId())
                        ->setChildrenAttribute('bloque_asignatura', 'bloque_asignatura')
                        ->setChildrenAttribute('onclick', 'mostrar_opciones_asignatura('.$asignatura->getId().')')
                        ->setChildrenAttribute('id', 'bloque_asginatura_'.$asignatura->getId())
                 
                    ->addChild('About Me', array(
                    'route' => 'si_sigue_homepage',
                    'routeParameters' => array('id' => 42)
                ));
                // ... add more children
                }
        }
        return $menu;
    }
}