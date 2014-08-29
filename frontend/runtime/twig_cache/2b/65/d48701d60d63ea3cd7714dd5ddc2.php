<?php

/* /views/layouts/column.twig */
class __TwigTemplate_2b65d48701d60d63ea3cd7714dd5ddc2 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("/views/layouts/main.twig");

        $this->blocks = array(
            'wrapper' => array($this, 'block_wrapper'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "/views/layouts/main.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_wrapper($context, array $blocks = array())
    {
        // line 4
        echo "    <div id=\"wrapper\">
        <div id=\"header\">
            <div class=\"container clear\">
                <strong class=\"logo\"><a href=\"/\">Салатник</a></strong>
                <div class=\"header-frame\">
                    <nav id=\"nav\">
                        <ul class=\"nav\">
                            ";
        // line 11
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "categories"));
        foreach ($context['_seq'] as $context["_key"] => $context["cat"]) {
            // line 12
            echo "                            <li><a href=\"#";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cat"]) ? $context["cat"] : null), "code"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cat"]) ? $context["cat"] : null), "title"), "html", null, true);
            echo "</a></li>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['cat'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 14
        echo "                            <li><a href=\"#map\">карта доставки</a></li>
                            <li class=\"basket\"><a href=\"#\">корзина</a></li>
                        </ul>
                    </nav>
                    <div class=\"phone\">";
        // line 18
        echo twig_escape_filter($this->env, Option::getOpt("mainphone"), "html", null, true);
        echo "</div>
                </div>
            </div>
        </div>
        <div id=\"main\">
            ";
        // line 23
        $this->displayBlock('content', $context, $blocks);
        // line 24
        echo "            <div class=\"item-head\" id=\"map\">
                <div class=\"container\">карта доставки</div>
            </div>
            <div class=\"map\">
                <iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2541.0889936984854!2d30.522504050950385!3d50.43944299769924!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sru!2sua!4v1409053248319\" width=\"100%\" height=\"425\" frameborder=\"0\" style=\"border:0\"></iframe>
            </div>
        </div>
    </div>
    <footer id=\"footer\">
        <div class=\"container clear footer-frame\">
            <strong class=\"footer-logo\"><a href=\"#\">Салатник</a></strong>
            <div class=\"contacts\">
                <div class=\"col\">";
        // line 36
        echo Option::getOpt("contacts");
        echo "</div>
            </div>
            <ul class=\"social-list\">
                <li><a href=\"#\"><img src=\"images/ico02.png\" width=\"17\" height=\"17\" alt=\"image description\" /></a></li>
                <li><a href=\"#\"><img src=\"images/ico03.png\" width=\"17\" height=\"17\" alt=\"image description\" /></a></li>
            </ul>
        </div>
    </footer>
";
    }

    // line 23
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "/views/layouts/column.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  99 => 23,  86 => 36,  72 => 24,  70 => 23,  62 => 18,  56 => 14,  45 => 12,  41 => 11,  32 => 4,  29 => 3,  31 => 4,  28 => 3,);
    }
}
