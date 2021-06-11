<aside class="overlay">
        <div>
            <a href="#" class="close"></a>
<?php foreach ($this->lang['aboutGeneralText'] as $p): ?>
            <p><?= $p ?></p>
<?php endforeach ?>
            <p>
                <b><?= $this->lang['aboutDataSource'] ?></b><br>
                <a href="<?= htmlspecialchars($this->lang['dataLink']) ?>" target='_blank'><?= $this->lang['about_06'] ?></a>
            </p><p>
                <b><?= $this->lang['aboutRelatedPublication'] ?></b><br>
                <a href="http://www.ecb.europa.eu/pub/pdf/other/statistics_a_brief_overview_2014.en.pdf"><?= $this->lang['about_01'] ?></a><br>
                <a href="http://www.ecb.europa.eu/press/pr/stats/md/html/index.en.html"><?= $this->lang['about_02'] ?></a><br>
                <a href="https://www.youtube.com/playlist?list=PL9436A6D62BD97634"><?= $this->lang['about_03']?></a><br>
                <a href="http://www.ecb.europa.eu/pub/research/statistics-papers/html/index.en.html"><?= $this->lang['about_04'] ?></a><br>
            </p><p><?= $this->lang['about_05'] ?></p>
            <p>
                <b><?= $this->lang['about_08'] ?></b><br>
            </p><p>
                <?= $this->lang['about_09'] ?>
            </p>
                 <p><b><?= $this->lang['about_10'] ?></b></p>
                 <p><?= $this->lang['about_11'] ?></p>

                <p><?= $this->lang['share1'] ?></p>
                <p><?= $this->lang['share2'] ?></p>
                <p><?= $this->lang['share3'] ?></p>
                <p><?= $this->lang['share4'] ?></p>
            <p>
                <b><?= $this->lang['about_07'] ?></b><br>
<?php foreach ($this->bankLinks as  $name => $link): ?>
                <a href="<?= htmlspecialchars($link) ?>"><?= $name ?></a><br>
<?php endforeach ?>
            </p>
        </div>
</aside>
