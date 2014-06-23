<?php
$group = isset($_COOKIE['groupNumber']) ? prepareGroupSch($_COOKIE['groupNumber']) : '';
$group = isset($_POST['groupNumber']) ? prepareGroupSch($_POST['groupNumber']) : $group;
if ($group != '') {
    setcookie("groupNumber", $group, time() + 3600 * 24 * 180, '/');
}
/*
 * Template Name: Schedule
 */

get_header(); ?>

<div class="boxContent">
    <section class="mainContent">
        <article class="fullArticle">
            <?php poit_bread_cumps($post) ?>
            <header>
                <h1>Расписание занятий</h1>
            </header>

            <form action="" method="POST">
                <label>Введите номер группы:</label>
                <input name="groupNumber" type="text" />
                <br>
                <input type="submit" value="Сформировать расписание">
            </form><br><br>
            <?php
            if (true) {
                if ($group != '') {
                    echo "<h2>Результаты поиска расписания для группы $group:</h2>";
                }
                ?>
                <div> 
                   <a onClick="return false;" id="shed_all_a" class="active_schedule">Полное расписание</a>
                   <a onClick="return false;" id="today_shed_a">Сегодня</a>
                   <div id="shedile_all">
                    <?php 
                    $hasOutput = printPrettySchedule($group, false, false);
                    if (!$hasOutput) {
                        echo "Занятий в расписании нет.";
                    }
                    ?>
                </div>
                <div id="shedile_today" style="display:none;">
                    <?php 
                    $hasOutput = printPrettySchedule($group, false, true);
                    if (!$hasOutput) {
                        echo "Занятий на сегодня в расписании не найдено.";
                    }
                    ?>
                </div>
            </div>
            <?php } ?>

        </article>
    </section>
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
    <!-- #content -->

    <?php get_sidebar(); ?>
</div><!-- #primary -->
<?php get_footer(); ?>