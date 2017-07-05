<div class="footer-pading">
	<div class="cf <?php echo $__cf_row['cf_footer_class_style']; ?>">
		<div class="lf f25 fullsize-if-mobile">
			<div class="right-menu-space">
				<?php
				echo _eb_echbay_menu(
					'footer-menu-01',
					array(
						'menu_class' => 'bottom-node',
					),
					1,
					'<div class="footer-title upper">'
				);
				?>
			</div>
			<div>&nbsp;</div>
		</div>
		<div class="lf f25 fullsize-if-mobile">
			<div class="right-menu-space">
				<?php
				echo _eb_echbay_menu(
					'footer-menu-02',
					array(
						'menu_class' => 'bottom-node',
					),
					1,
					'<div class="footer-title upper">'
				);
				?>
			</div>
			<div>&nbsp;</div>
		</div>
		<div class="lf f25 fullsize-if-mobile">
			<div class="right-menu-space">
				<?php
				echo _eb_echbay_menu(
					'footer-menu-03',
					array(
						'menu_class' => 'bottom-node',
					),
					1,
					'<div class="footer-title upper">'
				);
				?>
			</div>
			<div>&nbsp;</div>
		</div>
		<div class="lf f25 fullsize-if-mobile">
			<div class="footer-contact">
				<div class="footer-contact-title"><?php echo $__cf_row['cf_ten_cty']; ?></div>
				<ul class="footer-contact-content">
					<li><strong>Địa chỉ:</strong> <i class="fa fa-map-marker"></i> <?php echo nl2br( $__cf_row['cf_diachi'] ); ?></li>
					<li><strong>Điện thoại:</strong> <i class="fa fa-phone"></i> <?php echo $__cf_row['cf_call_hotline']; ?> - <span class="phone-numbers-inline"><?php echo $__cf_row['cf_call_dienthoai']; ?></span></li>
					<li><strong>Email:</strong> <i class="fa fa-envelope-o"></i> <a href="mailto:<?php echo $__cf_row['cf_email']; ?>" rel="nofollow" target="_blank"><?php echo $__cf_row['cf_email']; ?></a></li>
				</ul>
			</div>
			<br>
			<div class="footer-social-title">Kết nối với chúng tôi</div>
			<ul class="footer-social text-center cf">
				<li class="footer-social-fb"><a href="javascript:;" class="ahref-to-facebook" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>
				<li class="footer-social-tw"><a href="javascript:;" class="each-to-twitter-page" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>
				<li class="footer-social-yt"><a href="javascript:;" class="each-to-youtube-chanel" target="_blank" rel="nofollow"><i class="fa fa-youtube"></i></a></li>
				<li class="footer-social-gg"><a href="javascript:;" class="ahref-to-gooplus" target="_blank" rel="nofollow"><i class="fa fa-google-plus"></i></a></li>
			</ul>
		</div>
	</div>
</div>