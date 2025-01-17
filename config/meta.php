<?php
/*
 * Meta tag settings
 * Настройки мета-тегов
 */

return [
    'url'               => 'https://libarea.ru',
    
    // For removing: nofollow noreferrer noopener
    // Для удаления атрибутов: nofollow noreferrer noopener
    'white_list_hosts'  => ['libarea.ru', 'libarea.com'],
    
    // SEO
    'name'              => 'LibArea',
    'title'             => 'LibArea — сообщество (скрипт мультиблога)',
    'img_path'          => '/assets/images/libarea.jpg',
    
    /*
    * The following language strings are used for the meta description 
    * of the central (main) page, site feed.
    *
    * Следующие языковые строки используются для мета- описания 
    * центральной (главной) странице, ленты сайта.
    */
    'feed_title'        => 'LibArea — сообщество (скрипт мультиблога)',
    'top_title'         => 'LibArea — популярные посты',
    'all_title'         => 'LibArea — все посты ',
    'feed_desc'         => 'Темы по интересам, лента, блоги. Каталог сайтов. Платформа для коллективных блогов, скрипт мультиблога LibArea.',
    'top_desc'          => 'Список популярных постов в ленте сообщества (по количеству ответов). Темы по интересам. Беседы, вопросы и ответы, комментарии. Скрипт LibArea.',
    'all_desc'          => 'Список всех постов в ленте сообщества. Скрипт LibArea.',
    
    'questions_title'   => 'LibArea — вопросы и ответы',
    'questions_desc'    => 'Список всех вопросов и ответов в сообществе в хронологическом порядке. Сервис Q&A LibArea.',
    'posts_title'       => 'LibArea — посты, статьи в ленте',
    'posts_desc'        => 'Посты, статьи в ленте сообщества. Тематические публикации, подборка интересных статей. Скрипт LibArea.', 
    
    // For the main page - the banner title and text
    // Для главной - заголовок и текст баннера
    'banner_title'      => 'LibArea — сообщество',
    'banner_desc'       => 'Темы по интересам. Беседы, вопросы и ответы, комментарии. Скрипт мультиблога',
   
   // For site directory
   // Для каталог сайтов
   'img_path_web'       => '/assets/images/libarea-web.png',
]; 