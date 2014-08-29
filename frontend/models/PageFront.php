<?php
/**
 * Page AR frontend manager
 */
class PageFront
{
    /**
     * Tree head (key)
     *
     * @var string
     */
    protected static $head = '';

    /**
     * Static local cache
     *
     * @var array
     */
    protected static $cache = array();

    /**
     * Page vital fields
     *
     * @var array
     */
    protected static $select = array(
        'pid',
        'entity',
        'lft',
        'rgt',
        'type',
        'level',
        'title',
        'menu_title',
        'linked_title',
        'alias',
        'handler',
        'code',
        'color',
        'masked',
        'public'
    );

    /**
     * Get tree root PID
     *
     * @return int
     */
    public static function rootPid()
    {
        self::prefetch();

        return (int)self::$cache['tree'][self::$head]['pid'];
    }

    /**
     * Build page title string
     *
     * @param array $params
     * @return string
     */
    public static function buildPageTitle($params = array())
    {
        $o = array();
        $o[] = Option::getOpt('site.title');
        if(isset($params['page']['title']))
        {
            if($params['page']['level'] > Page::LVL_SITE)
            {
                $parent = self::parentOf($params['page']['pid']);
                $o[] = $parent['title'];
            }
            $o[] = $params['page']['title'];
        }
        if(!isset($params['pageTitle']) && isset($params['data']['title']))
            $o[] = $params['data']['title'];
        if(isset($params['pageTitle']))
            $o = array_merge($o, (array)$params['pageTitle']);

        return implode(' - ', $o);
    }

    /**
     * Currently selected pages
     *
     * @return array Array of pages PID from current to root
     */
    public static function currentPages()
    {
        self::prefetch();
        $current = request()->getQuery('page', 0);
        return (isset(self::$cache['paths'][$current][0]) ? self::$cache['paths'][$current][0] : array());
    }

    /**
     * Get site page tree edge values (lft,rgt)
     *
     * @param int $pid Page PID
     * @return array [lft, rgt]
     */
    public static function edgeOf($pid)
    {
        if(isset(self::$cache['lftRgt'][$pid]))
            return self::$cache['lftRgt'][$pid];

        return array(0, 0);
    }

    /**
     * Get children of page
     *
     * @param int $pid Parent page PID
     * @return array Array of tree nodes
     */
    public static function childrenOf($pid)
    {
        if(isset(self::$cache['refs'][$pid]['children']))
            return self::$cache['refs'][$pid]['children'];

        return array();
    }

    /**
     * Get parent of page
     *
     * @param int $pid Child PID
     * @return null|array Array of page data (tree node)
     */
    public static function parentOf($pid)
    {
        $path = self::$cache['paths'][$pid][0];
        // current item
        array_pop($path);
        if(count($path) < 1)
            return null;

        return self::$cache['refs'][array_pop($path)];
    }

    /**
     * Find closest navigation page
     *
     * @param int $pid Page PID parent of we are looking for (or self if already parent)
     * @return array
     */
    public static function closestNavPageOf($pid)
    {
        if(self::$cache['refs'][$pid]['level'] == Page::LVL_PAGE)
            return self::$cache['refs'][$pid];

        if(self::$cache['refs'][$pid]['level'] > Page::LVL_PAGE && self::$cache['refs'][$pid]['children'])
            return self::$cache['refs'][$pid];

        return self::parentOf($pid);
    }

    /**
     * Build pages menu
     *
     * @static
     * @param int $parentPagePid Page PID with descendants
     * @return array Array of [url, label, active, visible]
     */
    public static function menu($parentPagePid = null)
    {
        self::prefetch();
        $tree =& self::$cache['tree'][self::$head]['children'];
        //look for descendants
        if($parentPagePid && isset(self::$cache['paths'][$parentPagePid][0]))
        {
            // slice of first element as root page already fetched if we here
            $children = array_slice(self::$cache['paths'][$parentPagePid][0], 1);
            foreach($children as $pid)
                $tree =& $tree[self::$cache['refs'][$pid]['alias']]['children'];
        }

        return self::buildMenu($tree, self::currentPages());
    }

