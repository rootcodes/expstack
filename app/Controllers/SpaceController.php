<?php

namespace App\Controllers;
use App\Models\SpaceModel;
use Hleb\Constructor\Handlers\Request;
use SimpleImage;
use Lori\Config;
use Lori\Base;
use Parsedown;

class SpaceController extends \MainController
{
    // Все пространства сайта
    public function index()
    {
        $uid    = Base::getUid();
        $space  = SpaceModel::getSpaceAll($uid['id']);

        // Введем ограничение на количество создаваемых пространств
        $sp             = SpaceModel::getSpaceUserId($uid['id']);
        $count_space    = count($sp);

        $result = Array();
        foreach($space as $ind => $row) {
            $row['users']   = SpaceModel::numSpaceSubscribers($row['space_id']);
            $result[$ind]   = $row;
        }  
        
        $data = [
            'h1'            => lang('All space'),
            'canonical'     => '/space', 
        ];

        // title, description
        Base::Meta(lang('All space'), lang('all-space-desc'), $other = false);
        
        return view(PR_VIEW_DIR . '/space/all', ['data' => $data, 'uid' => $uid, 'space' => $result, 'count_space' => $count_space]);
    }

    // Посты по пространству
    public function SpacePosts($type)
    {
        $uid            = Base::getUid();
        $space_slug     = \Request::get('slug');
        $space_tags_id  = \Request::getInt('tags');
        
        $space = SpaceModel::getSpaceInfo($space_slug);
    
        // Покажем 404
        if(!$space) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
  
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
  
        $posts = SpaceModel::getSpacePosts($space['space_id'], $uid['id'], $space_tags_id, $type);

        $space['space_date']        = Base::ru_date($space['space_date']);
        $space['space_cont_post']   = count($posts);
        $space['space_text']        = $Parsedown->text($space['space_text']);
        
        $result = Array();
        foreach($posts as $ind => $row) {
            $row['post_content_preview']    = Base::cutWords($row['post_content'], 68);
            $row['lang_num_answers']        = Base::ru_num('answ', $row['post_answers_num']);
            $result[$ind]                   = $row;
        }  

        $tags           = SpaceModel::getSpaceTags($space['space_id']);
        $space['users'] = SpaceModel::numSpaceSubscribers($space['space_id']);

        // Отписан участник от пространства или нет
        $space_signed = SpaceModel::getMySpaceHide($space['space_id'], $uid['id']);
        
        if($type == 'feed') {
            $s_title = lang('space-feed-title');
        } else {
            $s_title = lang('space-top-title');
        }

        $data = [
            'h1'            => $space['space_name'],
            'canonical'     => '/s/' . $space['space_slug'], 
        ];

        $meta_title = $space['space_name'] . ' — ' . $s_title;
        $meta_desc  = $space['space_name'] . ' — ' . $s_title . '. ';
        
        // title, description
        Base::Meta($meta_title, $meta_desc, $other = false);

        return view(PR_VIEW_DIR . '/space/space-posts', ['data' => $data, 'uid' => $uid, 'posts' => $result, 'space_info' => $space, 'tags' => $tags, 'space_signed' => $space_signed, 'type' => $type]);
    }

    // Форма изменения пространства
    public function spaceForma()
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');
        $space  = SpaceModel::getSpaceInfo($slug);

        if(!$space){
            redirect('/');
        }

        // Или персонал или автор
        if ($uid['trust_level'] != 5 && $space['space_user_id'] != $uid['id']) {
            redirect('/');
        }

        $data = [
            'h1'            => lang('Change') . ' - ' . $slug,
            'canonical'     => '/***', 
        ];

        $meta_title = lang('Change') . ' - ' . $slug;
        
        Request::getHead()->addStyles('/assets/css/image-uploader.css'); 
        Request::getResources()->addBottomScript('/assets/js/image-uploader.js');
        
        // title, description
        Base::Meta($meta_title, lang('Change'), $other = false);

