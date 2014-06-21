<?php

update_option( 'siteurl', 'http://localhost/poit.by/');
update_option( 'home', 'http://localhost/poit.by/' );
/**
 * Twenty Twelve functions and definitions.
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
/**
 * Sets up the content width value based on the theme's design and stylesheet.
 */
if (!isset($content_width))
    $content_width = 625;

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 *     custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_setup()
{
    /*
    * Makes Twenty Twelve available for translation.
    *
    * Translations can be added to the /languages/ directory.
    * If you're building a theme based on Twenty Twelve, use a find and replace
    * to change 'twentytwelve' to the name of your theme in all the template files.
    */
    load_theme_textdomain('twentytwelve', get_template_directory() . '/languages');

    // This theme styles the visual editor with editor-style.css to match the theme style.
    add_editor_style();

    // Adds RSS feed links to <head> for posts and comments.
    add_theme_support('automatic-feed-links');

    // This theme supports a variety of post formats.
    add_theme_support('post-formats', array('aside', 'image', 'link', 'quote', 'status'));

    // This theme uses wp_nav_menu() in one location.
    register_nav_menu('primary', __('Primary Menu', 'twentytwelve'));

    /*
    * This theme supports custom background color and image, and here
    * we also set up the default background color.
    */
    add_theme_support('custom-background', array(
        'default-color' => 'e6e6e6',
    ));

    // This theme uses a custom image size for featured images, displayed on "standard" posts.
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(624, 9999); // Unlimited height, soft crop
}

add_action('after_setup_theme', 'twentytwelve_setup');

/**
 * Adds support for a custom header image.
 */
require(get_template_directory() . '/inc/custom-header.php');

/**
 * Enqueues scripts and styles for front-end.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_scripts_styles()
{
    global $wp_styles;

    /*
    * Adds JavaScript to pages with the comment form to support
    * sites with threaded comments (when in use).
    */
    if (is_singular() && comments_open() && get_option('thread_comments'))
        wp_enqueue_script('comment-reply');

    /*
    * Adds JavaScript for handling the navigation menu hide-and-show behavior.
    */
    wp_enqueue_script('twentytwelve-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true);

    /*
    * Loads our special font CSS file.
    *
    * The use of Open Sans by default is localized. For languages that use
    * characters not supported by the font, the font can be disabled.
    *
    * To disable in a child theme, use wp_dequeue_style()
    * function mytheme_dequeue_fonts() {
    *     wp_dequeue_style( 'twentytwelve-fonts' );
    * }
    * add_action( 'wp_enqueue_scripts', 'mytheme_dequeue_fonts', 11 );
    */

    /* translators: If there are characters in your language that are not supported
by Open Sans, translate this to 'off'. Do not translate into your own language. */
    if ('off' !== _x('on', 'Open Sans font: on or off', 'twentytwelve')) {
        $subsets = 'latin,latin-ext';

        /* translators: To add an additional Open Sans character subset specific to your language, translate
this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language. */
        $subset = _x('no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'twentytwelve');

        if ('cyrillic' == $subset)
            $subsets .= ',cyrillic,cyrillic-ext';
        elseif ('greek' == $subset)
            $subsets .= ',greek,greek-ext'; elseif ('vietnamese' == $subset)
            $subsets .= ',vietnamese';

        $protocol = is_ssl() ? 'https' : 'http';
        $query_args = array(
            'family' => 'Open+Sans:400italic,700italic,400,700',
            'subset' => $subsets,
        );
        wp_enqueue_style('twentytwelve-fonts', add_query_arg($query_args, "$protocol://fonts.googleapis.com/css"), array(), null);
    }

    /*
    * Loads our main stylesheet.
    */
    wp_enqueue_style('twentytwelve-style', get_stylesheet_uri());

    /*
    * Loads the Internet Explorer specific stylesheet.
    */
    wp_enqueue_style('twentytwelve-ie', get_template_directory_uri() . '/css/ie.css', array('twentytwelve-style'), '20121010');
    $wp_styles->add_data('twentytwelve-ie', 'conditional', 'lt IE 9');
}

add_action('wp_enqueue_scripts', 'twentytwelve_scripts_styles');

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Twenty Twelve 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function twentytwelve_wp_title($title, $sep)
{
    global $paged, $page;

    if (is_feed())
        return $title;

    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && (is_home() || is_front_page()))
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2)
        $title = "$title $sep " . sprintf(__('Page %s', 'twentytwelve'), max($paged, $page));

    return $title;
}

add_filter('wp_title', 'twentytwelve_wp_title', 10, 2);

