<?php $post = $data['post']; ?>
<div class="col-span-1 no-mob center">
  <div class="sticky top80">
    <?= votes($uid['user_id'], $post, 'post', 'size-24 middle', 'block'); ?>
    <div class="pt20">
      <?= favorite_post($uid['user_id'], $post['post_id'], $post['favorite_tid'], 'size-24'); ?>
    </div>
  </div>
</div>
<main class="col-span-8 mb-col-12">
  <article class="post-full br-box-gray br-rd5 bg-white<?php if ($post['post_is_deleted'] == 1) { ?> bg-red-300<?php } ?> mb15 pt0 pr15 pb5 pl15">
    <?php if ($post['post_is_deleted'] == 0 || $uid['user_trust_level'] == 5) { ?>
      <div class="post-body">
        <h1 class="mb0 mt10 size-24">
          <?= $post['post_title']; ?>
          <?php if ($post['post_is_deleted'] == 1) { ?>
            <i class="bi bi-trash blue"></i>
          <?php } ?>
          <?php if ($post['post_closed'] == 1) { ?>
            <i class="bi bi-lock gray"></i>
          <?php } ?>
          <?php if ($post['post_top'] == 1) { ?>
            <i class="bi bi-pin-angle blue"></i>
          <?php } ?>
          <?php if ($post['post_lo'] > 0) { ?>
            <i class="bi bi-award blue"></i>
          <?php } ?>
          <?php if ($post['post_type'] == 1) { ?>
            <i class="bi bi-patch-question green"></i>
          <?php } ?>
          <?php if ($post['post_translation'] == 1) { ?>
            <span class="pt5 pr10 pb5 pl10 gray-light bg-yellow-100 br-rd3 size-14 italic lowercase">
              <?= Translate::get('translation'); ?>
            </span>
          <?php } ?>
          <?php if ($post['post_tl'] > 0) { ?>
            <span class="pt5 pr10 pb5 pl10 gray-light bg-orange-100 br-rd3 italic size-14">
              tl<?= $post['post_tl']; ?>
            </span>
          <?php } ?>
          <?php if ($post['post_merged_id'] > 0) { ?>
            <i class="link-link-ext blue"></i>
          <?php } ?>
        </h1>
        <div class="size-14 lowercase flex gray-light-2">
          <?= $post['post_date_lang']; ?>
          <?php if ($post['modified']) { ?>
            (<?= Translate::get('ed'); ?>)
          <?php } ?>

          <?php if ($uid['user_id']) { ?>
            <?php if ($uid['user_login'] == $post['user_login']  || $uid['user_trust_level'] == 5) { ?>
              <a class="gray-light mr10 ml10" href="<?= getUrlByName('post.edit', ['id' => $post['post_id']]); ?>">
                <?= Translate::get('edit'); ?>
              </a>
            <?php } ?>
            <?php if ($uid['user_login'] == $post['user_login']) { ?>
              <?php if ($post['post_draft'] == 0) { ?>
                <?php if ($post['user_my_post'] == $post['post_id']) { ?>
                  <span class="mu_post gray-light mr10 ml10">+ <?= Translate::get('in-the-profile'); ?></span>
                <?php } else { ?>
                  <a class="add-post-profile gray-light mr10 ml10" data-post="<?= $post['post_id']; ?>">
                    <span class="mu_post"><?= Translate::get('in-the-profile'); ?></span>
                  </a>
                <?php } ?>
              <?php } ?>
            <?php } ?>
            <?php if ($uid['user_trust_level'] == 5) { ?>
              <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action gray-light mr10 ml10">
                <?php if ($post['post_is_deleted'] == 1) { ?>
                  <i class="bi bi-trash red"></i>
                <?php } else { ?>
                  <i class="bi bi-trash"></i>
                <?php } ?>
              </a>
              <a data-id="<?= $post['post_id']; ?>" class="post-recommend gray-light mr10 ml10">
                <?php if ($post['post_is_recommend'] == 1) { ?>
                  <i class="bi bi-lightning blue"></i>
                <?php } else { ?>
                  <i class="bi bi-lightning"></i>
                <?php } ?>
              </a>
            <?php } ?>
            <?= includeTemplate('/_block/show-ip', ['ip' => $post['post_ip'], 'user_trust_level' => $uid['user_trust_level']]); ?>
          <?php } ?>
        </div>
      </div>

      <?php if ($post['post_thumb_img']) { ?>
        <?= post_img($post['post_thumb_img'], $post['post_title'],  'thumb right ml15', 'thumbnails'); ?>
      <?php } ?>

      <div class="post-body max-w780 full">
        <div class="post">
          <?= $post['post_content']; ?>
        </div>
        <?php if ($post['post_url_domain']) { ?>
          <div class="mb15">
            <a rel="nofollow noreferrer ugc" target="_blank" class="bg-blue-800 br-box-blue bg-hover-light-blue pt5 pr15 pb5 pl15 br-rd5 white white-hover" href="<?= $post['post_url']; ?>">
              <?= Translate::get('details are here'); ?> <i class="bi bi-folder-symlink ml5"></i>
            </a>
          </div>
        <?php } ?>
        <?php if ($post['post_url_domain']) { ?>
          <h3 class="uppercase mb5 mt0 font-light size-14 gray"><?= Translate::get('source'); ?></h3>
          <div class="italic m15 mb15 p10 size-14 bg-gray-100 table gray">
            <div>
              <i class="bi bi-link-45deg"></i>
              <a class="gray" href="<?= getUrlByName('domain', ['domain' => $post['post_url_domain']]); ?>">
                <?= $post['post_url_domain']; ?>
              </a>
            </div>
          </div>
        <?php } ?>
        <?= includeTemplate('/_block/related-posts', ['related_posts' => $data['related_posts']]); ?>

        <div class="flex flex-row items-center mb20">
          <?php if (!empty($data['blog'])) { ?>
            <a title="<?= $data['blog'][0]['facet_title']; ?>" class="mr10 gray inline size-14" href="/blog/<?= $data['blog'][0]['facet_slug']; ?>">
              <span class="bg-gray-200 pt5 pr10 pb5 pl10 br-rd5">
                <?= $data['blog'][0]['facet_title']; ?>
              </span>
            </a>
          <?php } ?>

          <?php if (!empty($data['facets'])) { ?>
            <div class="lowercase">
              <?php foreach ($data['facets'] as $topic) { ?>
                <a class="bg-blue-100 bg-hover-green white-hover pt5 pr10 pb5 pl10 br-rd20 blue inline size-14" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>">
                  <?= $topic['facet_title']; ?>
                </a>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
      </div>

      <div class="br-box-gray dark-br-black flex items-center mb5">
        <div class="left p10 no-pc">
          <?= votes($uid['user_id'], $post, 'post', 'size-24 mr5 middle'); ?>
        </div>

        <ul class="list-none w-100 p0 m0 lowercase">
          <li class="left p10">
            <div class="size-14 gray-light-2 mb5">
              <?= Translate::get('created by'); ?>
            </div>
            <div class="center">
              <a title="<?= $post['user_login']; ?>" href="<?= getUrlByName('user', ['login' => $post['user_login']]); ?>">
                <?= user_avatar_img($post['user_avatar'], 'small', $post['user_login'], 'w34 br-rd-50'); ?>
              </a>
            </div>
          </li>
          <li class="left p10 no-mob">
            <div class="size-14 gray-light-2 mb5">
              <?= Translate::get('last answer'); ?>
            </div>
            <div class="center">
              <?php if (!empty($data['last_user']['answer_id'])) { ?>
                <a title="<?= $data['last_user']['user_login']; ?>" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>#answer_<?= $data['last_user']['answer_id']; ?>">
                  <?= user_avatar_img($data['last_user']['user_avatar'], 'small', $data['last_user']['user_login'], 'w34 br-rd-50'); ?>
                </a>
              <?php } else { ?>
                <span class="gray-light-2 size-14">—</span>
              <?php } ?>
            </div>
          </li>
          <li class="left p10 no-mob">
            <div class="size-21 gray-light center mb5">
              <?php if ($post['post_hits_count'] == 0) { ?>
                <span class="gray-light-2 size-14">—</span>
              <?php } else { ?>
                <?= $post['post_hits_count']; ?>
              <?php } ?>
            </div>
            <div class="center size-14 gray-light-2">
              <?= num_word($post['post_hits_count'], Translate::get('num-view'), false); ?>
            </div>
          </li>
          <li class="left p10 no-mob">
            <div class="size-21 gray-light center mb5">
              <?php if ($post['amount_content'] == 0) { ?>
                <span class="gray-light-2 size-14">—</span>
              <?php } else { ?>
                <?= $post['amount_content']; ?>
              <?php } ?>
            </div>
            <div class="center size-14 gray-light-2">
              <?= num_word($post['amount_content'], Translate::get('num-answer'), false); ?>
            </div>
          </li>
        </ul>

        <div class="mr15">
          <?php if ($uid['user_id'] > 0) { ?>
            <?php if (is_array($data['post_signed'])) { ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id size-14 right mt5 bg-gray-100 gray-light-2 br-box-gray br-rd20 center pt5 pr15 pb5 pl15">
                <?= Translate::get('unsubscribe'); ?>
              </div>
            <?php } else { ?>
              <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id size-14 right mt5 bg-gray-200 bg-hover-gray mazarine br-box-gray br-rd20 center pt5 pr15 pb5 pl15">
                + <?= Translate::get('read'); ?>
              </div>
            <?php } ?>
          <?php } else { ?>
            <a class="right size-14 mt5 bg-gray-200 bg-hover-gray mazarine br-box-gray br-rd20 center pt5 pr15 pb5 pl15" href="<?= getUrlByName('login'); ?>">
              + <?= Translate::get('read'); ?>
            </a>
          <?php } ?>
        </div>

        <div class="right ml15 p10 no-pc">
          <?= favorite_post($uid['user_id'], $post['post_id'], $post['favorite_tid'], 'size-21'); ?>
        </div>
      </div>

      <?php if ($post['post_type'] == 0) { ?>
        <?= includeTemplate('/_block/editor/answer-create-editor', [
          'data'    => $post,
          'lang'    => Config::get('general.lang'),
          'type'    => 'answer',
          'user_id' => $uid['user_id']
        ]); ?>
      <?php } ?>

    <?php } else { ?>
      <div class="bg-red-300 p15 center mr10">
        <?= Translate::get('post deleted'); ?>...
      </div>
    <?php } ?>
  </article>
  <div id="comment"></div>
  <?php if ($post['post_draft'] == 0) {
    if ($post['post_type'] == 0) {
      includeTemplate('/_block/comments-view', ['data' => $data, 'post' => $post, 'uid' => $uid]);
      if ($post['post_closed'] == 1) echo no_content(Translate::get('the post is closed'), 'bi bi-door-closed');
    } else {
      includeTemplate('/_block/questions-view', ['data' => $data, 'post' => $post, 'uid' => $uid]);
      if ($post['post_closed'] == 1) echo no_content(Translate::get('the question is closed'), 'bi bi-door-closed');
    }
  } else {
    echo no_content(Translate::get('this is a draft'), 'bi bi-journal-medical');
  } ?>
</main>
<aside class="col-span-3 relative br-rd5 no-mob">
  <div class="br-box-gray bg-white br-rd5 mb15 p15">
    <?php if (!empty($data['topics'])) { ?>
      <h3 class="uppercase mb5 mt0 font-light size-15 gray"><?= Translate::get('topics'); ?></h3>
      <?php foreach ($data['topics'] as $topic) { ?>
        <?php if ($uid['user_id']) { ?>
          <?php if (!$topic['signed_facet_id']) { ?>
            <div data-id="<?= $topic['facet_id']; ?>" data-type="topic" class="focus-id right inline size-14 blue center mt5 mr5">
              <i class="bi bi-plus"></i> <?= Translate::get('read'); ?>
            </div>
          <?php } ?>
        <?php } ?>
        <a class="flex justify-center pt5 pr10 pb5 black dark-white inline size-14" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>">
          <?= facet_logo_img($topic['facet_img'], 'max', $topic['facet_title'], 'w24 mr10 br-box-gray'); ?>
          <?= $topic['facet_title']; ?>
          <div class="gray-light-2 size-14"><?= $topic['facet_short_description']; ?></div>
        </a>
      <?php } ?>
    <?php } ?>
  </div>
  <?php if ($post['post_content_img']) { ?>
    <div class="br-box-gray bg-white br-rd5 mb15">
      <?= post_img($post['post_content_img'], $post['post_title'], 'w-100 p15 post-img br-rd5', 'cover', $post['post_content_img']); ?>
    </div>
  <?php } ?>
  <div class="br-box-gray bg-white br-rd5 mb15 p15">
    <div class="social center" data-url="<?= Config::get('meta.url') . getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>" data-title="<?= $post['post_title']; ?>">
      <a class="size-21 pl15 pr15 gray-light-2" data-id="fb"><i class="bi bi-facebook"></i></a>
      <a class="size-21 pl15 pr15 gray-light-2" data-id="vk">VK</a>
      <a class="size-21 pl15 pr15 gray-light-2" data-id="tw"><i class="bi bi-twitter"></i></a>
    </div>
  </div>
  <?php if ($data['recommend']) { ?>
    <div class="br-box-gray bg-white br-rd5 mb15 sticky top80 p15">
      <h3 class="uppercase mb10 mt0 font-light size-14 gray"><?= Translate::get('recommended'); ?></h3>
      <?php foreach ($data['recommend'] as  $rec_post) { ?>
        <div class="mb15 hidden flex">
          <a class="gray size-15" href="<?= getUrlByName('post', ['id' => $rec_post['post_id'], 'slug' => $rec_post['post_slug']]); ?>">
            <?php if ($rec_post['post_answers_count'] > 0) { ?>
              <div class="p5 pr10 pb5 pl10 bg-green-400 br-rd3 white size-14 center mr15">
                <?= $rec_post['post_answers_count'] ?>
              </div>
            <?php } else { ?>
              <div class="p5 pr10 pb5 pl10 bg-gray-300 br-rd3 gray size-14 center mr15">0</div>
            <?php } ?>
          </a>
          <a class="black dark-white size-14" href="<?= getUrlByName('post', ['id' => $rec_post['post_id'], 'slug' => $rec_post['post_slug']]); ?>">
            <?= $rec_post['post_title']; ?>
          </a>
        </div>
      <?php } ?>
    </div>
  <?php } ?>
</aside>
<script nonce="<?= $_SERVER['nonce']; ?>">
  $(document).ready(function() {
    $('.post-img').on('click', function(e) {
      let src = $(this).attr('src');
      if (src) {
        let img = '<img src="' + src + '">';
        Swal.fire({
          width: '100%',
          showConfirmButton: false,
          title: img
        })
      }
    });
    $(document).on('click', '.msg-flag', function() {
      let post_id = $(this).data('post_id');
      let content_id = $(this).data('content_id');
      let type = $(this).data('type');
      Swal.fire({
        title: '<?= Translate::get('report'); ?>',
        html: '<?= Translate::get('does this violate site rules'); ?>?',
        confirmButtonColor: '#3085d6',
        confirmButtonText: '<?= Translate::get('yes'); ?>',
        cancelButtonText: '<?= Translate::get('no'); ?>',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
          $.ajax({
            url: '/flag/repost',
            method: 'POST',
            data: {
              type: type,
              post_id: post_id,
              content_id: content_id
            },
          })
        }
      })
    });
  });
</script>
</div>
<?= includeTemplate('/_block/wide-footer'); ?>