<?php

/* /views/layouts/main.twig */
class __TwigTemplate_f99df9bd2062271635d552661b4e045c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'ieTags' => array($this, 'block_ieTags'),
            'wrapper' => array($this, 'block_wrapper'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo ETwigViewRendererVoidFunction($this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "getClientScript"), "registerPackage", array(0 => "base"), "method"));
        echo "
<!DOCTYPE html>
<html lang=\"";
        // line 3
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["App"]) ? $context["App"] : null), "language"), "html", null, true);
        echo "\">
<head>
    <base href=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "params"), "siteUrl"), "html", null, true);
        echo "/\" />
    <meta charset=\"utf-8\">
    <title>";
        // line 7
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
    <!--[if lte IE 8]><script type=\"text/javascript\" src=\"js/ie.js\"></script><![endif]-->
    ";
        // line 9
        $this->displayBlock('ieTags', $context, $blocks);
        // line 10
        echo "    <link href=\"favicon.png\" rel=\"icon\" type=\"image/png\" />
    <link href=\"favicon.png\" rel=\"shortcut icon\" type=\"image/png\" />
</head>

<body>
";
        // line 15
        $this->displayBlock('wrapper', $context, $blocks);
        // line 16
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "data"), "ga", array(), "array");
        echo "
</body>
</html>";
    }

    // line 7
    public function block_title($context, array $blocks = array())
    {
        echo $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "pageTitle");
    }

    // line 9
    public function block_ieTags($context, array $blocks = array())
    {
    }

    // line 15
    public function block_wrapper($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "/views/layouts/main.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 15,  60 => 7,  53 => 16,  51 => 15,  44 => 10,  42 => 9,  37 => 7,  27 => 3,  22 => 1,  85 => 22,  66 => 9,  64 => 22,  54 => 14,  45 => 12,  41 => 11,  32 => 5,  29 => 3,  31 => 4,  28 => 3,);
    }
}
