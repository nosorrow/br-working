<?php

$config = [
/*
 * base_url е пълният  URL адрес до index.php ( http://myhost.com/folder/ )
 * Важно : URL трябва да завършва с " / " - наклонена черта.
 * Непрепоръчително : Ако е празен стринг ще се опита
 *                    да  вземе хоста от глобалните променливи.
 */
    'base_url' => 'http://booking-room.manu/',
    /*
     * Ако не наличен $_SERVER'REQUEST_SCHEME'
     *
     */
    'REQUEST_SCHEME' => 'http',
    /*
     * Ако се използва mod_rewrite за премахване на index.php
     * трябва да бъде празна.
     * При  $config['index_page'] = 'index.php' адреса ще е
     * пр. http://mysite.com/index.php/controller/method/parameter
     */
    'index_page' => '',
    /*
     * Composer autoload се намира в директория vendor
     */
    'composer_autoload' => true,
    /*
     *  whoops ( Pretty error display )
     *  development
     *  production
     */
    'environment' => 'whoops',
    /*
     * true -> записва в лог файл когато 'environment' => 'production'
     */
    'logger'=>true,
    /*
     *Timezone
     *
     * http://php.net/manual/en/timezones.php
     */
    'timezone' => 'Europe/Sofia',
    /*
     * Language
     */
    'lang' => 'bg_BG',
    /*
     * Директория по подразбиране
     * за качване на файлове
     *
     */
    'upload_directory' => 'uploads/',

    /*
     * Session
     * Всички сесии са http-only
     *
     * Ako session_handler => database
     * в папка Libs/Session/session-database.sql
     * е Mysql за таблицата със сесиите
     *
     */
    'session_handler' => 'file', // 'file' or 'database'
    'session_name' => '_br_sess',
    'session_cookie_lifetime' => 7200,
    'session_save_path' => APPLICATION_DIR . 'storage' . DIRECTORY_SEPARATOR . 'tmp',
    'session_secure' => false,
    'session_regenerate' => true,

];

return $config;