    /**
     * Build multilevel menu
     *
     * @param array $tree Pages tree
     * @param array $active Active pages PID
     * @param bool $rr Build multilevel menu (recursive traverse)
     * @return array CMenu applicable menu
     */
    protected static function buildMenu($tree, $active = array(), $rr = false)
    {
        $parent = null;
        $menu = array();
        $hasActive = false;
        foreach($tree as $item)
        {
            if(!$parent && $item['level'] > 1)
            {
                $parent = self::parentOf($item['pid']);
            }

            if(!isset($menu[0]) && !$item['children'] && $parent)
                $urlData =& $parent;
            else
                $urlData =& $item;

            $url = app()->createUrl((!$urlData['handler'] ? 'site/page' : $urlData['handler']), array('page' => $urlData['pid']));
            $buff = array(
                'url' => $url,
                'label' => !$item['menu_title'] ? $item['title'] : $item['menu_title'],
                'active' => in_array($item['pid'], $active),
                'visible' => (Page::model()->typeVisible($item['type']) && ($item['public'] || !user()->isGuest)),
            );

            if($item['level'] == Page::LVL_SITE)
                $buff['itemOptions'] = array('class' => 'bgc-'.CHtml::encode($item['code']));

            if($item['alias']{0} === '#')
                $buff['linkOptions'] = array('class' => 'popup-link');

            if($rr && $item['children'])
                $buff['items'] = self::buildMenu($item['children'], $active, $rr);

            $hasActive = !$hasActive && $buff['active'] ? true : $hasActive;
            $menu[] = $buff;
        }

        // force first menu item as active if pages selected
        if(!$hasActive && !empty($active))
            $menu[0]['active'] = true;

        return $menu;
    }

    /**
     * Get page data
     *
     * @static
     * @param int $pid Page PID
     * @param bool $full Fetch short or full model
     * @throws CHttpException
     * @return array|Page Array as short version or Page as full
     */
    public static function data($pid, $full = false)
    {
        if(!$full)
        {
            if(!isset(self::$cache['refs'][$pid]))
                throw new CHttpException(404, Yii::t('frontend', 'Page not found.'));

            $data = self::$cache['refs'][$pid];
            unset($data['children']);

            return $data;
        }

        $model = Page::model()->language()->active()->with('image')->findByAttributes(array('pid' => $pid));
        if($model)
            return $model;

        throw new CHttpException(404, Yii::t('frontend', 'Page not found.'));
    }

    /**
     * Resolve page by path
     * Begin from root page
     *
     * @param array $path Path items, i.e. [/, page1, page2 ...]
     * @return array [page, site, unresolvedPath]
     */
    public static function resolvePage($path)
    {
        $i = 0;
        $page = null;
        $site = null;
        self::prefetch();
        $tree =& self::$cache['tree'];
        foreach($path as $item)
        {
            if(!isset($tree[$item]))
                break;

            ++$i;
            $page = $tree[$item];
            if($tree[$item]['level'] == Page::LVL_SITE)
                $site = $tree[$item];
            $tree =& $tree[$item]['children'];
        }

        $unresolved = array_slice($path, $i);

        // path fully resolved and site selected
        if(!$unresolved && $page['pid'] === $site['pid'] && $site['children'])
            $page = current($site['children']);

        unset($page['children'], $site['children']);

        return array(
            'page' => $page,
            'site' => $site,
            'unresolvedPath' => $unresolved
        );
    }

    /**
     * Get page's top page
     *
     * @param int $pid Page PID
     * @return int Top page PID
     */
    public static function topPageOf($pid)
    {
        if(!isset(self::$cache['paths'][$pid]))
            return 0;

        $data = self::$cache['paths'][$pid][0];
        // top pages(sites) on level 2
        if(!isset($data[1]))
            return 0;

        return $data[1];
    }

    /**
     * Find path to page
     *
     * @param int $pid Page PID
     * @return string
     */
    public static function pathToPage($pid)
    {
        if(!isset(self::$cache['paths'][$pid]))
            return '';

        $path = self::$cache['paths'][$pid][1];
        if(self::isFirstLeaf($pid))
            array_pop($path);

        return trim(implode('/', $path), '/ ');
    }

    /**
     * Fetch top pages data
     *
     * @return array
     */
    public static function sites()
    {
        $o = array();
        self::prefetch();
        $keys = array_flip(self::$select);
        foreach(self::$cache['tree'][self::$head]['children'] as $node)
        {
            $o[$node['pid']] = array_intersect_key($node, $keys);
        }

        return $o;
    }

    /**
     * Get page PID with handler
     *
     * @param string $handler Handler
     * @return int Page PID
     */
    public static function pidOfHandler($handler)
    {
        self::prefetch();
        if(!isset(self::$cache['h2p'][$handler]))
            return 0;

        return current(self::$cache['h2p'][$handler]);
    }

    /**
     * Check whether item is first child and has no children
     *
     * @param int $pid Item to check
     * @return bool
     */
    public static function isFirstLeaf($pid)
    {
        $parent = self::parentOf($pid);
        $child = current(self::childrenOf($parent['pid']));

        return (!$child['children'] && $child['pid'] == $pid);
    }

    /**
     * Get code of site pid
     *
     * @param int $pid Site PID
     * @return string
     */
    public static function codeOf($pid)
    {
        self::prefetch();
        return array_search($pid, self::$cache['code2p']);
    }