        return view(PR_VIEW_DIR . '/space/edit-space', ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Страница с информацией по меткам
    public function spaceTagsInfo() 
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');
        $space  = SpaceModel::getSpaceInfo($slug);

        // Или персонал или автор
        if ($uid['trust_level'] != 5 && $space['space_user_id'] != $uid['id']) {
            redirect('/');
        }
        
        $tags = SpaceModel::getSpaceTags($space['space_id']);
        
        $data = [
            'h1'            => lang('Tags'),
            'canonical'     => '/***', 
        ];

        // title, description
        Base::Meta(lang('Tags'), lang('Tags'), $other = false);
 
        return view(PR_VIEW_DIR . '/space/info-space', ['data' => $data, 'uid' => $uid, 'space' => $space, 'tags' => $tags]);
    }
    
    // Форма добавления пространства
    public function addSpacePage() 
    {
        $uid  = Base::getUid();
  
        // Для пользователя с TL < 2 редирект    
        if ($uid['trust_level'] < 2) {
            redirect('/');
        }  
  
        // Если пользователь уже создал пространство
        // Ограничить по TL (добавить!) количество + не показывать кнопку добавления
        $space          = SpaceModel::getSpaceUserId($uid['id']);
        $count_space    = count($space);
        
        if ($count_space >= 3) {
            redirect('/');
        }  
 
        $num_add_space = 3 - $count_space;
 
        $data = [
            'h1'        => lang('Add Space'),
            'canonical' => '/***', 
        ];

        // title, description
        Base::Meta(lang('Add Space'), lang('Add Space'), $other = false);
        
        return view(PR_VIEW_DIR . '/space/add-space', ['data' => $data, 'uid' => $uid, 'num_add_space' => $num_add_space]);
    }
    
    // Добавления пространства
    public function spaceAdd() 
    {
        $uid  = Base::getUid();
        
        // Для пользователя с TL < N       
        if ($uid['trust_level'] < Config::get(Config::PARAM_SPACE)) {
            redirect('/');
        }  
        
        $space_slug     = \Request::getPost('space_slug');
        $space_name     = \Request::getPost('space_name');  
        $space_permit   = \Request::getPostInt('permit');
        $space_feed     = \Request::getPostInt('feed');
        $space_tl       = \Request::getPostInt('space_tl');
     
        if (!preg_match('/^[a-zA-Z0-9]+$/u', $space_slug)) {
            Base::addMsg(lang('url-latin'), 'error');
            redirect('/space/add');
        }
        
        $redirect   = '/space/add';
        Base::Limits($space_name, lang('titles'), '4', '20', $redirect);
        Base::Limits($space_slug, 'slug (URL)', '4', '10', $redirect);
        
        if (preg_match('/\s/', $space_slug) || strpos($space_slug,' ')) {
            Base::addMsg(lang('url-gaps'), 'error');
            redirect('/space/add');
        }
        if (SpaceModel::getSpaceInfo($space_slug)) {
            Base::addMsg(lang('url-already-exists'), 'error');
            redirect('/space/add');
        }
        
        $space_permit   = $space_permit == 1 ? 1 : 0;
        $space_feed     = $space_feed == 1 ? 1 : 0;
        $space_tl       = $space_tl == 1 ? 1 : 0;

        $data = [
            'space_name'            => $space_name,
            'space_slug'            => $space_slug,
            'space_description'     => '',
            'space_color'           => '#56400',
            'space_img'             => 'space_no.png',
            'space_text'            => '',
            'space_date'            => date("Y-m-d H:i:s"),
            'space_category_id'     => 1,
            'space_user_id'         => $uid['id'],
            'space_type'            => 0, 
            'space_permit_users'    => $space_permit,  
            'space_feed'            => $space_feed,
            'space_tl'              => $space_tl,
            'space_is_delete'       => 0,
        ];
 
        // Добавляем пространство
        SpaceModel::AddSpace($data);

        Base::addMsg(lang('space-add-success'), 'success');
        redirect('/space'); 
    }
    
