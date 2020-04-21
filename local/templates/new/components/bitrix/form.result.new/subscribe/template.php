<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<section class="block subscribe">
    <div class="wrapper">
        <div class="subscribe-block">
            <form action="<?=POST_FORM_ACTION_URI?>">
                <h2 class="title">Узнавайте первыми о скидках и акциях</h2>
                <div class="form-group">
                    <input class="email" type="email" placeholder="Ваш email" name="email_subscribe">
                    <input type="submit" class="btn blue js-init-action" data-action="send_form" data-id="subscribe" value="Подписаться" >
                </div>
                <div class="politic">Я согласен с условиями предоставления услуг и <a href="<?=SITE_DIR?>/help/polzovatelskoe-soglashenie/" target="_blank">политикой конфиденциальности</a></div>
            </form>
            <div class="image">
                <img src="/local/front/files/img/subscribe-img.png" alt="">
            </div>
        </div>
    </div>
</section>
