<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$teachers = get_post_meta( $post->ID, 'teacher_info', true );
$current_teacher = array();
foreach( $teachers as $teacher){
    $current_teacher = $teacher;
}

$current_teacher['publication'] = preg_replace('/\n/', '</li><li>', $current_teacher['publication']);
$current_teacher['courses'] = preg_replace('/\n/', '</li><li>', $current_teacher['courses']);
$current_teacher['science'] = preg_replace('/\n/', '</li><li>', $current_teacher['science']);

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
            <a href="<?php the_permalink(); ?>"
               title="<?php echo esc_attr(sprintf(__('Permalink to %s', 'twentytwelve'), the_title_attribute('echo=0'))); ?>"
               rel="bookmark"><h2 class="entry-title"><?php the_title(); ?></h2></a>
		</header>
        <span class="teacher-position"><?php echo $current_teacher['position']; ?></span>

        <?php the_post_thumbnail('loopThumb', array("class" => "left-full-image")); ?>
        <p></p>
        <p><strong>Аудитория:</strong> <?php    echo $current_teacher['audience'] ?></p>
        <p><strong>Телефон:</strong> <?php      echo $current_teacher['phone'] ?></p>
        <p><strong>E-Mail:</strong> <?php       echo $current_teacher['e-mail'] ?></p>
        <p><a href="<?php the_permalink(); ?>">Подробнее</a></p>
        <div class="clearfix"></div>


		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->
	</article><!-- #post -->
