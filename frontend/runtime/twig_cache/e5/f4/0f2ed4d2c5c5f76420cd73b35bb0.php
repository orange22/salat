<?php

/* /views/site/index.twig */
class __TwigTemplate_e5f40f2ed4d2c5c5f76420cd73b35bb0 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("/views/layouts/column.twig");

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "/views/layouts/column.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "categories"));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["cat"]) {
            // line 5
            echo "    ";
            if ((!$this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "first"))) {
                // line 6
                echo "    <div class=\"item-head\" id=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cat"]) ? $context["cat"] : null), "code"), "html", null, true);
                echo "\">
        <div class=\"container\">";
                // line 7
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cat"]) ? $context["cat"] : null), "title"), "html", null, true);
                echo "</div>
    </div>
    ";
            }
            // line 10
            echo "    <div class=\"container\">
        <div class=\"item-box\" id=\"";
            // line 11
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cat"]) ? $context["cat"] : null), "code"), "html", null, true);
            echo "\">
            <a href=\"#\" class=\"prev\"></a>
            <a href=\"#\" class=\"next\"></a>
            <div class=\"gallery-holder\">
                <ul class=\"slide-list\">
                    <li class=\"slide\">
                        <div class=\"img-holder\"><img src=\"images/img01.jpg\" width=\"509\" height=\"600\" alt=\"image description\" /></div>
                    </li>
                    <li class=\"slide\">
                        <div class=\"img-holder\"><img src=\"images/img01.jpg\" width=\"509\" height=\"600\" alt=\"image description\" /></div>
                    </li>
                    <li class=\"slide\">
                        <div class=\"img-holder\"><img src=\"images/img01.jpg\" width=\"509\" height=\"600\" alt=\"image description\" /></div>
                    </li>
                    <li class=\"slide\">
                        <div class=\"img-holder\"><img src=\"images/img01.jpg\" width=\"509\" height=\"600\" alt=\"image description\" /></div>
                    </li>
                    <li class=\"slide\">
                        <div class=\"img-holder\"><img src=\"images/img01.jpg\" width=\"509\" height=\"600\" alt=\"image description\" /></div>
                    </li>
                    <li class=\"slide\">
                        <div class=\"img-holder\"><img src=\"images/img01.jpg\" width=\"509\" height=\"600\" alt=\"image description\" /></div>
                    </li>
                    <li class=\"slide\">
                        <div class=\"img-holder\"><img src=\"images/img01.jpg\" width=\"509\" height=\"600\" alt=\"image description\" /></div>
                    </li>
                    <li class=\"slide\">
                        <div class=\"img-holder\"><img src=\"images/img01.jpg\" width=\"509\" height=\"600\" alt=\"image description\" /></div>
                    </li>
                    <li class=\"slide\">
                        <div class=\"img-holder\"><img src=\"images/img01.jpg\" width=\"509\" height=\"600\" alt=\"image description\" /></div>
                    </li>
                    <li class=\"slide\">
                        <div class=\"img-holder\"><img src=\"images/img01.jpg\" width=\"509\" height=\"600\" alt=\"image description\" /></div>
                    </li>
                </ul>
            </div>
            <div class=\"description\">
                <div class=\"price-col\">
                    <div class=\"price\">50<span>грн</span></div>
                    <div class=\"weight\">300г</div>
                </div>
                <div class=\"info\">
                    <div class=\"name\">Цезарь с курицей </div>
                    <p>Салата Ромэн, помидоры черри, перепелиные яйца, куриная грудка, соус</p>
                </div>
            </div>
            <a href=\"#\" class=\"buy-btn\">Заказать</a>
            <div class=\"switcher-box\">
                <div class=\"switcher\">
                    <ul>
                        <li class=\"active\"><a href=\"#\"></a></li>
                        <li><a href=\"#\"></a></li>
                        <li><a href=\"#\"></a></li>
                        <li><a href=\"#\"></a></li>
                        <li><a href=\"#\"></a></li>
                        <li><a href=\"#\"></a></li>
                        <li><a href=\"#\"></a></li>
                        <li><a href=\"#\"></a></li>
                        <li><a href=\"#\"></a></li>
                        <li><a href=\"#\"></a></li>
                        <li><a href=\"#\"></a></li>
                    </ul>
                </div>
                <a href=\"/category/";
            // line 75
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cat"]) ? $context["cat"] : null), "code"), "html", null, true);
            echo "\" class=\"more\">все ";
            echo twig_escape_filter($this->env, twig_lower_filter($this->env, $this->getAttribute((isset($context["cat"]) ? $context["cat"] : null), "title")), "html", null, true);
            echo "</a>
            </div>
        </div>
    </div>
    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['cat'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
    }

    public function getTemplateName()
    {
        return "/views/site/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  133 => 75,  66 => 11,  63 => 10,  57 => 7,  52 => 6,  49 => 5,  31 => 4,  28 => 3,);
    }
}
