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

<div class="boxTopSlider">
<!--    <a href="" class="leftSlide"></a>-->

    <div class="sliderInner">
        <ul id="slider">
            <li>    
                <div class="item">
                    <h4>10 причин поступить на ПОИТ</h4>
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/img/mask1.png" alt=""
                         style="background-image: url(<?php bloginfo('stylesheet_directory'); ?>/img/content/reasons.jpg);">
                    <a href="/abiturientu/10-prichin-postupat-na-poit/" title="">Узнать больше</a>
                </div>
            </li>  
            <li>
                <div class="item">
                    <h4>Предметы</h4>
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/img/mask2.png" alt=""
                         style="background-image: url(<?php bloginfo('stylesheet_directory'); ?>/img/content/subjects.jpg);">
                    <a href="" title="">Узнать больше</a>
                </div>
            </li>    
            <li>
                <div class="item">
                    <h4>Преподаватели</h4>
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/img/mask3.png" alt=""
                         style="background-image: url(<?php bloginfo('stylesheet_directory'); ?>/img/content/teachers.jpg);">
                    <a href="" title="">Узнать больше</a>
                </div>
            </li>    
               <li>
                <div class="item">
                    <h4>Преподаватели</h4>
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/img/mask3.png" alt=""
                         style="background-image: url(<?php bloginfo('stylesheet_directory'); ?>/img/content/teachers.jpg);">
                    <a href="" title="">Узнать больше</a>
                </div>
            </li> 
               <li>
                <div class="item">
                    <h4>Преподаватели</h4>
                    <img src="<?php bloginfo('stylesheet_directory'); ?>/img/mask3.png" alt=""
                         style="background-image: url(<?php bloginfo('stylesheet_directory'); ?>/img/content/teachers.jpg);">
                    <a href="" title="">Узнать больше</a>
                </div>
            </li> 
        </ul>
    </div>
<!--    <a href="" class="rightSlide"></a>-->
</div>
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