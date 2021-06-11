        <style>

	    .shareList {
		width: 100%;
		margin-top: 20px;
	    }


	    .shareItem {
		float: left;
		width: 38%;
		margin: 1%;
		padding: 5%;
		height: 80px;
		background: #9E9797;
		color: #000000;
		text-align: center;
	    }

	    .shareItem:hover, .shareItem a:hover {
		color: white;
	    }


	    .shareItem a:link {
		display:block;
		width:100%;
/*		height:100%;
*/		text-decoration: none;
	    }


	    .shareItem a {
		color: black;
	    }


	    @media only screen and (min-width: 550px) {

		.shareItem {
		    width: 13%;
		}

	    }

        </style>

	<div id="shareContainer" class="resourceContainer">

	    <div id="shareContent" class="resourceContent">

                <div id="closeShare" class="sharebutton closeButtonAbout">x</div>

                <div class="aboutItem generalText"><?= $this->lang['aboutGeneralText'] ?> <?= $this->lang['aboutGeneralTextTerms']?></div>

                <div class="shareList">
                    <div class="shareItem" id="shareEmail">
			<a href="mailto:?subject=Compare your country&amp;body=Check out this site http://www.compareyourcountry.org/<?= $this->project ?>." title="Share by Email">
			    <img id="email" src="<?= htmlspecialchars($this->staticURL."/img/share/mail.png") ?>">
			    <?= $this->lang['aboutEmail'] ?>
			</a>
		    </div>

		    <div class="shareItem" id="shareFacebook">
			<a href="#"
			    onclick="
			      window.open(
				'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),
				'facebook',
				'width=626,height=436');
			      return false;">
			      <img id="facebook" src="<?= htmlspecialchars($this->staticURL."/img/share/facebook.png") ?>">
			    <?= $this->lang['aboutFacebook'] ?>
			  </a>
                    </div>

                    <div class="shareItem" id="shareTwitter">
			<a href="#"
			    onClick="Popup=window.open(
				'https://twitter.com/intent/tweet?text=OECD data viz&url=http%3A%2F%2Fwww.compareyourcountry.org/<?= $this->project ?>',
				'Popup',
				'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=626,height=436,left=100,top=50'); return false;" class="twitter-follow-button">
				<img id="twitter" src="<?= htmlspecialchars($this->staticURL."/img/share/twitter.png") ?>">
			    <?= $this->lang['aboutTwitter'] ?>
			</a>
		    </div>
                    <div class="shareItem" id="shareEmbed">
			<a href="#"
			    onClick="Popup=window.open(
				baseURL+'/embed?project='+project+'&lg='+window.lang+'&cr='+urlcountry+'&page='+page+'',
				'Popup',
				'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=750,left=100,top=50'); return false;" class="twitter-follow-button">
				<img id="embed" src="<?= htmlspecialchars($this->staticURL."/img/share/embed.png") ?>">
			    <?= $this->lang['aboutEmbed'] ?>
			</a>
		    </div>
                </div>

	    </div>

	</div>
