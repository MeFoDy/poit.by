<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$teachers = get_post_meta($post->ID, 'teacher_info', true);
$current_teacher = array();
foreach ($teachers as $teacher) {
    $current_teacher = $teacher;
}

$current_teacher['publication'] = preg_replace('/\n/', '</li><li>', $current_teacher['publication']);
$current_teacher['courses'] = preg_replace('/\n/', '</li><li>', $current_teacher['courses']);
$current_teacher['science'] = preg_replace('/\n/', '</li><li>', $current_teacher['science']);
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php poit_bread_cumps($post) ?>
    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
    </header>
    <span class="teacher-position"><?php echo $current_teacher['position']; ?></span>

<?php the_post_thumbnail("full", array("class" => "right-full-image")); ?>
    <p></p>
    <p><strong>Аудитория:</strong> <?php echo $current_teacher['audience'] ?></p>
    <p><strong>Телефон:</strong> <?php echo $current_teacher['phone'] ?></p>
    <p><strong>E-Mail:</strong> <?php echo $current_teacher['e-mail'] ?></p>
<?php /* <div class="clearfix"></div> */ ?>

    <div class="teacher-info">
        <p><strong>Читаемые курсы:</strong></p>
        <ul><li><?php echo $current_teacher['courses'] ?></li></ul>

        <p><strong>Направления научных исследований:</strong></p>
        <ul><li><?php echo $current_teacher['science'] ?></li></ul>

        <?php if (trim($current_teacher['publication'])): ?>
        <p><strong>Основные публикации:</strong></p>
        <ol><li><?php echo $current_teacher['publication'] ?></li></ol>
        <?php endif; ?>
    </div>

    <div class="entry-content">
<?php the_content(); ?>
    </div><!-- .entry-content -->
    <?php
        if (true) {
            $fio = get_the_title();
    ?>
    <div> 
       <a onClick="return false;" id="shed_all_a" class="active_schedule">Полное расписание</a>
       <a onClick="return false;" id="today_shed_a">Сегодня</a>
        <div id="shedile_all">
            <?php 
            $hasOutput = printPrettySchedule($fio, true, false);
            if (!$hasOutput) {
                echo "Занятий в расписании нет.";
            }
            ?>
        </div>
        <div id="shedile_today" style="display:none;">
            <?php 
            $hasOutput = printPrettySchedule($fio, true, true);
            if (!$hasOutput) {
                echo "Занятий на сегодня в расписании не найдено.";
            }
            ?>
        </div>
    </div>
    <?php } ?>
</article><!-- #post -->

<script>
            $('#shedile_today').hide();
            $('#shed_all_a').click(function() {
                $('#shedile_all').show();
                $('#today_shed_a').attr("class", "");
                $('#shed_all_a').attr("class", "active_schedule");
                $('#shedile_today').hide();
                return false;
            });
            $('#today_shed_a').click(function() {
                $('#shedile_all').hide();
                $('#today_shed_a').attr("class", "active_schedule");
                $('#shed_all_a').attr("class", "");
                $('#shedile_today').show();
                return false;
            });
</script>
