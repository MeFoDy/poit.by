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

/**
* * * * * * * * * *
*     SCHEDULE    *
* * * * * * * * * *
*/

function print_debug($obj) {
    echo "<pre>";
    print_r($obj);
    echo "</pre>";
}

function xmlToArraySch($xmlString) {
    $xml = simplexml_load_string($xmlString);
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    return $array;
}

function get_week_number() {
    $currentYear = date('Y');
    $currentStart = mktime(0, 0, 0, 9, 1, $currentYear);
    $nowWeek = time();
    if ($currentStart > $nowWeek)
        $currentYear--;
    $currentStart = mktime(0, 0, 0, 9, 1, $currentYear);
    $weeksNow = date('W');
    $weeksSept = date('W', $currentStart);
    $weekNumber = (52 - $weeksSept + $weeksNow) % 4 + 1;
    return $weekNumber;
}

function prepareGroupSch($group) {
    return preg_replace('/[^0-9]/', "", $group);
}

function prepareName($name) {
    $teacherName = explode(" ", $name);
    $teacherName = $teacherName[0] . " " . mb_substr(trim($teacherName[1]), 0, 1, 'UTF-8') . "." . mb_substr(trim($teacherName[2]), 0, 1, 'UTF-8') . ".";
    return $teacherName;
}

function getScheduleGroupOnlineSch($group) {
    $group = preg_replace('/[^\d]/', "", $group);

    $url = "http://bsuir.by/psched/rest/" . $group;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $result['content'] = curl_exec($ch);
    $result['errorno'] = curl_errno($ch);
    $result['error'] = curl_error($ch);
    if ($result['errorno']) {
        //print curl_error($ch) . '('.$url.')';
        return false;
    }
    curl_close($ch);

    $result['content'] = xmlToArraySch($result['content']);
    return $result;
}

function beatifyScheduleResultFromBsuir($group) {
    $output = array();
    $group = prepareGroupSch( $group );
    $result = getScheduleGroupOnlineSch( $group );
    if ($result === false)
        return false;
    foreach ($result['content']['ROW'] as $value) {
        $attr = $value['@attributes'];
        array_push($output, array(
            'group' => $group,
            'subgroup' => $attr['subgroup'],
            'weekDay' => $attr['weekDay'],
            'timePeriod' => $attr['timePeriod'],
            'weekList' => $attr['weekList'],
            'subject' => $attr['subject'],
            'subjectType' => $attr['subjectType'],
            'auditorium' => $attr['auditorium'],
            'teacher' => $attr['teacher']
            ));
    }
    return $output;
}

function getScheduleGroupOffline($group) {
    $output = array();
    $filename = ABSPATH . 'wp-content/plugins/fksis-schedule/schedule.json';
    if (file_exists($filename) && is_readable($filename)) {
        $json = file_get_contents($filename);
        $lessons = json_decode($json, true);
        foreach ($lessons as $lesson) {
            if ($lesson['group'] == $group) {
                array_push($output, $lesson);
            }
        }
    } else {
        return false;
    }
    return $output;
}

function getScheduleTeacherOffline($name) {
    $output = array();
    $name = prepareName($name);
    $filename = ABSPATH . 'wp-content/plugins/fksis-schedule/schedule.json';
    if (file_exists($filename) && is_readable($filename)) {
        $json = file_get_contents($filename);
        $lessons = json_decode($json, true);
        foreach ($lessons as $lesson) {
            if ($lesson['teacher'] == $name) {
                $flag = true;
                foreach ($output as $key => $value) {
                    if (
                           $lesson['teacher']       == $value['teacher']
                        && $lesson['timePeriod']    == $value['timePeriod']
                        && $lesson['weekDay']       == $value['weekDay']
                        && $lesson['weekList']      == $value['weekList']
                        && $lesson['subject']       == $value['subject']
                        && $lesson['subjectType']   == $value['subjectType']
                        && $lesson['auditorium']    == $value['auditorium']
                        && $lesson['group'] != $value['group']
                        ) 
                    {
                        $flag = false;
                        $output[$key]['group'][5] = 'x';
                    }
                }
                if ($flag) {
                    array_push($output, $lesson);
                }
            }
        }
    } else {
        return false;
    }
    return $output;
}

function getScheduleGroup($group) {
    $group = prepareGroupSch($group);
    $result = beatifyScheduleResultFromBsuir($group);
    if ($result === false) {
        $result = getScheduleGroupOffline($group);
        if ($result === false) {
            return false;
        }
    }
    return $result;
}

