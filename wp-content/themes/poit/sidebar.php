<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<?php if (is_active_sidebar('sidebar-1')) : ?>
<!--<div id="secondary" class="widget-area" role="complementary">-->
<aside id="%1$s" class="widget rightColumn %2$s">
    <h2><?php echo get_week_number(); ?> учебная неделя</h2>
    <?php dynamic_sidebar('sidebar-1'); ?>
</aside>
<!--</div><!-- #secondary -->
<?php endif; ?>