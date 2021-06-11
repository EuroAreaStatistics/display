	<footer>
	    <div class='footerContent'>
			<ul>
				<li>
					<a href="#" class="btn_share" id="shareLink">
						<object
						type="image/svg+xml"
						height="14"
						width="15"
						data="<?= htmlspecialchars($this->staticURL."/img/oecd/share_neu.svg") ?>">
						 </object>
						<div class='btn_text'><?= $this->lang['share_button'] ?></div>
					</a>
				</li>
				<li>
					<a href="#" class="btn_data" id="aboutLink">
						<div class='btn_text'><?= $this->lang['about_data_button'] ?></div>
					</a>
				</li>

				<li>
					<a href="#" class="btn_screen">
						<img src="<?= htmlspecialchars($this->staticURL."/img/oecd/fullscreen.png") ?>" height="15" alt="full screen" title="full screen">
					</a>
				</li>
			</ul>
	    </div>
	</footer>