    // Изменение пространства
    public function spaceEdit() 
    {
        $uid            = Base::getUid();
        $space_slug     = \Request::getPost('space_slug');
        $space_id       = \Request::getPost('space_id');
        $space_permit   = \Request::getPostInt('permit');
        $space_feed     = \Request::getPostInt('feed');
        $space_tl       = \Request::getPostInt('space_tl');
        
        $space = SpaceModel::getSpaceId($space_id);

        if(!$space){
            redirect('/');
        }

        // Или персонал или владелец
        if ($uid['trust_level'] != 5 && $space['space_user_id'] != $uid['id']) {
            redirect('/');
        }

        $space_name         = \Request::getPost('space_name');
        $space_description  = \Request::getPost('space_description');
        $space_text         = \Request::getPost('space_text');

        $redirect   = '/space/' . $space['space_slug'] . '/edit';
        if (!preg_match('/^[a-zA-Z0-9]+$/u', $space_slug)) {
            Base::addMsg(lang('url-latin'), 'error');
            redirect($redirect);
        }

        Base::Limits($space_name, lang('titles'), '4', '20', $redirect);
        Base::Limits($space_description, 'Meta-', '60', '190', $redirect);
        Base::Limits($space_slug, 'SLUG', '4', '10', $redirect);

        $name     = $_FILES['images']['name'][0];
        if($name) {
            $size     = $_FILES['images']['size'][0];
            $ext      = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $width_h  = getimagesize($_FILES['images']['tmp_name'][0]);
           
            $valid =  true;
            if (!in_array($ext, array('jpg','jpeg','png','gif'))) {
                $valid = false;
                Base::addMsg('Тип файла не разрешен', 'error');
                redirect('/space/'.$space_slug.'/edit');
            }

            if ($valid) {
                // 110px и 18px
                $path_img       = HLEB_PUBLIC_DIR. '/uploads/spaces/logos/';
                $path_img_small = HLEB_PUBLIC_DIR. '/uploads/spaces/logos/small/';
                $file           = $_FILES['images']['tmp_name'][0];
                $filename       =  's-' . $space['space_id'] . '-' . time();

                $image = new  SimpleImage();
 
                $image
                    ->fromFile($file)  // load image.jpg
                    ->autoOrient()     // adjust orientation based on exif data
                    ->resize(110, 110)
                    ->toFile($path_img . $filename .'.jpeg', 'image/jpeg')
                    ->resize(18, 18)
                    ->toFile($path_img_small . $filename .'.jpeg', 'image/jpeg');
                
                // Удалим, кроме дефолтной
                if($space['space_img'] != 'space_no.png'){
                    chmod($path_img . $space['space_img'], 0777);
                    chmod($path_img_small . $space['space_img'], 0777);
                    unlink($path_img . $space['space_img']);
                    unlink($path_img_small . $space['space_img']);
                }  
                
                $space_img    = $filename . '.jpeg';
                
            } else {
                $space_img = empty($space['space_img']) ? '' : $space['space_img'];
            }
            
        } else {
            $space_img = empty($space['space_img']) ? '' : $space['space_img'];
        }
        
        $space_color = \Request::getPost('color');
        $space_color = empty($space_color) ? $space['space_color'] : $space_color;
        
        $slug = SpaceModel::getSpaceInfo($space_slug);

        if($slug['space_slug'] != $space['space_slug']) {
            if($slug) {
                Base::addMsg(lang('url-already-exists'), 'error');
                redirect('/s/'.$space['space_slug']);
            }
        }
  
        $space_permit   = $space_permit == 1 ? 1 : 0;
        $space_feed     = $space_feed == 1 ? 1 : 0;
        $space_tl       = $space_tl == 1 ? 1 : 0;
        
        $data = [
            'space_id'              => $space_id,
            'space_slug'            => $space_slug,
            'space_name'            => $space_name,
            'space_description'     => $space_description,
            'space_color'           => $space_color,
            'space_text'            => $space_text,
            'space_img'             => $space_img,
            'space_permit_users'    => $space_permit,
            'space_feed'            => $space_feed,
            'space_tl'              => $space_tl,
        ]; 
        
        SpaceModel::setSpaceEdit($data);
        
        Base::addMsg(lang('Change saved'), 'success');
        redirect('/s/' . $space_slug);
    }
    
