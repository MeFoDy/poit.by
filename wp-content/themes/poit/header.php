<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width"/>
    <title><?php wp_title('|', true, 'right'); ?></title>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>"/>
    <link rel="alternate" type="text/xml" title="RSS .92" href="<?php bloginfo('rss_url'); ?>"/>
    <link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>"/>
    <script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
    <?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
    <!--[if lt IE 9]>
    <script src="<http://poit.by/wp-content/themes/poit/js/html5.js" type="text/javascript"></script>
    <![endif]-->
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="boxWrapper">
    <header class="boxHeader">
        <hr>
        <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
           target="_parent" class="logo"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/logo.png"
                                              alt="Логотип"></a>

        <form method="get" id="searchform" action="<?php echo site_url(); ?>">
            <div class="search">
                <input value="<?php the_search_query(); ?>" name="s" type="text"
                       placeholder="введите строку для поиска">
                <input type="submit" id="searchsubmit" value="">
            </div>
        </form>
        <h3><?php bloginfo('description'); ?></h3>
        <nav>
            <?php wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'nav-menu')); ?>
        </nav>
        <a href="http://bsuir.by" title="" class="bsuirLogo" target="_blank"><img
                src="<?php bloginfo('stylesheet_directory'); ?>/img/bsuirIcon.png" alt=""></a>
    </header>