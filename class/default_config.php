<?php



// mảng các tham số dành cho cấu hình website, cần thêm thuộc tính gì thì cứ thế add vào mảng này là được
$__cf_row_default = array(
	'cf_chu_de_chinh' => '',
	
	'cf_smtp_email' => '',
	'cf_smtp_pass' => '',
	'cf_smtp_host' => '',
	'cf_smtp_port' => 25,
	
	'cf_title' => 'Một website sử dụng plugin thương mại điện tử của EchBay.com',
	'cf_meta_title' => 'Một website sử dụng plugin thương mại điện tử của EchBay.com',
	'cf_keywords' => 'wordpress e-commerce plugin, echbay.com, thiet ke web chuyen nghiep',
	'cf_news_keywords' => 'wordpress e-commerce plugin, echbay.com, thiet ke web chuyen nghiep',
	'cf_description' => 'Một website sử dụng plugin thương mại điện tử của EchBay.com',
	'cf_abstract' => '',
	
	'cf_gse' => '',
	'cf_ga_id' => '',
	
	'cf_sys_email' => 0,
	
	'cf_logo' => EB_URL_TUONG_DOI . 'images-global/echbay-wp-logo.png',
	'cf_favicon' => eb_default_vaficon,
	
	'cf_ten_cty' => 'Thiết kế web giá rẻ',
//	'web_name' => '',
	
	'cf_email' => 'lienhe@echbay.com',
	'cf_email_note' => '',
	
	'cf_dienthoai' => '0984 533 228',
	'cf_call_dienthoai' => '',
	'cf_hotline' => '0984 533 228',
	'cf_call_hotline' => '',
	
	'cf_diachi' => 'Văn Khê, Hà Đông, Hà Nội, Việt Nam',
	
	'cf_bank' => '',
	
	'cf_facebook_page' => '',
	'cf_facebook_id' => '',
	'cf_facebook_admin_id' => '',
	'cf_google_plus' => '',
	'cf_youtube_chanel' => '',
	'cf_twitter_page' => '',
	'cf_yahoo' => '',
	'cf_skype' => '',
	
	'cf_js_allpage' => '',
	'cf_js_allpage_full' => '',
	'cf_js_hoan_tat' => '',
	'cf_js_hoan_tat_full' => '',
	'cf_js_head' => '',
	'cf_js_head_full' => '',
	'cf_js_details' => '',
	'cf_js_details_full' => '',
	
	// màu cơ bản
	'cf_default_css' => '',
	
	'cf_default_body_bg' => '#ffffff',
	'cf_default_color' => '#000000',
	'cf_default_link_color' => '#1264aa',
	
	'cf_default_bg' => '#ff4400',
	'cf_default_2bg' => '#555555',
	'cf_default_bg_color' => '#ffffff',
	'cf_default_amp_bg' => '#0a89c0',
	
	
	'cf_product_size' => '1',
	'cf_product_mobile_size' => '140',
	'cf_product_table_size' => '200',
	'cf_product_details_size' => '1',
	'cf_blog_size' => '2/3',
	'cf_top_banner_size' => '400/1366',
	'cf_other_banner_size' => '2/3',
	
	'cf_num_home_hot' => 5,
	'cf_num_home_new' => 5,
//	'cf_num_home_view' => 0,
	'cf_num_home_list' => 5,
	'cf_num_limit_home_list' => 100,
//	'cf_num_thread_list' => 10,
	'cf_num_details_list' => 10,
	'cf_num_details2_list' => 0,
	'cf_num_details_blog_list' => 10,
	
	// kích thước mặc định của ảnh đại diện
	'cf_product_thumbnail_size' => 'medium',
	'cf_product_thumbnail_table_size' => 'medium',
	'cf_product_thumbnail_mobile_size' => 'ebmobile',
	'cf_ads_thumbnail_size' => 'full',
	
	'cf_region' => '',
	'cf_placename' => 'Ha Noi',
	'cf_position' => '',
	'cf_content_language' => $default_all_site_lang,
	'cf_gg_api_key' => '',
	'cf_timezone' => $default_all_timezone,
	
	'cf_reset_cache' => eb_default_cache_time,
	'cf_dns_prefetch' => '',
	'cf_blog_public' => 1,
	'cf_tester_mode' => 1,
	'cf_theme_dir' => '',
	
	// cài đặt cho bản AMP
	'cf_blog_amp' => 1,
	'cf_blog_details_amp' => 1,
	'cf_product_amp' => 1,
	'cf_product_details_amp' => 0,
	'cf_product_buy_amp' => 0,
	
	
	// bật/ tắt chia sẻ dữ liệu qua JSON
	'cf_on_off_json' => 0,
	'cf_on_off_xmlrpc' => 0,
	
	// xóa URL cha của phân nhóm
	'cf_remove_category_base' => 0,
	
	// plugin SEO của EchBay
	'cf_on_off_echbay_seo' => 1,
	
	// logo thiết kế bởi echbay
	'cf_on_off_echbay_logo' => 1,
	
	// on/ off AMP
	'cf_on_off_amp_logo' => 0,
	'cf_on_off_amp_category' => 1,
	'cf_on_off_amp_product' => 0,
	'cf_on_off_amp_blogs' => 1,
	'cf_on_off_amp_blog' => 1,
	
	// tự động cập nhật mã nguồn wordpress
	'cf_on_off_auto_update_wp' => 0,
	
	// tự tìm và lấy link thumbnail bằng javascript
	'cf_disable_auto_get_thumb' => 0,
	
	// nhúng URL vào các thẻ H1 ở trang chi tiết sản phẩm, bài viết, danh mục...
	'cf_set_link_for_h1' => 1,
	
	// ẩn các menu quan trọng trong admin
	'cf_hide_supper_admin_menu' => 1,
	
	
	/*
	* giao diện HTML mặc định
	*/
	// class bao viền (w99, w90)
	'cf_blog_class_style' => '',
	
	'cf_using_top_default' => 1,
	'cf_top_class_style' => '',
	'cf_top1_include_file' => '',
	'cf_top2_include_file' => '',
	'cf_top3_include_file' => '',
	'cf_top4_include_file' => '',
	'cf_top5_include_file' => '',
	'cf_top6_include_file' => '',
	
	'cf_using_footer_default' => 1,
	'cf_footer_class_style' => '',
	'cf_footer1_include_file' => '',
	'cf_footer2_include_file' => '',
	'cf_footer3_include_file' => '',
	'cf_footer4_include_file' => '',
	'cf_footer5_include_file' => '',
	'cf_footer6_include_file' => '',
	
	// khung sản phẩm
	'cf_threadnode_include_file' => '',
	// Thay đổi thẻ cho phần tiêu đề, tùy các SEOer muốn là thẻ gì thì chọn thẻ đấy, mặc định DIV
	'cf_threadnode_title_tag' => 'div',
	
	// HTML trang chi tiết sản phẩm
	'cf_threaddetails_include_file' => '',
	
	
	// danh sách tin -> tổng quan
	'cf_home_class_style' => '',
	'cf_home_column_style' => '',
	
	
	// danh sách tin -> tổng quan
	'cf_cats_class_style' => '',
	'cf_cats_column_style' => '',
	// danh sách tin -> html cho phần node
	'cf_cats_node_html' => '',
	// danh sách tin -> số tin trên mỗi dòng
	'cf_cats_num_line' => '',
	
	// chi tiết -> tổng quan
	'cf_post_class_style' => '',
	'cf_post_column_style' => '',
	// chi tiết -> html cho phần node
	'cf_post_node_html' => '',
	
	
	// danh sách tin -> tổng quan
	'cf_blogs_class_style' => '',
	'cf_blogs_column_style' => '',
	// danh sách tin -> html cho phần node
	'cf_blogs_node_html' => '',
	// danh sách tin -> số tin trên mỗi dòng
	'cf_blogs_num_line' => '',
	
	// chi tiết -> tổng quan
	'cf_blogd_class_style' => '',
	'cf_blog_column_style' => '',
	// chi tiết -> html cho phần node
	'cf_blog_node_html' => '',
	'cf_blog_num_line' => '',
	
	
	// class bao viền (w99, w90)
	'cf_page_class_style' => '',
	// chi tiết -> tổng quan
	'cf_page_column_style' => '',
	
	
	
	// thư mục gốc của tài khoản FTP
	'cf_ftp_root_dir' => '',
	
	
	'cf_web_version' => 1.0,
	'cf_ngay' => date_time,
);

//
$__cf_row = $__cf_row_default;



