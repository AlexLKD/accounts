<?php

/**
 * generate nav bar
 *
 * @param array $pages input arrays
 * @param string $currentPage
 * @return string return navigation menu
 */
function createMenu(array $pages, string $currentPage): string
{

    $menu = '<ul class="nav">';
    foreach ($pages as $page) {
        $activeClass = ($currentPage === $page['url']) ? 'secondary' : 'body-emphasis';
        $menu .= '<li class="nav-item">
        <a href="' . $page['url'] . '" class="nav-link link-' . $activeClass . '" aria-current="page">' . $page['name'] . '</a>
    </li>';
    }
    $menu .= '</ul>';
    return '<nav class="col-11 col-md-7">' . $menu . '</nav>';
}

/**
 * get page title
 *
 * @param array $pages
 * @return string return title of the current page
 */
function getCurrentPageTitle(array $pages): string
{
    foreach ($pages as $page) {
        if ($page['url'] === basename($_SERVER['PHP_SELF'])) {
            return $page['title'];
        }
    }
}
