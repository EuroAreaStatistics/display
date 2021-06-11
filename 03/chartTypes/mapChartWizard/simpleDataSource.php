
  <div class='simpleDataSource'>
      <?= $lang['aboutDataSource'] ?>:
  <?php if ($config['project']['options']['dataSourceURL'][$language]) : ?>
      <a href='<?= $config['project']['options']['dataSourceURL'][$language] ?>' target='_blank'><?= $config['project']['options']['dataSource'][$language] ?></a>
  <?php else : ?>
      <?= $config['project']['options']['dataSource'][$language] ?>
  <?php endif ?>
  </div>
