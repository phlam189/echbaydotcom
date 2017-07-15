<?php



//
global $wpdb;
global $__cf_row_default;



//
//echo get_home_url() . '<br>' . "\n";
//echo get_site_url() . '<br>' . "\n";



// lấy siteurl và homeurl trong CSDL để fix lại nếu người dùng có nhu cầu
$sql = _eb_q("SELECT *
	FROM
		`" . $wpdb->options . "`
	WHERE
		option_name = 'siteurl'
		OR option_name = 'home'
	ORDER BY
		option_id DESC");
//print_r( $sql );

//
$current_homeurl = '';
$current_siteurl = '';
foreach ( $sql as $v ) {
	if ( $v->option_name == 'home' ) {
		$current_homeurl = $v->option_value;
	}
	else if ( $v->option_name == 'siteurl' ) {
		$current_siteurl = $v->option_value;
	}
}




//print_r( $__cf_row );
_eb_get_config( true );
//print_r( $__cf_row );

//
if ( (int) $__cf_row['cf_reset_cache'] < 0 ) {
	$__cf_row['cf_reset_cache'] = 60;
}
//print_r( $__cf_row );



//
$arr_cf_reset_cache = array(
	0 => 'Không sử dụng',
	1 => 'Mỗi phút',
	2 => 'Mỗi 2 phút',
	5 => 'Mỗi 5 phút',
	10 => 'Mỗi 10 phút ( Khuyên dùng )',
	15 => 'Mỗi 15 phút',
	30 => 'Mỗi 30 phút'
);
//print_r($arr_cf_reset_cache);
$str_cf_reset_cache = '';
foreach ( $arr_cf_reset_cache as $k => $v ) {
	// Đơn vị tính là phút -> nhân với 60 giây
	$k = $k * 60;
	
	$label_id = 'config_label_id' .$k;
	
	$sl = '';
	if ( $__cf_row['cf_reset_cache'] == $k ) {
		$sl = ' checked="checked"';
	}
	
	$str_cf_reset_cache .= '
<div>
	<input type="radio" name="cf_reset_cache" id="' .$label_id. '" value="' .$k. '"' . $sl . '>
	<label for="' .$label_id. '">' .$v. '</label>
</div>';
}










/*
* Chung
*/
function __eb_create_select_checked_config ( $arr, $val, $key, $file_name = '' ) {
	$str = '';
	
	foreach ( $arr as $k => $v ) {
		
		$label_id = $key . '_id' . $k;
		
		// đánh dấu kiểm
		$sl = '';
		if ( $val == $k ) {
			$sl = ' checked="checked"';
		}
		
		// ví dụ cho code dễ hình dung
		$phai = $k;
		if ( $file_name != '' ) {
			$phai = $file_name;
			if ( $k != '' ) {
				$phai .= '_' . $k;
			}
		}
		
		// tạo HTML
		$str .= '
<div class="ebdesign-table-for ebdesign-table-for-' . $phai . '">
	<input type="radio" name="' . $key . '" id="' .$label_id. '" value="' .$k. '"' . $sl . '>
	<label for="' .$label_id. '">' .$v. ' (' . $phai . ')</label>
</div>';
	}
	
	return trim( $str );
}

$arr_cf_blog_class_style = array(
	'' => 'Không giới hạn chiều rộng',
	'w99' => 'Dạng thu gọn, rộng 999px',
	'w90' => 'Dạng tràn khung, rộng 90%, tối đa 1366px',
);

$str_cf_blog_class_style = __eb_create_select_checked_config(
	$arr_cf_blog_class_style,
	$__cf_row['cf_blog_class_style'],
	'cf_blog_class_style'
);




/*
* Danh sách tin
*/
$arr_cf_blog_column_style = array(
	'' => 'Một cột, không có menu',
	'noidung_menu' => 'Hai cột, menu nằm bên phải',
	'menu_noidung' => 'Hai cột, menu nằm bên trái',
//	'menu_noidung_menu' => 'Ba cột, menu nằm hai bên',
);

$str_cf_blogs_column_style = __eb_create_select_checked_config(
	$arr_cf_blog_column_style,
	$__cf_row['cf_blogs_column_style'],
	'cf_blogs_column_style',
	'blogs'
);



//
$arr_cf_blog_node_html = array(
	'' => 'Hai cột, ảnh bên trái - chữ bên phải',
	'chu_anh' => 'Hai cột, chữ bên trái - ảnh bên phải',
	'anhtren_chuduoi' => 'Một cột, ảnh bên trên - chữ bên dưới',
	'chutren_anhduoi' => 'Một cột, chữ bên trên - ảnh bên dưới',
	'chi_chu' => 'Một cột, chỉ chữ',
);

$str_cf_blogs_node_html = __eb_create_select_checked_config(
	$arr_cf_blog_node_html,
	$__cf_row['cf_blogs_node_html'],
	'cf_blogs_node_html',
	'blog_details'
);



//
$arr_cf_blogs_num_line = array(
	'' => 'Theo thiết kế mặc định của tác giả',
	'echbay-blog100' => 'Một',
	'echbay-blog50' => 'Hai',
	'echbay-blog33' => 'Ba',
	'echbay-blog25' => 'Bốn',
	'echbay-blog20' => 'Năm',
);

$str_cf_blogs_num_line = __eb_create_select_checked_config(
	$arr_cf_blogs_num_line,
	$__cf_row['cf_blogs_num_line'],
	'cf_blogs_num_line'
);



/*
* Chi tiết tin
*/
$str_cf_blog_column_style = __eb_create_select_checked_config(
	$arr_cf_blog_column_style,
	$__cf_row['cf_blog_column_style'],
	'cf_blog_column_style',
	'blog_details'
);



//
$str_cf_blog_node_html = __eb_create_select_checked_config(
	$arr_cf_blog_node_html,
	$__cf_row['cf_blog_node_html'],
	'cf_blog_node_html',
	'blog_node'
);


/*
* Page
*/
$str_cf_page_class_style = __eb_create_select_checked_config(
	$arr_cf_blog_class_style,
	$__cf_row['cf_page_class_style'],
	'cf_page_class_style'
);


//
$str_cf_page_column_style = __eb_create_select_checked_config(
	$arr_cf_blog_column_style,
	$__cf_row['cf_page_column_style'],
	'cf_page_column_style',
	'page'
);










//
$arr_cf_product_thumbnail_size = array(
	'medium' => 'Thu gọn (medium)',
	'medium_large' => 'Trung bình (medium_large)',
	'large' => 'Lớn (large)',
	'full' => 'Đầy đủ (bản gốc)',
);
//print_r($arr_cf_product_thumbnail_size);
$str_cf_product_thumbnail_size = '';
foreach ( $arr_cf_product_thumbnail_size as $k => $v ) {
	$sl = '';
	if ( $__cf_row['cf_product_thumbnail_size'] == $k ) {
		$sl = ' selected="selected"';
	}
	
	$str_cf_product_thumbnail_size .= '<option value="' . $k . '"' . $sl . '>' . $v . '</option>';
}


//
$arr_cf_product_thumbnail_table_size = $arr_cf_product_thumbnail_size;
//print_r($arr_cf_product_thumbnail_table_size);
$str_cf_product_thumbnail_table_size = '';
foreach ( $arr_cf_product_thumbnail_table_size as $k => $v ) {
	$sl = '';
	if ( $__cf_row['cf_product_thumbnail_table_size'] == $k ) {
		$sl = ' selected="selected"';
	}
	
	$str_cf_product_thumbnail_table_size .= '<option value="' . $k . '"' . $sl . '>' . $v . '</option>';
}


//
$arr_cf_product_thumbnail_mobile_size = array(
	'thumbnail' => 'Bản cắt gọn (thumbnail)',
	'ebmobile' => 'Tối ưu cho mobile (khuyên dùng)',
//	'medium' => 'Thu gọn (medium)',
//	'full' => 'Đầy đủ (bản gốc)',
) + $arr_cf_product_thumbnail_size;
//print_r($arr_cf_product_thumbnail_mobile_size);
$str_cf_product_thumbnail_mobile_size = '';
foreach ( $arr_cf_product_thumbnail_mobile_size as $k => $v ) {
	$sl = '';
	if ( $__cf_row['cf_product_thumbnail_mobile_size'] == $k ) {
		$sl = ' selected="selected"';
	}
	
	$str_cf_product_thumbnail_mobile_size .= '<option value="' . $k . '"' . $sl . '>' . $v . '</option>';
}




//
foreach ( $__cf_row as $k => $v ) {
	if ( trim( $v != '' ) && ! is_numeric($v) ) {
		$__cf_row[$k] = htmlentities( $__cf_row[$k], ENT_QUOTES, "UTF-8" );
	}
}
//print_r($__cf_row);



// list timezone
/**
 * Timezones list with GMT offset
 *
 * @return array
 * @link http://stackoverflow.com/a/9328760
 */
$timezone_wp_full = '';
/*
function get_timezone_list () {
	$zones_array = array();
	$timestamp = time();
	
	// lấy danh sách múi giờ được hỗ trợ bởi PHP
	foreach( timezone_identifiers_list() as $key => $zone ) {
		date_default_timezone_set($zone);
		
		// zone
		$zones_array[$key]['z'] = $zone;
		
		// diff_from_GMT
		$zones_array[$key]['d'] = date('P', $timestamp);
	}
//	print_r( $zones_array );
	
	return $zones_array;
}
foreach( get_timezone_list() as $t ) {
	$timezone_wp_full .= '<option value="' .$t['z']. '">' .$t['d']. ' - ' .$t['z']. '</option>';
}
$timezone_wp_full = '<select name="cf_timezone">' .$timezone_wp_full. '</select>';

// Set lại timezone do function ở trên làm sai giờ
date_default_timezone_set( $__cf_row['cf_timezone'] );
//echo date( 'r', $date_time );
*/

//$timezone_wp_full = file_get_contents( $dir_index . 'timezone.html', 1 );
//$timezone_wp_full = file_get_contents( $dir_index . 'timezone_gmt.html', 1 );




// Danh sách các ngôn ngữ được hỗ trợ
//echo EB_THEME_PLUGIN_INDEX . "\n";
//echo EB_URL_OF_PLUGIN . "\n";
//echo __FILE__ . "\n";
/*
$list_file_for_lang = '';

//
$arr_file_for_lang = glob ( EB_THEME_PLUGIN_INDEX . 'lang/*.{php}', GLOB_BRACE );
//print_r($arr_file_for_lang);
$arr_file_for_lang = array_filter ( $arr_file_for_lang, 'is_file' );
//print_r($arr_file_for_lang);
foreach ( $arr_file_for_lang as $v ) {
	$v = basename( $v );
	$v = explode( '.', $v );
	$v = $v[0];
	
	//
	$sl = '';
	if ( $v == $__cf_row['cf_content_language'] ) {
		$sl = ' selected="selected"';
	}
	
	//
	$list_file_for_lang .= '<option value="' . $v . '" ' . $sl . '>' . $v . '</option>';
}
*/






//
$main_content = EBE_str_template( 'html/' . $include_page . '.html', array(
	'tmp.js' => 'var cf_timezone="' . $__cf_row['cf_timezone'] . '",current_module_config="' . $include_page . '";',
	
	'tmp.config_module_name' => $include_page == 'config_theme' ? 'Cài đặt giao diện' : 'Cấu hình Website',
	'tmp.include_page' => $include_page,
//	'tmp.js_version' => date( 'Ymd-His', filemtime( ECHBAY_PRI_CODE . 'js/config.js' ) ),
	'tmp.js_version' => time(),
	
//	'tmp.ex_dns_prefetch' => $_SERVER['HTTP_HOST'],
	'tmp.timezone_wp_full' => $timezone_wp_full,
	'tmp.cf_reset_cache' => $str_cf_reset_cache,
	
	'tmp.current_homeurl' => $current_homeurl,
	'tmp.current_siteurl' => $current_siteurl,
	
	
	// blog
	'tmp.cf_blog_class_style' => $str_cf_blog_class_style,
	
	'tmp.cf_blogs_column_style' => $str_cf_blogs_column_style,
	'tmp.cf_blogs_node_html' => $str_cf_blogs_node_html,
	'tmp.cf_blogs_num_line' => $str_cf_blogs_num_line,
	
	'tmp.cf_blog_column_style' => $str_cf_blog_column_style,
	'tmp.cf_blog_node_html' => $str_cf_blog_node_html,
	
	// page
	'tmp.cf_page_class_style' => $str_cf_page_class_style,
	'tmp.cf_page_column_style' => $str_cf_page_column_style,
	
	
	'tmp.cf_product_thumbnail_size' => '<select name="cf_product_thumbnail_size">' . $str_cf_product_thumbnail_size . '</select>',
	'tmp.cf_product_thumbnail_table_size' => '<select name="cf_product_thumbnail_table_size">' . $str_cf_product_thumbnail_table_size . '</select>',
	'tmp.cf_product_thumbnail_mobile_size' => '<select name="cf_product_thumbnail_mobile_size">' . $str_cf_product_thumbnail_mobile_size . '</select>',
	
//	'tmp.list_file_for_lang' => '<select name="cf_content_language">' . $list_file_for_lang . '</select>',
	'tmp.list_file_for_lang' => '',
), ECHBAY_PRI_CODE );



// cấu hình cho trang thiết kế html
$arr_for_set_template = array();



/*
* Tranh chủ
*/
$arr_for_set_template['cf_home_class_style'] = __eb_create_select_checked_config(
	$arr_cf_blog_class_style,
	$__cf_row['cf_home_class_style'],
	'cf_home_class_style'
);

$arr_for_set_template['cf_home_column_style'] = __eb_create_select_checked_config(
	$arr_cf_blog_column_style,
	$__cf_row['cf_home_column_style'],
	'cf_home_column_style',
	'home'
);



/*
* Tranh danh sách sản phẩm
*/
$arr_for_set_template['cf_cats_class_style'] = __eb_create_select_checked_config(
	$arr_cf_blog_class_style,
	$__cf_row['cf_cats_class_style'],
	'cf_cats_class_style'
);

$arr_for_set_template['cf_cats_column_style'] = __eb_create_select_checked_config(
	$arr_cf_blog_column_style,
	$__cf_row['cf_cats_column_style'],
	'cf_cats_column_style',
	'thread_list'
);

//
$arr_cf_cats_node_html = array(
	'' => 'Một cột, ảnh bên trên - chữ bên dưới',
	'chutren_anhduoi' => 'Một cột, chữ bên trên - ảnh bên dưới',
	'anh_chu' => 'Hai cột, ảnh bên trái - chữ bên phải',
	'chu_anh' => 'Hai cột, chữ bên trái - ảnh bên phải',
);

$arr_for_set_template['cf_cats_node_html'] = __eb_create_select_checked_config(
	$arr_cf_cats_node_html,
	$__cf_row['cf_cats_node_html'],
	'cf_cats_node_html',
	'thread_node'
);

//
$arr_cf_cats_num_line = array(
	'' => 'Theo thiết kế mặc định của tác giả',
	'thread-list100' => 'Một',
	'thread-list50' => 'Hai',
	'thread-list33' => 'Ba',
	'thread-list25' => 'Bốn',
	'thread-list20' => 'Năm',
);

$arr_for_set_template['cf_cats_num_line'] = __eb_create_select_checked_config(
	$arr_cf_cats_num_line,
	$__cf_row['cf_cats_num_line'],
	'cf_cats_num_line'
);



/*
* Trang chi tiết sản phẩm
*/
$arr_for_set_template['cf_post_class_style'] = __eb_create_select_checked_config(
	$arr_cf_blog_class_style,
	$__cf_row['cf_post_class_style'],
	'cf_post_class_style'
);

$arr_for_set_template['cf_post_column_style'] = __eb_create_select_checked_config(
	$arr_cf_blog_column_style,
	$__cf_row['cf_post_column_style'],
	'cf_post_column_style',
	'thread_details'
);

//
$arr_for_set_template['cf_post_node_html'] = __eb_create_select_checked_config(
	$arr_cf_blog_node_html,
	$__cf_row['cf_post_node_html'],
	'cf_post_node_html',
	'thread_details_node'
);



/*
* Trang danh sách blogs
*/
$arr_for_set_template['cf_blogs_class_style'] = __eb_create_select_checked_config(
	$arr_cf_blog_class_style,
	$__cf_row['cf_blogs_class_style'],
	'cf_blogs_class_style'
);



/*
* Trang chi tiết blog
*/
$arr_for_set_template['cf_blogd_class_style'] = __eb_create_select_checked_config(
	$arr_cf_blog_class_style,
	$__cf_row['cf_blogd_class_style'],
	'cf_blogd_class_style'
);

$arr_for_set_template['cf_blog_num_line'] = __eb_create_select_checked_config(
	$arr_cf_blogs_num_line,
	$__cf_row['cf_blog_num_line'],
	'cf_blog_num_line'
);



// top & footer
$arr_for_set_template['cf_top_class_style'] = __eb_create_select_checked_config(
	$arr_cf_blog_class_style,
	$__cf_row['cf_top_class_style'],
	'cf_top_class_style'
);

$arr_for_set_template['cf_footer_class_style'] = __eb_create_select_checked_config(
	$arr_cf_blog_class_style,
	$__cf_row['cf_footer_class_style'],
	'cf_footer_class_style'
);







// load danh sách file TOP, FOOTER
$str_list_all_include_file = array();

function EBE_config_theme_load_file_tag ( $str, $search ) {
	if ( $str == '' || $search == '' ) {
		return '';
	}
	
	$str = explode( $search . ':', $str );
	
	if ( count( $str ) > 1 ) {
		$str = explode( "\n", $str[1] );
		
		return trim( $str[0] );
	}
	
	return '';
}

/*
* Quy tắc tạo file template:

1: Nếu muốn file chỉ chạy trên domain cụ thể
Domain: xwatch.vn

*/
function EBE_config_load_top_footer_include ( $type = 'top', $file_type = '.php' ) {
	global $__cf_row_default;
	global $__cf_row;
	global $str_list_all_include_file;
//	global $arr_for_set_template;
	
	// kiểm tra theo domain của template
	$current_domain = str_replace( 'www.', '', $_SERVER['HTTP_HOST'] );
	
	//
	$str_top_design_preview = '';
	
	$arr_file_name = glob ( EB_THEME_PLUGIN_INDEX . 'themes/' . $type . '/*.{' . substr( $file_type, 1 ) . '}', GLOB_BRACE );
//	print_r( $arr_file_name );
	
	$arr_top_include_file = array();
	for ( $i = 1; $i < 10; $i++ ) {
		$j = $type . $i;
		$j_name = 'cf_' . $j . '_include_file';
		$j2_name = 'cf_' . $type . '_include_file';
		
		//
		if ( isset( $__cf_row_default[ $j_name ] ) ) {
			/*
			$arr_top_include_file[ $j ] = array(
				'' => 'Chọn file thiết kế cho phần ' . $j,
				$type . '_widget.php' => 'Đặt làm ' . $type . ' widget'
			);
			*/
			
			$arr_top_include_file[ $j ] = array();
			
			$str_list_all_include_file[] = '<input type="text" name="' . $j_name . '" id="' . $j_name . '" value="' . $__cf_row[ $j_name ] . '" data-type="' . $j . '" class="each-to-get-current-value-file" />';
		} else {
			if ( isset( $__cf_row_default[ $j2_name ] ) ) {
				/*
				$arr_top_include_file[ $type ] = array(
					'' => 'Chọn file thiết kế cho phần ' . $type
				);
				*/
				
				$arr_top_include_file[ $type ] = array();
				
				$str_list_all_include_file[] = '<input type="text" name="' . $j2_name . '" id="' . $j2_name . '" value="' . $__cf_row[ $j2_name ] . '" data-type="' . $type . '" class="each-to-get-current-value-file" />';
			}
			
			break;
		}
	}
	$str_list_all_include_file[] = '<br>';
//	print_r( $str_list_all_include_file );
//	$arr_for_set_template['str_list_all_include_file'] = implode( "\n", $str_list_all_include_file );
	
	foreach ( $arr_file_name as $v ) {
		$file_name = basename( $v );
		$node = explode( '-', $file_name );
//		print_r( $node );
//		$node = $node[0];
		if ( count( $node ) == 1 ) {
			$node = str_replace( '_', '', $type );
		} else {
			$node = str_replace( $file_type, '', $node[1] );
		}
//		echo $node . "\n";
		
		//
	//	if ( ! empty( $arr_top_include_file[ $node ] ) ) {
	//		$arr_top_include_file[ $node ] = array();
	//	} else {
	//		echo $v . "\n";
			$arr_top_include_file[ $node ][$v] = 1;
	//	}
	}
//	print_r( $arr_top_include_file );
	
	$str_top_include_file = '
	<div class="change-eb-design-note d-none"><em>* Các file sẽ xuất hiện lần lượt theo vị trí đã chọn!</em></div>
	<div class="button-for-ebdesign-hover">
		<input type="text" id="press_for_search_eb_themes" placeholder="Tìm kiếm giao diện theo tên" class="eb-themes-search" />
		<button type="button" data-type="' . $type . '" class="click-remove-file-include-form-input cur">[ Xóa file ]</button>
		<button type="button" data-type="' . $type . '" class="click-add-widget-include-to-input cur">[ ' . $type . ' widget ]</button>
		<button type="button" class="cur click-to-exit-design d-none2 show-if-ebdesign-hover">Đóng [x]</button>
	</div>';
	
	$i = 0;
	foreach ( $arr_top_include_file as $k => $v ) {
//		print_r($v);
		
		$str_top_include_file .= '<br><h3>' . $k . '</h3>';
		
		$label_name = 'cf_' . $k . '_include_file';
		
		foreach ( $v as $k2 => $v2 ) {
			
			$label_id = $label_name . $i;
			$file_tag = '';
			$for_domain = '';
			
			if ( $k2 == '' ) {
//				$str_top_include_file .= '<hr>';
				$val = '';
				$text = '[' . $v2 . ']';
			} else {
				// lấy thông tin file để tạo tag
				$file_tag = file_get_contents( $k2, 1 );
				
				// Tìm theo domain, nếu file được set cho domain cụ thể -> kiểm tra domain có trùng không
				$for_domain = EBE_config_theme_load_file_tag( $file_tag, 'Domain' );
				if ( $for_domain != '' ) {
					$for_domain = strtolower( str_replace( 'www.', '', $for_domain ) );
				}
//				echo $for_domain . '<br>' . "\n";
				
				$k2 = basename($k2);
				$val = $k2;
				$text = 'Mẫu #' . str_replace( $file_type, '', $k2 );
			}
			
			
			//
			if ( $for_domain == ''
			// theo tên miền chính
			|| $current_domain == $for_domain
			// theo sub-domain -> tạo thêm dấu . ở đầu chuỗi
			|| strstr( $current_domain, '.' . $for_domain ) == true ) {
				
				//
				$ck = '';
				if ( $val == $__cf_row[ $label_name ] ) {
					$ck = ' checked="checked"';
				}
				
				
				// kiểm tra và lấy hình nền nếu có
				$bg = '';
				$css_class = '';
				$img = '';
				if ( $val != '' ) {
					$bg_file = EB_THEME_PLUGIN_INDEX . 'themes/images/' . str_replace( $file_type, '.jpg', $val );
					if ( file_exists( $bg_file ) ) {
						$file_info = getimagesize( $bg_file );
						
						$img = str_replace( EB_THEME_PLUGIN_INDEX, EB_URL_OF_PLUGIN, $bg_file );
						
						$css_class = 'preview-bg-ebdesign';
						if ( $ck != '' ) {
							$css_class .= ' selected';
							
	//						$str_top_design_preview .= '<div title="Click to change design" data-key="' . $k . '" data-size="' . $file_info[1] . '/' . $file_info[0] . '" class="click-to-change-file-design preview-file-design" style="background-image:url(\'' . $img . '\');">&nbsp;</div>';
	//						echo $bg_file;
						}
						
						$bg = ' data-size="' . $file_info[1] . '/' . $file_info[0] . '" style="height: ' . $file_info[1] . 'px;background-image:url(\'' . $img . '\');"';
					}
				}
				
				/*
				// v1
				$str_top_include_file .= '
				<div data-img="' . $img . '" data-key="' . $k . '" data-val="' . $val . '" title="' . $text . '" class="click-add-class-selected preview-in-ebdesign ' . $css_class . '" ' . $bg . '>
					<input type="radio" name="' . $label_name . '" id="' .$label_id. '" value="' .$val. '" ' . $ck . '>
					<label for="' .$label_id. '">' .$text. '</label>
				</div>';
				*/
				
				// v2
				$str_top_include_file .= '
				<div data-img="' . $img . '" data-key="' . $k . '" data-val="' . $val . '" data-type="' . $label_name . '" title="' . $text . '" class="click-add-class-selected preview-in-ebdesign ' . $css_class . '" ' . $bg . '>' .$text. '</div>
				<div class="small text-right hide-if-threadnode"><em>' . $text . '</em></div>';
				
			}
			
			//
			$i++;
		}
	}
//	echo $str_top_include_file;
	
	/*
	return array(
		'list' => $str_top_include_file,
		'preview' => $str_top_design_preview
	);
	*/
	
	return $str_top_include_file;
	
}

//
/*
$arr_design_preview = EBE_config_load_top_footer_include();
$arr_for_set_template['str_top_include_file'] = $arr_design_preview['list'];
$arr_for_set_template['str_top_design_preview'] = $arr_design_preview['preview'];
*/

$arr_for_set_template['str_top_include_file'] = EBE_config_load_top_footer_include();
$str_top_design_preview = '';
for ( $i = 1; $i < 10; $i++ ) {
	$j_name = 'cf_top' . $i . '_include_file';
	
	if ( isset( $__cf_row_default[ $j_name ] ) ) {
		$str_top_design_preview .= '<div title="[Bấm đây để chọn thiết kế hoặc để trống]" data-name="' . $j_name . '" data-key="top' . $i . '" data-val="' . $__cf_row[ $j_name ] . '" class="click-to-change-file-design preview-file-design">&nbsp;</div>';
	} else {
		break;
	}
}
$arr_for_set_template['str_top_design_preview'] = $str_top_design_preview;


//
/*
$arr_design_preview = EBE_config_load_top_footer_include('footer');
$arr_for_set_template['str_footer_include_file'] = $arr_design_preview['list'];
$arr_for_set_template['str_footer_design_preview'] = $arr_design_preview['preview'];
*/

$arr_for_set_template['str_footer_include_file'] = EBE_config_load_top_footer_include('footer');
$str_footer_design_preview = '';
for ( $i = 1; $i < 10; $i++ ) {
	$j_name = 'cf_footer' . $i . '_include_file';
	
	if ( isset( $__cf_row_default[ $j_name ] ) ) {
		$str_footer_design_preview .= '<div title="[Bấm đây để chọn thiết kế hoặc để trống]" data-name="' . $j_name . '" data-key="footer' . $i . '" data-val="' . $__cf_row[ $j_name ] . '" class="click-to-change-file-design preview-file-design">&nbsp;</div>';
	} else {
		break;
	}
}
$arr_for_set_template['str_footer_design_preview'] = $str_footer_design_preview;




//
$arr_for_set_template['str_threadnode_include_file'] = EBE_config_load_top_footer_include('threadnode', '.html');

$str_threadnode_design_preview = '<div title="[Bấm đây để chọn thiết kế hoặc để trống]" data-name="cf_threadnode_include_file" data-key="threadnode" data-val="' . $__cf_row[ 'cf_threadnode_include_file' ] . '" class="click-to-change-file-design preview-file-design">&nbsp;</div>';

$arr_for_set_template['str_threadnode_design_preview'] = $str_threadnode_design_preview;

$arr_for_set_template['cf_threadnode_title_tag'] = __eb_create_select_checked_config(
	array(
		'div' => 'DIV',
		'p' => 'P',
		'li' => 'LI',
		'h2' => 'H2',
		'h3' => 'H3',
		'h4' => 'H4',
		'h5' => 'H5',
		'h6' => 'H6'
	),
	$__cf_row['cf_threadnode_title_tag'],
	'cf_threadnode_title_tag'
);




//
$arr_for_set_template['str_threaddetails_include_file'] = EBE_config_load_top_footer_include('threaddetails', '.html');

$str_threaddetails_design_preview = '<div title="[Bấm đây để chọn thiết kế hoặc để trống]" data-name="cf_threaddetails_include_file" data-key="threaddetails" data-val="' . $__cf_row[ 'cf_threaddetails_include_file' ] . '" class="click-to-change-file-design preview-file-design">&nbsp;</div>';

$arr_for_set_template['str_threaddetails_design_preview'] = $str_threaddetails_design_preview;




//
global $str_list_all_include_file;
//print_r( $str_list_all_include_file );
$arr_for_set_template['str_list_all_include_file'] = implode( "\n", $str_list_all_include_file );







// -> HTML
$main_content = EBE_arr_tmp( $arr_for_set_template, $main_content );






// các cấu hình mặc định
$main_content = EBE_arr_tmp( $__cf_row, $main_content );

$main_content = EBE_arr_tmp( $__cf_row_default, $main_content, 'aaa.' );






// cập nhật lại rule cho wp
if ( $__cf_row['cf_remove_category_base'] == 1 ) {
	add_action( 'shutdown', 'flush_rewrite_rules' );
}