function cmp_times($a, $b) {
    if ($a['timePeriod'] == $b['timePeriod'])
        return 0;
    return $a['timePeriod'] > $b['timePeriod'] ? 1 : -1;
}

function weekArray() {
    return array( 0 => 'пн', 1 => 'вт', 2 => 'ср', 3 => 'чт', 4 => 'пт', 5 => 'сб', 6 => 'вс' );
}

function prettyScheduleResult($name, $isTeacher = false) {
    if ($isTeacher) {
        $result = getScheduleTeacherOffline($name);
    } else {
        $result = getScheduleGroup($name);
    }
    if ($result === false) {
        return false;
    }
    $output = array();
    $week = weekArray();
    foreach ($result as $lesson) {
        $output[ array_search($lesson['weekDay'], $week) ][] = $lesson;
    }
    foreach ($output as &$day) {
        uasort($day, 'cmp_times');
    }
    return $output;
}

function printPrettySchedule($name, $isTeacher = false, $today = false) {
    $result = prettyScheduleResult($name, $isTeacher);
    if ($result === false) {
        return false;
    }
    $week = weekArray();
    $hasOutput = false;

    echo <<<EOF
    <div id="schedule-table">
EOF;
    
    foreach ($result as $keyDay => $day) {
        if ($today) {
            $todayWeekDay = (date('w') + 6) % 7;
            if ($keyDay != $todayWeekDay)
                continue;
        }
        $dayName = array_values($day)[0]['weekDay'];
        echo <<<EOD
        <div class="br">
        </div>
        <div class="dh">$dayName</div>
        <table class="c">
            <tbody>
EOD;
        foreach ($day as $lesson) {
            if ($today) {
                $weekNumber = get_week_number();
                if (($lesson['weekList'] != "") && (!preg_match("/$weekNumber/i", $lesson['weekList']))) {
                    continue;
                }
            }
            $subgroup = $lesson['subgroup'];
            $subgroup .= $subgroup == '' ? '' : "п.";
            $subjectTarget = $isTeacher ? $lesson['group'] : $lesson['teacher'];
            $hasOutput = true;

            echo <<<EOL
            <tr>
                <td class="rhs">{$lesson['timePeriod']}</td>
                <td class="cb">
                    <div class="br"></div>
                    <div class="cw">{$lesson['weekList']}</div>
                    <div class="cs">{$subgroup}</div>
                    <div class="cn">{$lesson['subject']}</div>
                    <div class="cr">{$lesson['auditorium']}</div>
                    <div class="ct">{$lesson['subjectType']} {$subjectTarget}</div>
                </td>
            </tr>
EOL;
        }
        echo <<<EOD
            </tbody>
        </table>
EOD;
    }

    echo "</div>";
    return $hasOutput;
}

function printShortTodayGroupSchedule($group) {
    $result = prettyScheduleResult($group);
    if ($result === false) {
        return false;
    }
    $week = weekArray();
    $hasOutput = false;

    echo <<<EOF
    <div id="schedule-table">
EOF;
    
    foreach ($result as $keyDay => $day) {
        
        $todayWeekDay = (date('w') + 6) % 7;
        if ($keyDay != $todayWeekDay)
            continue;
        
        $dayName = array_values($day)[0]['weekDay'];
        echo <<<EOD
        <div class="br">
        </div>
        <div class="dh">$dayName</div>
        <table class="c">
            <tbody>
EOD;
        foreach ($day as $lesson) {
            $weekNumber = get_week_number();
            if ($lesson['weekList'] != "" && !preg_match("/$weekNumber/i", $lesson['weekList'])) {
                continue;
            }
            
            $subgroup = $lesson['subgroup'];
            $subgroup .= $subgroup == '' ? '' : "п.";
            $subjectTarget = $lesson['group'];
            $hasOutput = true;

            echo <<<EOL
            <tr>
                <td class="rhss">{$lesson['timePeriod']}</td>
                <td class="cbs">
                    <div class="br"></div>
                    <div class="cn">{$lesson['subjectType']} {$lesson['subject']} ({$lesson['auditorium']})</div>
                    <div class="ct">{$subgroup}</div>
                </td>
            </tr>
EOL;
        }
        echo <<<EOD
            </tbody>
        </table>
EOD;
    }

    echo "</div>";
    return $hasOutput;
}
    