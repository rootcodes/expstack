<main>
  <h1 class="title-max"><?= __('app.' . $data['sheet']); ?></h1>

  <div class="p15 bg-violet max-w300 mb-none right">
    <?= __('auth.mail_confirm'); ?>
  </div>

  <form class="max-w300" action="<?= url('register.add'); ?>" method="post">
    <?php csrf_field(); ?>
    <?= insert('/_block/form/registration'); ?>
  </form>

  <p><?= __('app.agree_rules'); ?>.</p>
  <p><?= __('help.security_info'); ?></p>
</main>