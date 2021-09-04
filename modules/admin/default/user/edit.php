<div class="wrap">
  <main class="admin">
    <div class="white-box pt5 pr15 pb10 pl15">
      <?= breadcrumb('/admin', lang('Admin'), '/admin/users', lang('Users'), lang('Edit user')); ?>

      <div class="box badges">
        <form action="/admin/user/edit/<?= $data['user']['user_id']; ?>" method="post">
          <?= csrf_field() ?>
          <?php if ($data['user']['user_cover_art'] != 'cover_art.jpeg') { ?>
            <a class="right size-13" href="/u/<?= $data['user']['user_login']; ?>/delete/cover">
              <?= lang('Remove'); ?>
            </a>
            <br>
          <?php } ?>
          <img width="325" class="right" src="<?= user_cover_url($data['user']['user_cover_art']); ?>">
          <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'avatar'); ?>

          <div class="boxline">
            <label class="form-label" for="post_title">
              Id<?= $data['user']['user_id']; ?> |
              <a target="_blank" rel="noopener noreferrer" href="/u/<?= $data['user']['user_login']; ?>">
                <?= $data['user']['user_login']; ?>
              </a>
            </label>
            <?php if ($data['user']['user_trust_level'] != 5) { ?>
              <?php if ($data['user']['isBan']) { ?>
                <span class="type-ban" data-id="<?= $data['user']['user_id']; ?>" data-type="user">
                  <span class="red"><?= lang('Unban'); ?></span>
                </span>
              <?php } else { ?>
                <span class="type-ban" data-id="<?= $data['user']['user_id']; ?>" data-type="user">
                  <span class="green">+ <?= lang('Ban it'); ?></span>
                </span>
              <?php } ?>
            <?php } else { ?>
              ---
            <?php } ?>
          </div>
          <hr>
          <div class="boxline">
            <?php if ($data['user']['user_limiting_mode'] == 1) { ?>
              <span class="red"><?= lang('Dumb mode'); ?>!</span><br>
            <?php } ?>
            <label class="form-label" for="post_content">
              <?= lang('Dumb mode'); ?>?
            </label>
            <input type="radio" name="limiting_mode" <?php if ($data['user']['user_limiting_mode'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
            <input type="radio" name="limiting_mode" <?php if ($data['user']['user_limiting_mode'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
          </div>
          <hr>
          <div class="boxline">
            <?php if ($data['posts_count'] != 0) { ?>
              <label class="required"><?= lang('Posts-m'); ?>:</label>
              <a target="_blank" rel="noopener noreferrer" title="<?= lang('Posts-m'); ?> <?= $data['user']['user_login']; ?>" href="/u/<?= $data['user']['user_login']; ?>/posts">
                <?= $data['posts_count']; ?>
              </a>
              <br>
            <?php } ?>
            <?php if ($data['answers_count'] != 0) { ?>
              <label class="required"><?= lang('Answers'); ?>:</label>
              <a target="_blank" rel="noopener noreferrer" title="<?= lang('Answers'); ?> <?= $data['user']['user_login']; ?>" href="/u/<?= $data['user']['user_login']; ?>/answers">
                <?= $data['answers_count']; ?>
              </a>
              <br>
            <?php } ?>
            <?php if ($data['comments_count'] != 0) { ?>
              <label class="required"><?= lang('Comments'); ?>:</label>
              <a target="_blank" rel="noopener noreferrer" title="<?= lang('Comments'); ?> <?= $data['user']['user_login']; ?>" href="/u/<?= $data['user']['user_login']; ?>/comments">
                <?= $data['comments_count']; ?>
              </a>
              <br>
            <?php } ?>
            <?php if ($data['spaces_user']) { ?>
              <br>
              <label class="required"><?= lang('Created by'); ?>:</label>
              <br>
              <span class="d">
                <?php foreach ($data['spaces_user'] as  $space) { ?>
                  <div class="profile-space">
                    <?= spase_logo_img($space['space_img'], 'small', $space['space_name'], 'ava-24'); ?>
                    <a href="/s/<?= $space['space_slug']; ?>"><?= $space['space_name']; ?></a>
                  </div>
                <?php } ?>
              </span>
            <?php } ?>
          </div>
          <hr>
          <div class="boxline">
            <label class="form-label" for="post_title"><?= lang('Badge'); ?></label>
            <a class="lowercase" href="/admin/badges/user/add/<?= $data['user']['user_id']; ?>">
              <?= lang('Reward the user'); ?>
            </a>
          </div>
          <div class="boxline">
            <label class="form-label" for="post_title"><?= lang('Badges'); ?></label>
            <?php if ($data['user']['badges']) { ?>
              <?php foreach ($data['user']['badges'] as $badge) { ?>
                <?= $badge['badge_icon']; ?>
              <?php } ?>
            <?php } else { ?>
              ---
            <?php } ?>
          </div>
          <div class="boxline">
            <label for="post_title"><?= lang('Views'); ?></label>
            <?= $data['user']['user_hits_count']; ?>
          </div>
          <div class="boxline">
            <label class="form-label" for="post_title"><?= lang('Sign up'); ?></label>
            <?= $data['user']['user_created_at']; ?> |
            <?= $data['user']['user_reg_ip']; ?>
            <?php if ($data['user']['replayIp'] > 1) { ?>
              <sup class="red">(<?= $data['user']['replayIp']; ?>)</sup>
            <?php } ?>
          </div>
          <hr>
          <div class="boxline">
            <label class="form-label" for="post_title">E-mail</label>
            <input class="form-input" type="text" name="email" value="<?= $data['user']['user_email']; ?>" required>
          </div>
          <div class="boxline">
            <label class="form-label" for="post_content"><?= lang('Email activated'); ?>?</label>
            <input type="radio" name="activated" <?php if ($data['user']['user_activated'] == 0) { ?>checked<?php } ?> value="0"> <?= lang('No'); ?>
            <input type="radio" name="activated" <?php if ($data['user']['user_activated'] == 1) { ?>checked<?php } ?> value="1"> <?= lang('Yes'); ?>
          </div>
          <hr>
          <div class="boxline">
            <label class="form-label" for="post_title">TL</label>
            <select name="trust_level">
              <?php for ($i = 0; $i <= 5; $i++) {  ?>
                <option <?php if ($data['user']['user_trust_level'] == $i) { ?>selected<?php } ?> value="<?= $i; ?>">
                  <?= $i; ?>
                </option>
              <?php } ?>
            </select>
          </div>
          <div class="boxline">
            <label for="post_title"><?= lang('Nickname'); ?>:</label>
            /u/***
            <input class="form-input" type="text" name="login" value="<?= $data['user']['user_login']; ?>" required>
          </div>
          <div class="boxline">
            <label class="form-label" for="post_title"><?= lang('Name'); ?></label>
            <input class="form-input" type="text" name="name" value="<?= $data['user']['user_name']; ?>">
          </div>
          <div class="boxline">
            <label class="form-label" for="post_title"><?= lang('About me'); ?></label>
            <textarea class="add" name="about"><?= $data['user']['user_about']; ?></textarea>
          </div>

          <h3><?= lang('Contacts'); ?></h3>
          <div class="boxline">
            <label class="form-label" for="name"><?= lang('URL'); ?></label>
            <input type="text" class="form-input" name="website" id="name" value="<?= $data['user']['user_website']; ?>">
            <div class="box_h">https://site.ru</div>
          </div>

          <div class="boxline">
            <label class="form-label" for="name"><?= lang('City'); ?></label>
            <input type="text" class="form-input" name="location" id="name" value="<?= $data['user']['user_location']; ?>">
            <div class="box_h">Москва</div>
          </div>

          <div class="boxline">
            <label class="form-label" for="name"><?= lang('E-mail'); ?></label>
            <input type="text" class="form-input" name="public_email" id="name" value="<?= $data['user']['user_public_email']; ?>">
            <div class="box_h">**@**.ru</div>
          </div>

          <div class="boxline">
            <label class="form-label" for="name"><?= lang('Skype'); ?></label>
            <input type="text" class="form-input" name="skype" id="name" value="<?= $data['user']['user_skype']; ?>">
            <div class="box_h">skype:<b>NICK</b></div>
          </div>

          <div class="boxline">
            <label class="form-label" for="name"><?= lang('Twitter'); ?></label>
            <input type="text" class="form-input" name="twitter" id="name" value="<?= $data['user']['user_twitter']; ?>">
            <div class="box_h">https://twitter.com/<b>NICK</b></div>
          </div>

          <div class="boxline">
            <label class="form-label" for="name"><?= lang('Telegram'); ?></label>
            <input type="text" class="form-input" name="telegram" id="name" value="<?= $data['user']['user_telegram']; ?>">
            <div class="box_h">tg://resolve?domain=<b>NICK</b></div>
          </div>

          <div class="boxline">
            <label class="form-label" for="name"><?= lang('VK'); ?></label>
            <input type="text" class="form-input" name="vk" id="name" value="<?= $data['user']['user_vk']; ?>">
            <div class="box_h">https://vk.com/<b>NICK / id</b></div>
          </div>

          <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
        </form>
      </div>
    </div>
  </main>
</div>