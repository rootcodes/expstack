<?php

namespace App\Controllers\User;

use Hleb\Constructor\Handlers\Request;
use App\Models\NotificationsModel;
use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\SpaceModel;
use Lori\Content;
use Lori\Config;
use Lori\Base;

class UserController extends \MainController
{
    // Все пользователи
    function index()
    {
        $uid    = Base::getUid();
        $page   = \Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit = 40;
        $usersCount = UserModel::getUsersAllCount();
        $users      = UserModel::getUsersAll($page, $limit, $uid['user_id']);

        Base::PageError404($users);

        $data = [
            'h1'            => lang('Users'),
            'canonical'     => Config::get(Config::PARAM_URL) . '/users',
            'sheet'         => 'users',
            'pagesCount'    => ceil($usersCount / $limit),
            'pNum'          => $page,
            'meta_title'    => lang('Users') . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('desc-user-all') . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        Request::getHead()->addStyles('/assets/css/users.css');

        return view(PR_VIEW_DIR . '/user/users', ['data' => $data, 'uid' => $uid, 'users' => $users]);
    }

    // Страница участника
    function profile()
    {
        $login = \Request::get('login');
        $user  = UserModel::getUser($login, 'slug');

        // Покажем 404
        Base::PageError404($user);

        $post = PostModel::getPostId($user['user_my_post']);

        if (!$user['user_about']) {
            $user['user_about'] = lang('Riddle') . '...';
        }

        $site_name = Config::get(Config::PARAM_NAME);
        $meta_title = sprintf(lang('title-profile'), $user['user_login'], $user['user_name'], $site_name);
        $meta_desc  = sprintf(lang('desc-profile'), $user['user_login'], $user['user_about'], $site_name);

        \Request::getHead()->addStyles('/assets/css/users.css');

        if ($user['user_ban_list'] == 1) {
            \Request::getHead()->addMeta('robots', 'noindex');
        }

        // Просмотры профиля
        if (!isset($_SESSION['usernumbers'])) {
            $_SESSION['usernumbers'] = array();
        }

        if (!isset($_SESSION['usernumbers'][$user['user_id']])) {
            UserModel::userHits($user['user_id']);
            $_SESSION['usernumbers'][$user['user_id']] = $user['user_id'];
        }

        $uid    = Base::getUid();

        // Ограничение на показ кнопки отправить Pm (ЛС, личные сообщения)
        $button_pm  = accessPm($uid, $user['user_id'], Config::get(Config::PARAM_TL_ADD_PM));

        $counts = UserModel::contentCount($user['user_id']);

        $data = [
            'h1'                => $user['user_login'],
            'sheet'             => 'profile',
            'user_created_at'   => lang_date($user['user_created_at']),
            'user_trust_level'  => UserModel::getUserTrust($user['user_id']),
            'posts_count'       => $counts['count_posts'],
            'answers_count'     => $counts['count_answers'],
            'comments_count'    => $counts['count_comments'],
            'spaces_user'       => SpaceModel::getUserCreatedSpaces($user['user_id']),
            'badges'            => UserModel::getBadgeUserAll($user['user_id']),
            'canonical'         => Config::get(Config::PARAM_URL) . '/u/' . $user['user_login'],
            'img'               => Config::get(Config::PARAM_URL) . '/uploads/users/avatars/' . $user['user_avatar'],
            'meta_title'        => $meta_title,
            'meta_desc'         => $meta_desc,
        ];

        return view(PR_VIEW_DIR . '/user/profile', ['data' => $data, 'uid' => $uid, 'user' => $user, 'onepost' => $post, 'button_pm' => $button_pm]);
    }

    // Страница закладок участника
    function userFavorites()
    {
        $uid    = Base::getUid();
        $login  = \Request::get('login');

        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/favorite');
        }

        $fav = UserModel::userFavorite($uid['user_id']);

        $result = array();
        foreach ($fav as $ind => $row) {
            $row['post_date']       = (empty($row['post_date'])) ? $row['post_date'] : lang_date($row['post_date']);
            $row['answer_content']  = Content::text($row['answer_content'], 'text');
            $row['date']            = $row['post_date'];
            $row['post']            = PostModel::getPostId($row['answer_post_id']);
            $result[$ind]           = $row;
        }

        $data = [
            'sheet'         => 'favorites',
            'h1'            => lang('Favorites') . ' ' . $uid['user_login'],
            'meta_title'    => lang('Favorites') . ' ' . $uid['user_login'] . ' | ' . Config::get(Config::PARAM_NAME),
        ];

        return view(PR_VIEW_DIR . '/user/favorite', ['data' => $data, 'uid' => $uid, 'favorite' => $result]);
    }

    // Страница черновиков участника
    function userDrafts()
    {
        $uid    = Base::getUid();
        $login  = \Request::get('login');

        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/drafts');
        }

        $drafts = UserModel::userDraftPosts($uid['user_id']);

        $data = [
            'sheet'         => 'drafts',
            'h1'            => lang('Drafts') . ' ' . $uid['user_login'],
            'meta_title'    => lang('Drafts') . ' ' . $uid['user_login'] . ' | ' . Config::get(Config::PARAM_NAME)
        ];

        return view(PR_VIEW_DIR . '/user/draft-post', ['data' => $data, 'uid' => $uid, 'drafts' => $drafts]);
    }

    // Страница предпочтений пользователя
    public function preferencesPage()
    {
        $uid    = Base::getUid();
        $login  = \Request::get('login');

        if ($login != $uid['user_login']) {
            redirect('/u/' . $uid['user_login'] . '/preferences');
        }

        $focus_posts = NotificationsModel::getFocusPostsListUser($uid['user_id']);

        $result = array();
        foreach ($focus_posts as $ind => $row) {
            $text                           = explode("\n", $row['post_content']);
            $row['post_content_preview']    = Content::text($text[0], 'line');
            $row['lang_num_answers']        = word_form($row['post_answers_count'], lang('Answer'), lang('Answers-m'), lang('Answers'));
            $row['post_date']               = lang_date($row['post_date']);
            $result[$ind]                   = $row;
        }

        $data = [
            'h1'          => lang('Preferences'),
            'sheet'         => 'preferences',
            'meta_title'    => lang('Preferences') . ' | ' . Config::get(Config::PARAM_NAME)
        ];

        return view(PR_VIEW_DIR . '/user/preferences', ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }
}