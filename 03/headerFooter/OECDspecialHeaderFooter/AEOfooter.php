

<style>
    
    #oecdLogo {
		top: -32px;
    }

	#oecdLogo img {
		height: 30px;
	}
</style>

	<footer>
	    <div class='footerContent'>
		<ul>
		    <li>
			<a href="#" class="btn_share" id="shareLink">
			    <object
				type="image/svg+xml"
				height="14"
				width="15"
				data="<?= $staticURL ?>/img/oecd/share_neu.svg">
			     </object>
			    <span><?= $lang['share_button'] ?></span>
			</a>
		    </li>
<!--			<li><a href="#" class="btn_embed"><img id="embed_pos"src="<?= $staticURL ?>/img/oecd/embed_neu.svg"><?= $lang['share'] ?></a></li>
		    <li><a href="#"class="btn_data"  id="aboutLink"><?= $lang['get_data'] ?></a></li>
-->
		    <li>
			<a href="#" class="btn_data" id="aboutLink">
			    <object
				type="image/svg+xml"
				height="14"
				width="0"
				data="">
			     </object>
			    <span><?= $lang['about_data_button'] ?></span>
			</a>
		    </li>

		    <li>
			<a href="<?= $baseURL ?>/<?= $project ?>?cr=<?= $country ?>&lg=<?= $language ?>&page=<?= $page ?>" target="_blank" class="btn_screen">
			    <img src="<?= $staticURL ?>/img/oecd/fullscreen.png" height="15" alt="full screen" title="full screen">
			</a>
		    </li>
		</ul>
		
		<div id="footerLogo">
		    <a href="http://www.afdb.org/en/"><img title="African Development Bank" src="<?= $staticURL ?>/img/aeo/afdb.png" height="52" /></a>
		    <a href="http://www.oecd.org"><img title="OECD Development Center"  src="<?= $staticURL ?>/img/aeo/dev.png" height="52" /></a>
		    <a href="http://www.undp.org"><img title="United Nations Development Programme"  src="<?= $staticURL ?>/img/aeo/undp1.png" height="52" /></a>
		    <a href="http://www.europa.eu"><img title="European Union"  src="<?= $staticURL ?>/img/aeo/eu.png" height="52" /></a>
		</div>

	    </div>             
	</footer>
               
