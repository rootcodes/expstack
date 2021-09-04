<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb5 pl15">
      <?= breadcrumb('/admin', lang('Admin'), null, null, lang('Comments-n'));
      $pages = array(
        array('id' => 'comments', 'url' => '/admin/comments', 'content' => lang('All')),
        array('id' => 'comments-ban', 'url' => '/admin/comments/ban', 'content' => lang('Deleted comments')),
      );
      echo tabs_nav($pages, $data['sheet'], $uid);
      ?>

      <?php if (!empty($data['comments'])) { ?>
        <?php foreach ($data['comments'] as $comment) { ?>

          <div id="comment_<?= $comment['comment_id']; ?>">
            <div class="size-13">
              <?= user_avatar_img($comment['user_avatar'], 'small', $comment['user_login'], 'ava'); ?>
              <a class="date" href="/u/<?= $comment['user_login']; ?>"><?= $comment['user_login']; ?></a>
              <span class="mr5 ml5"> &#183; </span>
              <span class="date"><?= $comment['date']; ?></span>

              <span class="mr5 ml5"> &#183; </span>
              <a href="/post/<?= $comment['post_id']; ?>/<?= $comment['post_slug']; ?>">
                <?= $comment['post_title']; ?>
              </a>

              <?php if ($comment['post_type'] == 1) { ?>
                <i class="icon-commenting-o middle"></i>
              <?php } ?>
            </div>
            <div class="comm-telo-body">
              <?= $comment['content']; ?>
            </div>
            <div class="border-bottom mb15 mt5 pb5 size-13 hidden gray">
              + <?= $comment['comment_votes']; ?>
              <span id="cm_dell" class="right comment_link size-13">
                <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action">
                  <?php if ($data['sheet'] == 'comments-ban') { ?>
                    <?= lang('Recover'); ?>
                  <?php } else { ?>
                    <?= lang('Remove'); ?>
                  <?php } ?>
                </a>
              </span>
            </div>
          </div>
        <?php } ?>

      <?php } else { ?>
        <?= no_content('There are no comments'); ?>
      <?php } ?>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/comments'); ?>
  </main>
</div>