    // Страница добавления метки (тега) пространства
    public function spaceTagsAddPage()
    {
        $uid    = Base::getUid();
        $slug   = \Request::get('slug');
        $space  = SpaceModel::getSpaceInfo($slug);
        
        // Покажем 404
        if(!$space) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        // Добавлять может только автор и админ
        if ($space['space_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }
      
        $data = [
            'h1'        => lang('Add tag'),
            'canonical' => '/***', 
        ];

        // title, description
        Base::Meta(lang('Add tag'), lang('Add tag'), $other = false);
        
        return view(PR_VIEW_DIR . '/space/add-tag', ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Страница изменение тега пространства
    public function editTagSpacePage()
    {
        $uid            = Base::getUid();
        $slug           = \Request::get('slug');
        $space_tags_id  = \Request::getInt('tags');
        
        $space = SpaceModel::getSpaceInfo($slug);
    
        // Покажем 404
        if(!$space) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        // Редактировать может только автор и админ
        if ($space['space_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }

        $tag = SpaceModel::getTagInfo($space_tags_id);
        
        // Покажем 404
        if(!$tag) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $data = [
            'h1'        => lang('Edit tag'),
            'canonical' => '/***', 
        ];

        // title, description
        Base::Meta(lang('Edit tag'), lang('Edit tag'), $other = false);

        return view(PR_VIEW_DIR . '/space/edit-tag', ['data' => $data, 'uid' => $uid, 'tag' => $tag]);
    }
    
    // Изменяем тег пространства
    public function editTagSpace()
    {
        $uid        = Base::getUid();
        $space_id   = \Request::getPostInt('space_id');
        $tag_id     = \Request::getPostInt('tag_id');
        $st_desc    = \Request::getPost('st_desc');
        $st_title   = \Request::getPost('st_title');
        
        $space = SpaceModel::getSpaceId($space_id);
        
        // Редактировать может только автор и админ
        if ($space['space_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }

        $redirect = '/s/' . $space['space_slug'] . '/' . $tag_id . '/edit';
        Base::Limits($st_title, lang('titles'), '4', '20', $redirect);
        Base::Limits($st_desc, lang('descriptions'), '30', '180', $redirect);
    
        SpaceModel::tagEdit($tag_id, $st_title, $st_desc);

        Base::addMsg(lang('tags-edit-yes'), 'success');
        redirect('/s/' .$space['space_slug']);
    }
    
    // Подписка / отписка от пространств
    public function hide()
    {
        $uid        = Base::getUid();
        $space_id   = \Request::getPostInt('space_id'); 
        $account    = \Request::getSession('account');

        // Запретим действия если участник создал пространство
        $sp_info    = SpaceModel::getSpaceId($space_id);
        if($sp_info['space_user_id'] == $uid['id']) {
            return false;
        }

        SpaceModel::SpaceHide($space_id, $account['user_id']);
        
        return true;
    }

    // Добавления тега
    public function addTagSpace() 
    {
        $uid        = Base::getUid();
        $space_id   = \Request::getPostInt('space_id');
        $st_desc    = \Request::getPost('st_desc');
        $st_title   = \Request::getPost('st_title');
        
        $space = SpaceModel::getSpaceId($space_id);
        
        // Редактировать может только автор и админ
        if ($space['space_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }

        $redirect = '/space/' . $space['space_slug'] . '/tags/add';
        Base::Limits($st_title, lang('titles'), '4', '20', $redirect);
        Base::Limits($st_desc, lang('descriptions'), '30', '180', $redirect);

        // Добавим
        SpaceModel::tagAdd($space['space_id'], $st_title, $st_desc);
        
        Base::addMsg(lang('tags-add-yes'), 'success');
        redirect('/s/' . $space['space_slug']);
    }

}
