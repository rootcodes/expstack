<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<div id="contentWrapper">
  <main>
    <h2 class="mb20">
      <?= __($data['sheet'] . '.view'); ?>
      <?php if ($data['count'] != 0) : ?><sup class="gray-600 text-sm"><?= $data['count']; ?></sup><?php endif; ?>
    </h2>

    <?php if (!empty($data['items'])) : ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user, 'delete_fav' => 'yes', 'screening' => $data['screening']]); ?>
    <?php else : ?>
      <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no.bookmarks.sites'), 'icon' => 'bi-info-lg']); ?>
    <?php endif; ?>

    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], url($data['sheet'])); ?>
  </main>
  <aside>
    <div class="box-yellow text-sm mt15"><?= __('web.bookmarks.info'); ?>.</div>
    <?php if (UserData::checkActiveUser()) : ?>
      <div class="box text-sm bg-violet mt15">
        <h3 class="uppercase-box"><?= __('menu'); ?></h3>
        <ul class="menu">
          <?= includeTemplate('/view/default/_block/add-site', ['data' => $data]); ?>
          <?= Tpl::insert('/_block/navigation/menu', ['type' => $data['sheet'], 'list' => config('catalog/menu.user')]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>