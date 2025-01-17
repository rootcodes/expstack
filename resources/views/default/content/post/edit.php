<?= insert('/_block/add-js-css');
$post = $data['post']; ?>
<main>
  <h2 class="m0"><?= __('app.edit_' . $post['post_type']); ?></h2>

  <form class="max-w780" action="<?= url('content.change', ['type' => $post['post_type']]); ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?= insert('/_block/form/edit-post', ['post' => $post, 'data' => $data]); ?>
  </form>
</main>
<aside>
  <div class="box bg-beige">
    <h4 class="uppercase-box"><?= __('app.help'); ?></h4>
    <?= __('help.edit_' . $post['post_type']); ?>
  </div>
</aside>