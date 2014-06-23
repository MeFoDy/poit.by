<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header();
?>

<div class="boxContent">
    <section class="mainContent">
        <?php poit_bread_cumps($post); ?>
        <?php $isManyPosts = false; ?>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php echo $isManyPosts ? "<hr>" : ""; ?>
                <article class="fullArticle">
                    <header>
                        <h1 id="post-<?php the_ID(); ?>"><?php the_title(); ?></h1>
                    </header>
                    <div class="entrytext">
                        <?php the_content('<p class="serif">Подробнее &raquo;</p>'); ?>
                    </div>
                    <div class="clearfix"></div>
                </article>
                <?php $isManyPosts = true; ?>
            <?php endwhile;
        endif; ?>

    </section>
    <!-- #content -->

<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>