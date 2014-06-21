<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
<div class="boxContent">
    <section class="mainContent">

        <article id="post-0" class="post error404 no-results not-found fullArticle">
            <header class="entry-header">
                <h1 class="entry-title"><?php _e('This is somewhat embarrassing, isn&rsquo;t it?', 'twentytwelve'); ?></h1>
            </header>

            <div class="entry-content entrytext">
                <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'twentytwelve'); ?></p>
                <?php get_search_form(); ?>
                <br><br>
            </div>
            <!-- .entry-content -->
        </article>
        <!-- #post-0 -->

    </section>
    <!-- #content -->
    <?php get_sidebar(); ?>
</div><!-- #primary -->

<?php get_footer(); ?>