<fieldset>
  <label for="facet_title"><?= __('app.title'); ?> <strong class="red">*</strong></label>
  <input id="facet_title" minlength="3" name="facet_title" required type="text" minlength="3" maxlength="64" value="">
  <div class="text-sm gray-600">3 - 64 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="facet_short_description"><?= __('app.short_description'); ?> <strong class="red">*</strong></label>
  <input id="facet_short_description" minlength="9" name="facet_short_description" required type="text" value="">
  <div class="text-sm gray-600">9 - 120 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="facet_seo_title"><?= __('app.title'); ?> (SEO) <strong class="red">*</strong></label>
  <input id="facet_seo_title" minlength="3" name="facet_seo_title" required type="text" value="">
  <div class="text-sm gray-600">> 3 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="facet_slug"><?= __('app.slug'); ?> <strong class="red">*</strong></label>
  <input id="facet_slug" name="facet_slug" minlength="3" maxlength="32" required type="text" value="">
  <div class="text-sm gray-600">3 - 32 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="facet_description"><?= __('app.meta_description'); ?> <strong class="red">*</strong></label>
  <textarea id="facet_description" name="facet_description" required></textarea>
  <div class="text-sm gray-600">> 3 <?= __('app.characters'); ?></div>
</fieldset>

<?= Html::sumbit(__('app.add')); ?>