/**
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_page_menu_args($args)
{
    if (!isset($args['show_home']))
        $args['show_home'] = true;
    return $args;
}

add_filter('wp_page_menu_args', 'twentytwelve_page_menu_args');

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_widgets_init()
{
    register_sidebar(array(
        'name' => __('Main Sidebar', 'twentytwelve'),
        'id' => 'sidebar-1',
        'description' => __('Appears on posts and pages except the optional Front Page template, which has its own widgets', 'twentytwelve'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<header class="widget-title">',
        'after_title' => '</header>',
    ));

    register_sidebar(array(
        'name' => __('First Front Page Widget Area', 'twentytwelve'),
        'id' => 'sidebar-2',
        'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<header class="widget-title">',
        'after_title' => '</header>',
    ));

    register_sidebar(array(
        'name' => __('Second Front Page Widget Area', 'twentytwelve'),
        'id' => 'sidebar-3',
        'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'twentytwelve'),
        'before_widget' => '<aside id="%1$s" class="widget rightColumn %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'twentytwelve_widgets_init');

if (!function_exists('twentytwelve_content_nav')) :

    /**
     * Displays navigation to next/previous pages when applicable.
     *
     * @since Twenty Twelve 1.0
     */
    function twentytwelve_content_nav($html_id)
    {
        global $wp_query;

        $html_id = esc_attr($html_id);

        if ($wp_query->max_num_pages > 1) :
            ?>
        <nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
            <h3 class="assistive-text"><?php _e('Post navigation', 'twentytwelve'); ?></h3>

            <div class="nav-previous alignleft"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'twentytwelve')); ?></div>
            <div class="nav-next alignright"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'twentytwelve')); ?></div>
        </nav><!-- #<?php echo $html_id; ?> .navigation -->
        <?php
        endif;
    }

endif;

if (!function_exists('poit_bread_cumps')) :

    /**
     * Displays navigation to next/previous pages when applicable.
     *
     * @since Twenty Twelve 1.0
     */
    function poit_bread_cumps($post)
    {
        $ancs = get_post_ancestors($post); // array of IDs
        if (!empty($ancs)) {
            $parentID = $ancs[0];
            echo "<div class=\"pagination\">
			<span class=\"pic\"></span>";
            $ancs = array_reverse($ancs);
            foreach ($ancs as $anc) {
                $anc_title = get_the_title($anc);
                echo '<a href="' . get_permalink($anc) . '" title="' .
                    $anc_title . '">' . $anc_title . '</a>>';
            }
            echo "</div>";
        }
    }

endif;

if (!function_exists('twentytwelve_comment')) :

    /**
     * Template for comments and pingbacks.
     *
     * To override this walker in a child theme without modifying the comments template
     * simply create your own twentytwelve_comment(), and that function will be used instead.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @since Twenty Twelve 1.0
     */
    function twentytwelve_comment($comment, $args, $depth)
    {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type) :
            case 'pingback' :
            case 'trackback' :
                // Display trackbacks differently than normal comments.
                ?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                    <p><?php _e('Pingback:', 'twentytwelve'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('(Edit)', 'twentytwelve'), '<span class="edit-link">', '</span>'); ?></p>
                    <?php
                break;
            default :
                // Proceed with normal comments.
                global $post;
                ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="comment">
                        <header class="comment-meta comment-author vcard">
                            <?php
                            echo get_avatar($comment, 44);
                            printf('<cite class="fn">%1$s %2$s</cite>', get_comment_author_link(),
                                // If current post author is also comment author, make it known visually.
                                ($comment->user_id === $post->post_author) ? '<span> ' . __('Post author', 'twentytwelve') . '</span>' : ''
                            );
                            printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>', esc_url(get_comment_link($comment->comment_ID)), get_comment_time('c'),
                                /* translators: 1: date, 2: time */
                                sprintf(__('%1$s at %2$s', 'twentytwelve'), get_comment_date(), get_comment_time())
                            );
                            ?>
                        </header>
                        <!-- .comment-meta -->

                        <?php if ('0' == $comment->comment_approved) : ?>
                        <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'twentytwelve'); ?></p>
                        <?php endif; ?>

                        <section class="comment-content comment">
                            <?php comment_text(); ?>
                            <?php edit_comment_link(__('Edit', 'twentytwelve'), '<p class="edit-link">', '</p>'); ?>
                        </section>
                        <!-- .comment-content -->

                        <div class="reply">
                            <?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'twentytwelve'), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                        </div>
                        <!-- .reply -->
                    </article>
                    <!-- #comment-## -->
                <?php
                break;
        endswitch; // end comment_type check
    }

endif;

