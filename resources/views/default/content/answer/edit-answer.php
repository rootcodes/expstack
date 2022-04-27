<main class="col-two">
  <div class="bg-white items-center justify-between br-gray br-rd5 p15 mb15">

    <a href="/"><?= __('home'); ?></a> /
    <span class="red"><?= __('edit.answer'); ?></span>

    <a class="mb5 block" href="<?= url('post', ['id' => $data['post']['post_id'], 'slug' => $data['post']['post_slug']]); ?>">
      <?= $data['post']['post_title']; ?>
    </a>

    <form action="<?= url('content.change', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
      <?= csrf_field() ?>

      <?= Tpl::insert('/_block/form/editor', ['height'  => '300px', 'content' => $data['content'], 'type' => 'answer', 'id' => $data['post']['post_id']]); ?>

      <div class="pt5 clear">
        <input type="hidden" name="answer_id" value="<?= $data['answer_id']; ?>">
        <?= Html::sumbit(__('edit')); ?>
      </div>
    </form>
</main>