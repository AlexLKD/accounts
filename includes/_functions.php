<?php

function getCurrentPageTitle(array $pages): string
{
    foreach ($pages as $page) {
        if ($page['url'] === basename($_SERVER['PHP_SELF'])) {
            return $page['title'];
        }
    }
}
