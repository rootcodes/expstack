<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <h1 class="head"><?= $data['title']; ?></h1>
        <div class="box wide">
            <form class="" action="/login" method="post">
                <?php csrf_field(); ?>
                <div class="boxline">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="ss@sdf.ru">
                </div>
                Страница в разработке (не работает)... <br><br>
                <div class="row">
                    <div class="boxline">
                        <button type="submit" class="button-primary">Сбросить</button>
                    </div>
                    <div class="boxline">
                        <a href="/register">Регистрация</a> &emsp;
                        <a href="/login">Войти</a>
                    </div>
                </div>
            </form>
        </div>

    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>