<?php

/* SISigueBundle:Default:index.html.php */
class __TwigTemplate_bbe08a13e64bd4bd9b1cc033f819bf850837b01baf3ed9fbdcd1f913bea3f7b9 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<?php \$view->extend('::base.html.php') ?>

Hello <?php echo \$view->escape(\"MOTHERFUCKER\") ?>!";
    }

    public function getTemplateName()
    {
        return "SISigueBundle:Default:index.html.php";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}
