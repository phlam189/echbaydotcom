<?php
/*
Description: Alias breadcrumb-top1 -> Breadcrumb cho website, sử dụng thiết kế tràn khung, tràn màn hình (không giới hạn chiều rộng) -> giữ nguyên mọi thứ theo bản gốc.
Tags: breadcrumb
*/
?>

<div class="thread-details-tohome">
	<?php
	include EB_THEME_PLUGIN_INDEX . 'themes/top/breadcrumb-top1.php';
	$arr_for_add_theme_css[ EB_THEME_PLUGIN_INDEX . 'css/breadcrumb-top1.css' ] = 1;
	?>
</div>