if (!function_exists('twentytwelve_entry_meta')) :

    /**
     * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
     *
     * Create your own twentytwelve_entry_meta() to override in a child theme.
     *
     * @since Twenty Twelve 1.0
     */
    function twentytwelve_entry_meta()
    {
        // Translators: used between list items, there is a space after the comma.
        $categories_list = get_the_category_list(__(', ', 'twentytwelve'));

        // Translators: used between list items, there is a space after the comma.
        $tag_list = get_the_tag_list('', __(', ', 'twentytwelve'));

        $date = sprintf('<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>', esc_url(get_permalink()), esc_attr(get_the_time()), esc_attr(get_the_date('c')), esc_html(get_the_date())
        );

        $author = sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>', esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', 'twentytwelve'), get_the_author())), get_the_author()
        );

        // Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
        if ($tag_list) {
            $utility_text = __('This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve');
        } elseif ($categories_list) {
            $utility_text = __('This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve');
        } else {
            $utility_text = __('This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'twentytwelve');
        }

        printf(
            $utility_text, $categories_list, $tag_list, $date, $author
        );
    }

endif;

/**
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Twenty Twelve 1.0
 *
 * @param array Existing class values.
 * @return array Filtered class values.
 */
function twentytwelve_body_class($classes)
{
    $background_color = get_background_color();

    if (!is_active_sidebar('sidebar-1') || is_page_template('page-templates/full-width.php'))
        $classes[] = 'full-width';

    if (is_page_template('page-templates/front-page.php')) {
        $classes[] = 'template-front-page';
        if (has_post_thumbnail())
            $classes[] = 'has-post-thumbnail';
        if (is_active_sidebar('sidebar-2') && is_active_sidebar('sidebar-3'))
            $classes[] = 'two-sidebars';
    }

    if (empty($background_color))
        $classes[] = 'custom-background-empty';
    elseif (in_array($background_color, array('fff', 'ffffff')))
        $classes[] = 'custom-background-white';

    // Enable custom font class only if the font CSS is queued to load.
    if (wp_style_is('twentytwelve-fonts', 'queue'))
        $classes[] = 'custom-font-enabled';

    if (!is_multi_author())
        $classes[] = 'single-author';

    return $classes;
}

add_filter('body_class', 'twentytwelve_body_class');

