<?php
function getBreadcrumb() {
    $current_page = basename($_SERVER['PHP_SELF']);
    $current_dir = basename(dirname($_SERVER['PHP_SELF']));
    
    $breadcrumbs = [
        'Home' => '/food/index.php'
    ];
    
    if($current_dir != 'food' && $current_dir != '') {
        $breadcrumbs[ucfirst(str_replace('_', ' ', $current_dir))] = "/food/$current_dir/index.php";
    }
    
    if($current_page != 'index.php') {
        $page_name = str_replace('.php', '', $current_page);
        $page_name = ucfirst($page_name);
        $breadcrumbs[$page_name] = '#';
    }
    
    echo '<div class="breadcrumb">';
    $count = count($breadcrumbs);
    $i = 1;
    foreach($breadcrumbs as $name => $url) {
        if($i == $count) {
            echo "<span>$name</span>";
        } else {
            echo "<a href='$url'>$name</a> > ";
        }
        $i++;
    }
    echo '</div>';
}
?>