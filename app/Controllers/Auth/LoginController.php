<?php

namespace App\Controllers\Auth;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\User\UserModel;
use Validation, Tpl, Meta;

class LoginController extends MainController
{
    // Отправка запроса авторизации
    public function index()
    {
        $email      = Request::getPost('email');
        $password   = Request::getPost('password');
        $rememberMe = Request::getPostInt('rememberme');

        $redirect   = getUrlByName('login');

        Validation::Email($email, $redirect);

        $user = UserModel::userInfo($email);

        if (empty($user['id'])) {
            Validation::ComeBack('no.user', 'error', $redirect);
        }

        // Находится ли в бан- листе
        if (UserModel::isBan($user['id'])) {
            Validation::ComeBack('account.being.verified', 'error', $redirect);
        }

        // Активирован ли E-mail
        if (!UserModel::isActivated($user['id'])) {
            Validation::ComeBack('account.not.activated', 'error', $redirect);
        }

        if (!password_verify($password, $user['password'])) {
            Validation::ComeBack('email.password.not.correct', 'error', $redirect);
        }

        // Если нажал "Запомнить" 
        // Устанавливает сеанс пользователя и регистрирует его
        if ($rememberMe == 1) {
            (new \App\Controllers\Auth\RememberController())->rememberMe($user['id']);
        }

        (new \App\Controllers\Auth\SessionController())->set($user);

        (new \App\Controllers\AgentController())->set($user['id']);

        redirect('/');
    }

    // Страница авторизации
    public function showLoginForm()
    {
        $m = [
            'og'    => false,
            'url'   => getUrlByName('login'),
        ];

        return Tpl::LaRender(
            '/auth/login',
            [
                'meta'  => Meta::get(__('sign.in'), __('login.info'), $m),
                'data'  => [
                    'sheet' => 'sign.in',
                    'type'  => 'login',
                ]
            ]
        );
    }
}
