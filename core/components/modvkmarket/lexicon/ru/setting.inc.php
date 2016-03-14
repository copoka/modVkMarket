<?php

$_lang['area_modvkmarket_main'] = 'Основные';
$_lang['area_modvkmarket_vksetting'] = 'Настроки VK';

$_lang['setting_modvkmarket_desc_field'] = 'Поле для описания товара';
$_lang['setting_modvkmarket_desc_field_desc'] = 'Укажите поле ресурса или товара miniShop2, которое будет являться описанием товара в маркете ВК. Доступны, в том числе, и ТВ-поля.';
$_lang['setting_modvkmarket_goods_clear_limit'] = 'Лимит очистки за итерацию';
$_lang['setting_modvkmarket_goods_clear_limit_desc'] = 'Количество товаров, которое удаляется из маркета ВК за один цикл.';
$_lang['setting_modvkmarket_goods_sync_limit'] = 'Сколько товаров добавлять за раз';
$_lang['setting_modvkmarket_goods_sync_limit_desc'] = 'Количество товаров, которое добавляется в маркет ВК за один цикл.';
$_lang['setting_modvkmarket_shop_type'] = 'Тип магазина';
$_lang['setting_modvkmarket_shop_type_desc'] = 'Укажите тип маркета ВК. Рекомендуемое значение: default - соответствует необходимому типу приложения ВК Standalone';

$_lang['setting_modvkmarket_access_token'] = 'Токен';
$_lang['setting_modvkmarket_access_token_desc'] = 'Секретный ключ, который получаете путём прохода <a href="https://oauth.vk.com/authorize?client_id={APPID}&scope=market,photos,offline&redirect_uri=https://oauth.vk.com/blank.html&display=page&v=5.21&response_type=token" target="_blank">по этой ссылке</a>, где {APPID} заменяется на ваш ID приложения. Потом получаете строку адреса в браузере вида https://oauth.vk.com/blank.html#access_token={Токен}&expires_in=0&user_id=30314063 и копируете {Токен} в данную настройку.';
$_lang['setting_modvkmarket_app_id'] = 'ID приложения';
$_lang['setting_modvkmarket_app_id_desc'] = 'Значение поля `ID приложения` ВК. Находится в <a href="https://vk.com/apps?act=manage" target="_blank">настройках созданного вами приложения в ВК</a>.';
$_lang['setting_modvkmarket_app_secret'] = 'Защищенный ключ';
$_lang['setting_modvkmarket_app_secret_desc'] = 'Значение поля `Защищенный ключ` ВК. Находится в <a href="https://vk.com/apps?act=manage" target="_blank">настройках созданного вами приложения в ВК</a>.';
