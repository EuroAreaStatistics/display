<style>

.resourceContainer {
  position: fixed;
  bottom: 40px;
  top: 90px;
  height: auto;
  width: 100%;
  background-color: rgba(255,255,255,0.8);
  z-index: 15;
  color: black;
}

.resourceContent {
  background-color: rgba(240,240,240,0.99);
  padding-right: 5%;
  padding-left: 3%;
  padding-top: 30px;
  height: 95%;
  overflow: auto;
}

.closeButtonAbout {
  position: absolute;
  right: 5%;
  top: 20px;
}

#aboutContainer,
#shareContainer {
  display: none;
}

.aboutItem, .aboutItem h3 {
  font-size: 12px;
}

.generalText {
  margin-top: 25px;
}

.aboutItem a {
  color: #0C2383;
  text-decoration: none;
}


.PubCover {
  background-color: white;
  opacity: 1.0;
  border: 5px;
  width: 95px;
}

.PubInfo {
  padding-left: 20px;
}

.PubInfo h4{
  margin-top: 0px;
}

.PubInfo li, #dataSource li{
  margin-top: 5px;
}


.aboutItem a:hover {
  text-decoration: underline;
}

@media only screen and (min-width: 700px) {

  .generalText {
    margin-top: 18px;
  }

  .aboutItem, .aboutItem h3 {
    font-size: 14px;
  }

  .resourceContent {
    margin-top: 50px;
    margin-right: 10%;
    margin-left: 10%;
  }

  .closeButtonAbout {
    right: 12%;
    top: 70px;
  }

}

@media only screen and (min-width: 900px) {

  .generalText {
    margin-top: 18px;
  }

  .aboutItem, .aboutItem h3 {
    font-size: 16px;
  }


  .resourceContent {
    margin-top: 50px;
    margin-right: 20%;
    margin-left: 20%;
  }

  .closeButtonAbout {
    right: 22%;
    top: 70px;
  }

}

</style>

  <div id="aboutContainer" class="resourceContainer">
      <div id="aboutContent" class="resourceContent">
        <div id="closeAbout" class="sharebutton closeButtonAbout">x</div>
        <div class="aboutItem generalText"><?= $this->lang['aboutGeneralText'] ?> <?= $this->lang['aboutGeneralTextTerms'] ?></div>
        <div class="aboutItem" id="dataSource">
            <h3><?= $this->lang['aboutDataSource'] ?></h3>
            <ul>
<?php foreach ($this->dataLinks as $link => $name): ?>
              <li><a href="<?= htmlspecialchars($link) ?>" target='_blank'><?= $name ?></a></li>
<?php endforeach ?>
            </ul>
        </div>
<?php if (isset($this->publicationName)) : ?>
        <div class="aboutItem" id="relatedPublication">
          <h3><?= $this->lang['aboutRelatedPublication'] ?></h3>
          <table>
            <tr>
  <?php if (isset($this->coverLink)) : ?>
              <td class="PubCover" >
                <img src="<?= htmlspecialchars($this->coverLink) ?>">
              </td>
  <?php endif ?>
              <td class="PubInfo">
                <h4 class="PubTitle"><?= $this->publicationName ?></h4>
                <ul>
                  <li id="OECDwebsite"><a href="<?= htmlspecialchars($this->relatedWebsite) ?>"><?= $this->lang['aboutRelatedWebsite'] ?></a></li>
                </ul>
              </td>
            </tr>
          </table>
        </div>
<?php endif ?>
      </div>
  </div>
