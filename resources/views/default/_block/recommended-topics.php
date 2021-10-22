<?php if (Request::getUri() == '/') { ?>
  <div class="grid grid-cols-12 gap-4 pr10 pl10 justify-between">
    <?php foreach ($data['topics'] as $topic) { ?>
      <div class="col-span-6 border-box-1 p10">
        <div data-id="<?= $topic['topic_id']; ?>" data-type="topic" class="focus-id right inline br-rd20 blue center mr5">
          <sup><i class="bi bi-plus"></i> <?= lang('read'); ?></sup>
        </div>
        <a class="" title="<?= $topic['topic_title']; ?>" href="<?= getUrlByName('topic', ['slug' => $topic['topic_slug']]); ?>">
          <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'w24 mr5'); ?>
          <?= $topic['topic_title']; ?>
        </a>
        <div class="mt5 size-14 gray-light">
          <?= $topic['topic_description']; ?>
        </div>
      </div>
    <?php } ?>
  </div>
<?php } ?>