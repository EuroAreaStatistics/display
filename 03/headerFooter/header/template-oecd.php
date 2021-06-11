

            <header>
                <div id="wrapper_mainNav">
                    <div id="mainNav" class='headerContent'>
                        <div id="logo">
                            <a href="<?= htmlspecialchars($this->home) ?>">
                              <img src="<?= htmlspecialchars($this->staticURL . "/img/oecd/cyc_guillemets.png") ?>"  alt="compare your country">
                              <h1><?= $this->lang['main_title_compare'] ?></h1>
                            </a>
                        </div>
<?php if ($this->languages) : ?>
                        <div class="select-menu">
                          <select class="style-select" id='langSelect'>
	<?php foreach ($this->languages as $code => $name) : ?>
                            <option value="<?= htmlspecialchars($code) ?>"><?= $name ?></option>
	<?php endforeach  ?>
                          </select>
                        </div>
<?php endif ?>
                        <div id="home">
                            <a href="<?= htmlspecialchars($this->home) ?>"><?= $this->lang['Home'] ?></a>
                        </div>
                    </div>
                </div>
                <div id="wrapper_topic">
                  <div id="topic" class='headerContent'>
                    <h1><?= strip_tags($this->pageTitle) ?></h1>
<?php if ($this->templates) : ?>
                    <div class="template-menu">
                      <select class="style-select" id='templateSelect'>
                        <option value='notSet' ><?= $this->lang['changeView'] ?></option>
    <?php foreach ($this->templates as $code => $name) : ?>
                        <option value="<?= htmlspecialchars($code) ?>"><?= $name ?></option>
    <?php endforeach  ?>
                      </select>
                    </div>
<?php endif ?>
                  </div>
                </div>
                <div id="wrapper_topicNav">
                    <nav id="topicNav" class="headerContent">
                        <ul>
                            <!--
<?php  foreach ($this->tabs as $i => $name): ?>
                        --><li><a class='navTabs' id="<?= htmlspecialchars("tab$i") ?>" value="<?= htmlspecialchars("$i") ?>" href='#' ><?= $name ?></a></li><!--
<?php  endforeach ?>
                           -->
                        </ul>
                    </nav>
                </div>
            </header>


