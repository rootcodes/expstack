<?php

declare(strict_types=1);

namespace App\Services;

use Hleb\Constructor\Handlers\Request;
use App\Models\VotesModel;
use UserData;

class Votes extends Base
{
    public function index()
    {
        $type   = Request::getPost('type');
        $allowed = ['post', 'comment', 'answer', 'item', 'reply'];
        if (!in_array($type, $allowed)) return false;

        $content_id  = Request::getPostInt('content_id');
        if ($content_id <= 0) return false;

        // We check that the participant does not vote for their content
        // Проверяем, чтобы участник не голосовал за свой контент
        // $type = post / answer / comment / item
        $author_id = VotesModel::authorId($content_id, $type);
        if (UserData::getUserId() == $author_id) return false;

        // We check whether the user voted
        // Проверяем, голосовал ли пользователь
        $info = VotesModel::voteStatus($content_id, $type);
        if ($info) return false;

        $ip = Request::getRemoteAddress();

        VotesModel::saveVote($content_id, $ip, $type);
        VotesModel::saveVoteContent($content_id, $type);

        return true;
    }
}
