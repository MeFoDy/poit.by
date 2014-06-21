<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
<footer class="boxFooter">
    <div class="siteMap">
        <section>
            <header>Навигация</header>
            <ul>
                <li><a href="/" title="">Главная</a></li>
                <li><a href="/feed/" title="" target="_blank">Лента новостей</a></li>
            </ul>
        </section>
        <section>
            <header>О кафедре</header>
            <ul>
                <li><a href="" title="">О кафедре</a></li>
                <li><a href="" title="">Партнеры</a></li>
                <li><a href="" title="">Доска почета</a></li>
            </ul>
        </section>
        <section>
            <header>Студенту</header>
            <ul>
                <li><a href="" title="">Расписание занятий</a></li>
                <li><a href="" title="">Преподаватели</a></li>
                <li><a href="" title="">Предметы</a></li>
                <li><a href="" title="">Информация деканата</a></li>
                <li><a href="/blog/" title="">Новости</a></li>
            </ul>
        </section>
        <section>
            <header>Абитуриенту</header>
            <ul>
                <li><a href="/abiturientu/10-prichin-postupat-na-poit/" title="">10 причин поступить на ПОИТ</a></li>
                <li><a href="" title="">Кем ты можешь стать</a></li>
                <li><a href="" title="">FAQ</a></li>
                <li><a href="" title="">Советы</a></li>
                <li><a href="" title="">История проходных баллов</a></li>
            </ul>
        </section>
    </div>
    <div class="copyright">
        <div class="contacts">
            220013, г. Минск, ул. П.Бровки, 6<br>
            <span class="phone">8 (017) 293-84-66</span>
        </div>
        <div class="poit">“ПОИТ”<img src="<?php bloginfo('stylesheet_directory'); ?>/img/copyright.png">2010 - 2013</div>
    </div>
</footer>
<?php wp_footer(); ?>
</div>
</body>
</html>