/**
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_content_width()
{
    if (is_page_template('page-templates/full-width.php') || is_attachment() || !is_active_sidebar('sidebar-1')) {
        global $content_width;
        $content_width = 960;
    }
}

add_action('template_redirect', 'twentytwelve_content_width');

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @since Twenty Twelve 1.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function twentytwelve_customize_register($wp_customize)
{
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
}

add_action('customize_register', 'twentytwelve_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_customize_preview_js()
{
    wp_enqueue_script('twentytwelve-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array('customize-preview'), '20120827', true);
}

add_action('customize_preview_init', 'twentytwelve_customize_preview_js');

add_image_size('loopThumb', 90, 135, true);

function cmp($a, $b)
{
    if ($a['day'] == $b['day']) {
        return 0;
    }
    return ($a['day'] < $b['day']) ? -1 : 1;
}

function cmp_time($a, $b)
{
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

function getSchedule($namePrepod = "")
{
    $namePrepod = explode(" ", $namePrepod);
    $namePrepod = $namePrepod[0] . " " . mb_substr(trim($namePrepod[1]), 0, 1, 'UTF-8') . "." . mb_substr(trim($namePrepod[2]), 0, 1, 'UTF-8');

    $result_arr = array();
    $result_poit_sort = array();
    $result_poit_sort_day = array();
    if ($namePrepod != "") {
        $url = "http://fksis.bsuir.by/wps/schedule/api?classes=1";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $result['content'] = curl_exec($ch);
        $result['errorno'] = curl_errno($ch);
        $result['error'] = curl_error($ch);
        if ($result['errorno'])
            //print curl_error($ch) . '('.$url.')';
            return 0;
        curl_close($ch);
        $result_poit = Array();
        if (!$result['errorno']) {
            $schedule = json_decode($result['content'], true);
            $day = array("Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс");
            $time = array(0 => "08:00 09:35", "09:45 11:20", "11:40 13:15", "13:25 15:00", "15:20 16:55", "17:05 18:40", "18:45 20:20", "20:25 22:00");
            // echo "<pre>"; print_r($schedule); echo "</br>";
            foreach ($schedule as $item) {
                if (isset($item['teacher']))
                    if (strpos(" " . $item['teacher'], $namePrepod)) {
                        if ($item['type'] == "лк") {
                            $item['group'] = substr($item['group'], 0, strlen($item['group']) - 1) . 'х';
                        }
                        if ($item['weeks'] == "1234") {
                            $item['weeks'] = "";
                        } else {
                            $item['weeks'] = preg_replace('/(\d)(\d)/', "$1,$2", $item['weeks']);
                            $item['weeks'] = preg_replace('/(\d)(\d)/', "$1,$2", $item['weeks']);
                        }
                        if ($item['subgroups'] == "12") {
                            $item['subgroups'] = "";
                        } else {
                            $item['subgroups'] = $item['subgroups'] . "п";
                        }

                        $flag = true;

                        foreach ($result_poit_sort_day[$item['day']][$item['timeSlot']] as &$slot) {
                            if (
                                $slot['subgroups'] == $item['subgroups']
                                && $slot['group'] == $item['group']
                                && $slot['room'] == $item['room']
                            ) {
                                if ($slot['weeks'] != $item['weeks']) {
                                    $slot['weeks'] .= $item['weeks'];
                                    $slot['weeks'] = str_replace(',', "", $slot['weeks']);
                                    $arr = str_split($slot['weeks']);
                                    asort($arr);
                                    $slot['weeks'] = implode('', $arr);
                                    if ($slot['weeks'] == "1234") {
                                        $slot['weeks'] = "";
                                    } else {
                                        $slot['weeks'] = preg_replace('/(\d)(\d)/', "$1,$2", $slot['weeks']);
                                        $slot['weeks'] = preg_replace('/(\d)(\d)/', "$1,$2", $slot['weeks']);
                                    }
                                }
                                $flag = false;
                            }

                        }

                        if ($flag)
                            $result_poit_sort_day[$item['day']][$item['timeSlot']][] = $item;
                    }
            }
            //   echo "<pre>"; print_r($result_poit_sort_day);
            ksort($result_poit_sort_day);
            //    echo "<pre>"; print_r($result_poit_sort_day);
            foreach ($result_poit_sort_day as $key => $item) {
                $result_poit[$day[$key]] = $item;
            }

            //  echo "<pre>"; print_r($result_poit);
            foreach ($result_poit as $key => $value) {
                $arr_temp = $result_poit[$key];
                ksort($arr_temp);
                //uasort($arr_temp, 'cmp_time');
                //   print_r($arr_temp);
                $result_poit[$key] = $arr_temp;
            }

            //   echo "<pre>"; print_r($result_poit);
            foreach ($result_poit as $key => $item) {
                foreach ($item as $key_1 => $value) {
                    $result_poit_sort[$key][$time[$key_1]] = $value;
                }
            }
            //  echo "<pre>"; print_r($result_poit_sort); die;

            return $result_poit_sort;
        }
    }
}

function get_shedile_wp($namePrepod)
{

    $namePrepod = explode(" ", $namePrepod);
    //print_r($namePrepod);	die;
    $arr = getSchedule($namePrepod[0]);
    uasort($arr, 'cmp');
    $day = array("Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс");
    if (!empty($arr)) {
        return $arr;
//			echo " </br><table>"; 
//		foreach($arr as $item) {
//		   
//			echo "<tr>";
//				echo "Предмет: ".$item['name']."</br>";
//				echo "День недели: ".$day[$item['day']]."</br>";
//				echo "Учебные недеи: ".$item['weeks']."</br>";	
//				echo "Время: ".$item['timeSlot']."</br>";
//				echo "Группа: ".$item['group']."</br>";
//				echo "Подгруппа:  ".$item['subgroups']."</br>";
//				echo "Тип занятия: ".$item['type']."</br></br></br>";
//			echo "</tr>";	
//		}
//			echo "</table>";
    } else {
        return array();
    }
}

function get_shedile_today_wp($namePrepod)
{
    $schedule = getSchedule($namePrepod);

    $weekNumber = get_week_number();
    $day = array("Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс");
    $dayToday = (int)date('w', time()) - 1;
    if ($dayToday < 0)
        $dayToday = 6;
    if (!isset($schedule[$day[$dayToday]])) {
        return false;
    }
    $result[$day[$dayToday]] = array();
    foreach ($schedule[$day[$dayToday]] as $time_key => $time_array) {
        foreach ($time_array as $lesson) {
            if (mb_strpos($lesson['weeks'], $weekNumber) !== false || $lesson['weeks'] == '') {
                $result[$day[$dayToday]][$time_key][] = $lesson;
            }
        }
    }
    if (!$result[$day[$dayToday]]) {
        return false;
    }
    return $result;
}

function get_week_number() {
    $etalonWeek = mktime(0, 0, 0, 9, 1, 2012);
    $nowWeek = time();
    $weekNumber = (($nowWeek - $etalonWeek) / (3600 * 24 * 7)) % 4 + 1;
    return $weekNumber;
}

    