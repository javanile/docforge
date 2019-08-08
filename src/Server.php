<?php
/**
 * File description.
 *
 * PHP version 5
 *
 * @category -
 *
 * @author    -
 * @copyright -
 * @license   -
 */

namespace Javanile\DocForge;

class Server extends Scope
{
    /**
     * Render page by server routing.
     */
    public function run()
    {
        if (empty($this->configData['pages']) || !$this->configData['pages']) {
            die('Empty "pages" into docforge.json');
        }

        $this->setCurrentPage($this->getRoutePage());

        return $this->getCurrentPage()->renderize();
    }

    /**
     * @return string
     */
    public function getRoutePage()
    {
        $pages = $this->getPages();
        var_dump($pages);
        $slug = $this->getRouteSlug();

        var_dump($slug);

        $tokens = $this->getTokensBySlug($slug);
        $depth = count($tokens) - 1;

        foreach ($tokens as $index => $token) {
            if (isset($pages[$token]) && is_string($pages[$token]) && $index == $depth) {
                return $this->buildPage($pages[$token], $slug);
            } elseif (!isset($pages[$token])) {
                return $this->getPage404($slug);
            } elseif (is_array($pages[$token])) {
                $pages = $pages[$token];
            }
        }

        //$this->buildPage
        //var_Dump($pages);
        //echo "AAA";

        return $this->getPage404($slug);
    }

    /**
     * Get browser URL tokens for routing.
     *
     * @return array
     */
    public function getRouteSlug()
    {
        $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!preg_match('/\.html$/i', $route)) {
            return $route != '/' ? trim($route, '/').'/index' : 'index';
        }

        return substr($route, 1, strlen($route) - 6);
    }

    /**
     * Get browser URL tokens for routing.
     *
     * @param $slug
     * @return array
     */
    public function getTokensBySlug($slug)
    {
        return explode('/', $slug);
    }
}