    /**
     * Get site PID by site code
     *
     * @param string $code Site code
     * @return int
     */
    public static function pidOfCode($code)
    {
        self::prefetch();
        if(!isset(self::$cache['code2p'][$code]))
            return 0;

        return self::$cache['code2p'][$code];
    }

    /**
     * Get alias of page by handler
     *
     * @param string $handler Page handler
     * @return string
     */
    public static function aliasOfHandler($handler)
    {
        $pid = self::pidOfHandler($handler);
        if(!$pid)
            return '';

        $data = self::$cache['refs'][$pid];

        return $data['alias'];
    }

    /**
     * Prefetch sites data
     */
    protected static function prefetch()
    {
        if(!empty(self::$cache['tree']))
            return;

        if((self::$cache = getCache('PageFront_'.app()->language)))
        {
            self::$head = key(self::$cache['tree']);
            return;
        }

        $data = Page::model()
            ->select(self::$select)
            ->language()
            ->active()
            ->sort()
            ->findAll();

        self::$cache['h2p'] = array();
        self::$cache['refs'] = array();
        self::$cache['paths'] = array();
        self::$cache['code2p'] = array();
        self::$cache['lftRgt'] = array();
        self::$cache['tree'] = self::toTree($data);

        reset(self::$cache['tree']);
        self::$head = key(self::$cache['tree']);

        self::findAllPaths(self::$cache['tree'], self::$cache['paths']);
        self::collectRefs(self::$cache['tree'], self::$cache['refs']);
        foreach(self::$cache['refs'] as $pid => $data)
        {
            if($data['level'] == Page::LVL_SITE)
            {
                self::$cache['code2p'][$data['code']] = $pid;
                self::$cache['lftRgt'][$data['pid']] = array($data['lft'], $data['rgt']);
            }
            self::$cache['h2p'][$data['handler']][] = $pid;
        }

        setCache('PageFront_'.app()->language, self::$cache, 604800, new TagCacheDependency('Page'));
    }

    /**
     * Build tree from nested sets
     *
     * @param array $data
     * @return array
     */
    protected static function toTree($data)
    {
        $level = 0;
        $tree = array();
        $treeKeyPrev = null;
        $parents = array();
        foreach($data as $item)
        {
            foreach(self::$select as $field)
                $treeData[$field] = $item[$field];
            $treeData['children'] = array();
            $treeKey = $item['alias'];

            // first(root) item
            if($level == 0 && $item['level'] == 1)
            {
                $parents[$level] = $treeKey;
                $tree = array($treeKey => $treeData);
                $treeTail =& $tree;
            }
            else
            {
                if($item['level'] == $level)
                {
                    $treeTail[$treeKey] = $treeData;
                }
                elseif($item['level'] > $level)
                {
                    $parents[$level] = $treeKey;
                    $treeTail =& $treeTail[$treeKeyPrev]['children'];
                    $treeTail[$treeKey] = $treeData;
                }
                elseif($item['level'] < $level)
                {
                    $treeTail =& $tree;
                    for($i = 0; $i < $item['level'] - 1; $i++)
                    {
                        $treeTail =& $treeTail[$parents[$i]]['children'];
                    }
                    $treeTail[$treeKey] = $treeData;
                    $parents[$item['level'] - 1] = $treeKey;
                }
            }

            $level = $item['level'];
            $treeKeyPrev = $treeKey;
        }

        return $tree;
    }

    /**
     * Collect references of tree nodes into plain array, PID as key
     *
     * @param array $tree Tree array
     * @param array $refs Array of collected references
     */
    protected static function collectRefs(&$tree, &$refs = array())
    {
        $root =& $tree;
        foreach($root as &$node)
        {
            $refs[$node['pid']] =& $node;
            if(!empty($node['children']))
                self::collectRefs($node['children'], $refs);
        }
    }

    /**
     * Find all paths from root to leafs
     *
     * @param array $tree Tree
     * @param array $all Variable to store paths [0: [pid], 1: [alias]]
     * @param array $_path Current path
     */
    protected static function findAllPaths(&$tree, &$all = array(), &$_path = array())
    {
        $root =& $tree;
        foreach($root as $node)
        {
            $_path[$node['level'] - 1] = array($node['pid'], $node['alias']);
            if(!empty($node['children']))
                self::findAllPaths($node['children'], $all, $_path);

            $buff = array_slice($_path, 0, $node['level']);
            $idx = $buff[count($buff) - 1][0];
            foreach($buff as $item)
            {
                $all[$idx][0][] = $item[0];
                $all[$idx][1][] = $item[1];
            }

        }
    }
}