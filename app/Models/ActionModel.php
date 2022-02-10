<?php

namespace App\Models;

use UserData;
use DB;

class ActionModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Получим информацию по контенту в зависимости от типа
    public static function getInfoTypeContent($type_id, $type)
    {
        $sql = "select * from " . $type . "s where " . $type . "_id = " . $type_id . "";

        return DB::run($sql)->fetch();
    }

    // Удаление / восстановление контента
    public static function setDeletingAndRestoring($type, $type_id, $status)
    {
        if ($status == 1) {
            $sql = "UPDATE " . $type . "s SET " . $type . "_is_deleted = 0 where " . $type . "_id = :type_id";
        } else {
            $sql = "UPDATE " . $type . "s SET " . $type . "_is_deleted = 1 where " . $type . "_id = :type_id";
        }

        DB::run($sql, ['type_id' => $type_id]);
    }

    // Рекомендованно
    public static function setRecommend($post_id, $status)
    {
        if ($status == 1) {
            $sql = "UPDATE posts SET post_is_recommend = 0 where post_id = :post_id";
        } else {
            $sql = "UPDATE posts SET post_is_recommend = 1 where post_id = :post_id";
        }

        DB::run($sql, ['post_id' => $post_id]);
    }

    // Поиск контента для форм
    public static function getSearch($search, $type)
    { 
        $user = UserData::get();
        $field_id   = $type . '_id';
        if ($type == 'post') {
            $field_tl = 'post_tl';
            $field_name = 'post_title';
            $sql = "SELECT post_id, post_title, post_tl, post_is_deleted FROM posts WHERE post_title LIKE :post_title AND post_is_deleted = 0 AND post_tl = 0 ORDER BY post_id DESC LIMIT 100";
        } elseif ($type == 'user') {
            $field_tl = 'trust_level';
            $field_id = 'id';
            $field_name = 'login';
            $sql = "SELECT id, login, trust_level, activated FROM users WHERE activated = 1 AND login LIKE :login";
        } elseif ($type == 'section') {
            $field_id = 'facet_id';
            $field_tl = 'facet_tl';
            $field_name = 'facet_title';
            $condition = 'AND facet_user_id = ' . $user['id']; // AND facet_type = 'section'
            $sql = "SELECT facet_id, facet_title, facet_tl, facet_type FROM facets 
                    WHERE facet_title LIKE :facet_title  $condition ORDER BY facet_count DESC LIMIT 100";
       } elseif ($type == 'category') {
            $field_id = 'facet_id';
            $field_tl = 'facet_tl';
            $field_name = 'facet_title';
            $condition = 'AND facet_user_id = ' . $user['id']; // AND facet_type = 'category'
            $sql = "SELECT facet_id, facet_title, facet_tl, facet_type FROM facets 
                    WHERE facet_title LIKE :facet_title AND facet_type = 'category' $condition ORDER BY facet_count DESC LIMIT 100"; 
        } else {
            $condition = '';
            if ($user['trust_level'] != UserData::REGISTERED_ADMIN) {
                if ($type == 'blog') {
                    $condition = 'AND facet_user_id = ' . $user['id'];
                }
            }

            $field_id = 'facet_id';
            $field_tl = 'facet_tl';
            $field_name = 'facet_title'; // AND facet_type = '$type'
            $sql = "SELECT facet_id, facet_title, facet_tl, facet_type FROM facets 
                    WHERE facet_title LIKE :facet_title AND facet_type = '$type' $condition ORDER BY facet_count DESC LIMIT 200";
        }

        $result = DB::run($sql, [$field_name => "%" . $search . "%"]);
        $lists  = $result->fetchall();

        $response = [];
        foreach ($lists as $list) {
            $response[] = array(
                "id"    => $list[$field_id],
                "value" => $list[$field_name],
                "tl"    => $list[$field_tl]
            );
        }

        return json_encode($response);
    }

    // Режим заморозки
    public static function addLimitingMode($uid)
    {
        $sql = "UPDATE users SET limiting_mode = 1 where id = :uid";

        return DB::run($sql, ['uid' => $uid]);
    }

    public static function deleteLimitingMode($uid)
    {
        $sql = "UPDATE users SET limiting_mode = 0 where id = :uid";

        return DB::run($sql, ['uid' => $uid]);
    }

    // Get the logs
    // Получим логи  
    public static function getLogs($page, $limit)
    {
        $start  = ($page - 1) * $limit;
        $sql = "SELECT 
                    log_id,
                    log_user_id,
                    log_user_login,
                    log_id_content,
                    log_type_content,
                    log_action_name,
                    log_url_content,
                    log_date
                        FROM users_action_logs ORDER BY log_id DESC LIMIT $start, $limit";

        return DB::run($sql)->fetchAll();
    }

    // Get gthe number of records 
    // Получим количество записей  
    public static function getLogsCount()
    {
        return DB::run("SELECT log_id FROM users_action_logs")->rowCount();
    }

    // Let's write the logs
    // Запишем логи   
    public static function addLogs($params)
    {
        $sql = "INSERT INTO users_action_logs(log_user_id, 
                        log_user_login, 
                        log_id_content, 
                        log_type_content, 
                        log_action_name, 
                        log_url_content,
                        log_date) 
                            VALUES(:log_user_id, 
                                :log_user_login, 
                                :log_id_content, 
                                :log_type_content, 
                                :log_action_name, 
                                :log_url_content,
                                :log_date)";

        return DB::run($sql, $params);
    }
}
