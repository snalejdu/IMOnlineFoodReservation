<?php
function getBreadcrumb() {
    $current_page = basename($_SERVER['PHP_SELF']);
    $current_dir  = basename(dirname($_SERVER['PHP_SELF']));

    $breadcrumbs = ['Home' => '/food/index.php'];

    if ($current_dir !== 'food' && $current_dir !== '') {
        $breadcrumbs[ucfirst(str_replace('_', ' ', $current_dir))] = "/food/$current_dir/index.php";
    }

    if ($current_page !== 'index.php') {
        $page_name = ucfirst(str_replace('.php', '', $current_page));
        $breadcrumbs[$page_name] = '#';
    }

    $total = count($breadcrumbs);
    $i     = 1;

    echo '<nav class="breadcrumb-nav" aria-label="Breadcrumb">';
    echo '<ol class="breadcrumb-list">';
    foreach ($breadcrumbs as $name => $url) {
        if ($i === $total) {
            echo "<li class=\"breadcrumb-item breadcrumb-current\" aria-current=\"page\">$name</li>";
        } else {
            echo "<li class=\"breadcrumb-item\"><a href=\"$url\" class=\"breadcrumb-link\">$name</a></li>";
            echo '<li class="breadcrumb-sep" aria-hidden="true">';
            echo '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>';
            echo '</li>';
        }
        $i++;
    }
    echo '</ol>';
    echo '</nav>';
}
?>

<style>
.breadcrumb-nav {
    margin-bottom: 24px;
}

.breadcrumb-list {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.breadcrumb-item {
    font-size: 12.5px;
    font-family: 'Inter', sans-serif;
}

.breadcrumb-link {
    color: #7c3aed;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.15s;
}

.breadcrumb-link:hover {
    color: #a78bfa;
    text-decoration: underline;
}

.breadcrumb-current {
    color: #5e5a7a;
    font-weight: 400;
}

.breadcrumb-sep {
    color: #5e5a7a;
    display: flex;
    align-items: center;
}
</style>