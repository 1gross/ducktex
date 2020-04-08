<?php
/**
 * @author Lukmanov Mikhail <lukmanof92@gmail.com>
 */
?>
<section id="personal">
    <div class="wrapper">
        <div class="personal-block">
            <?$APPLICATION->IncludeComponent('bitrix:menu', 'personal_menu', array())?>
            <div class="personal-item">
                <?=$arParams['CONTENT']?>
            </div>
        </div>
</section>
