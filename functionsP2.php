<?php


function EBE_get_big_banner ( $limit = 5, $option = array () ) {
	global $__cf_row;
	
	//
	$a = _eb_load_ads(
		1,
		$limit,
		$__cf_row['cf_top_banner_size'],
		$option,
		0,
		'
<li class="global-a-posi"><a href="{tmp.p_link}" title="{tmp.post_title}"{tmp.target_blank}>&nbsp;</a>
	<div data-size="{tmp.data_size}" data-img="{tmp.trv_img}" data-table-img="{tmp.trv_table_img}" data-mobile-img="{tmp.trv_mobile_img}" class="ti-le-global banner-ads-media" style="background-image:url({tmp.trv_img});">&nbsp;</div>
</li>',
		array(
			'default_value' => ''
		)
	);
	
	//
	if ( $a != '' ) {
		$a = '<div class="oi_big_banner">' . $a . '</div>';
	}
	
	//
	return $a;
}


function _eb_load_post_obj ( $posts_per_page, $_eb_query ) {
	
	//
	$arr['post_type'] = 'post';
	$arr['posts_per_page'] = $posts_per_page;
//	$arr['orderby'] = 'menu_order';
	$arr['orderby'] = 'menu_order ID';
	$arr['order'] = 'DESC';
	$arr['post_status'] = 'publish';
	
	//
	foreach ( $_eb_query as $k => $v ) {
		$arr[$k] = $v;
	}
//	print_r( $_eb_query );
//	print_r( $arr );
	
	// https://codex.wordpress.org/Class_Reference/WP_Query
	return new WP_Query( $arr );
	
}

/*
* Load danh sách đơn hàng
*/
// tạo đơn hàng
function EBE_set_order ( $arr ) {
	_eb_sd( $arr, 'eb_in_con_voi' );
	
	// lấy ID trả về
	$strsql = _eb_q("SELECT *
	FROM
		`eb_in_con_voi`
	WHERE
		tv_id = " . $arr['tv_id'] . "
	ORDER BY
		order_id DESC
	LIMIT 0, 1" );
//	print_r( $strsql );
//	echo count($strsql);
	if ( count($strsql) > 0 ) {
		return $strsql[0]->order_id;
	}
	
	return 0;
}

// xóa chi tiết đơn hàng
function EBE_update_details_order ( $k, $id, $v = '' ) {
	_eb_q ( "DELETE
	FROM
		`eb_details_in_con_voi`
	WHERE
		order_id = " . $id . "
		AND dorder_key = '" . $k . "'" );
	
	// nếu có value mới -> add mới luôn
	if ( $v != '' ) {
		EBE_set_details_order( $k, $v, $id );
	}
}

// tạo chi tiết đơn hàng
function EBE_set_details_order ( $k, $v, $id ) {
	_eb_q ( "INSERT INTO
	`eb_details_in_con_voi`
	( dorder_key, dorder_name, order_id )
	VALUES
	( '" . $k . "', '" . $v . "', " . $id . " )" );
}

// danh sách đơn hàng
function _eb_load_order ( $posts_per_page = 68, $_eb_query = array() ) {
	
	//
//	print_r( $_eb_query );
	
	//
	$strFilter = "";
	$offset = 0;
	
	// lấy theo ID hóa đơn
	if ( isset( $_eb_query['offset'] ) ) {
		$offset = $_eb_query['offset'];
	}
	
	// lấy theo ID hóa đơn
	if ( isset( $_eb_query['p'] ) && $_eb_query['p'] > 0 ) {
		$strFilter .= " AND order_id = " . $_eb_query['p'];
	}
	
	// lấy theo trạng thái hóa đơn
//	if ( isset( $_eb_query['status_by'] ) && (int) $_eb_query['status_by'] != '' ) {
	if ( isset( $_eb_query['status_by'] ) ) {
		$strFilter .= " AND order_status = " . (int) $_eb_query['status_by'];
	}
	
	// lấy theo filter có sẵn
	if ( isset( $_eb_query['filter_by'] ) ) {
		$strFilter .= " " . $_eb_query['filter_by'];
	}
	
	//
//	echo $strFilter . '<br>' . "\n";
	
	//
	$sql = _eb_q( "SELECT *
	FROM
		`eb_in_con_voi`
	WHERE
		tv_id > 0
		" . $strFilter . "
	ORDER BY
		order_id DESC
	LIMIT " . $offset . ", " . $posts_per_page );
//	print_r( $sql );
	
	return $sql;
	
}

function _eb_load_order_v1 ( $posts_per_page = 68, $_eb_query = array() ) {
	global $wpdb;
	
	//
//	print_r( $_eb_query );
	
	//
	$strFilter = "";
	if ( isset( $_eb_query['p'] ) && $_eb_query['p'] > 0 ) {
		$strFilter .= " AND ID = " . $_eb_query['p'];
	}
	
	//
	$sql = _eb_q( "SELECT *
	FROM
		" . $wpdb->posts . "
	WHERE
		post_type = 'shop_order'
		AND post_status = 'private'
		" . $strFilter . "
	ORDER BY
		ID DESC
	LIMIT 0, " . $posts_per_page );
//	print_r( $sql );
	
	return $sql;
	
	
	//
	/*
	$_eb_query['post_type'] = 'shop_order';
	$_eb_query['post_status'] = 'private';
	$_eb_query['orderby'] = 'ID';
	$_eb_query['order'] = 'DESC';
	
	return _eb_load_post_obj( $posts_per_page, $_eb_query );
	*/
}

/*
* https://codex.wordpress.org/Class_Reference/WP_Query
* posts_per_page: số lượng bài viết cần lấy
* _eb_query: gán giá trị để thực thi wordpres query
* html: mặc định là sử dụng HTML của theme, file thread_node.html, nếu muốn sử dụng HTML riêng thì truyền giá trị HTML mới vào
* not_set_not_in: mặc định là lọc các sản phẩm trùng lặp trên mỗi trang, nếu để bằng 1, sẽ bỏ qua chế độ lọc -> chấp nhận lấy trùng
*/
function _eb_load_post ( $posts_per_page = 20, $_eb_query = array(), $html = __eb_thread_template, $not_set_not_in = 0 ) {
	global $___eb_post__not_in;
//	echo 'POST NOT IN: ' . $___eb_post__not_in . '<br>' . "\n";
	
	// lọc các sản phẩm trùng nhau
	if ( $___eb_post__not_in != '' && $not_set_not_in == 0 ) {
		$_eb_query['post__not_in'] = explode( ',', substr( $___eb_post__not_in, 1 ) );
	}
	
	//
	$sql = _eb_load_post_obj( $posts_per_page, $_eb_query );
	
	//
//	if ( $_eb_query['post_type'] == 'blog' ) {
//		print_r( $sql );
//		print_r( $_eb_query );
//		exit();
//	}
	
	//
	$str = '';
	
	//
	while ( $sql->have_posts() ) {
		
		$sql->the_post();
//			the_content();
		
		//
		if ( $not_set_not_in == 0 ) {
			$___eb_post__not_in .= ',' . $sql->post->ID;
		}
		
		//
		$str .= EBE_select_thread_list_all( $sql->post, $html );
		
	}
	
	//
	wp_reset_postdata();
	
	// ưu tiên sử dụng URL tương đối -> có thể gây lỗi trên 1 số phiên bản -> bỏ
//	return str_replace( web_link, '', _eb_supper_del_line( $str ) );
	return _eb_supper_del_line( $str );
}






function _eb_checkPostServerClient() {
	if ($_SERVER ['REQUEST_METHOD'] != 'POST') {
		die ( '<h1>POST DIE</h1>' );
		exit ();
	}
	
	
	$checkPostServer = $_SERVER ['HTTP_HOST'];
	$checkPostServer = str_replace ( 'www.', '', $checkPostServer );
//		$checkPostServer = explode ( '/', $checkPostServer );
//		$checkPostServer = $checkPostServer [0];
	
	$checkPostClient = isset ( $_SERVER ['HTTP_REFERER'] ) ? $_SERVER ['HTTP_REFERER'] : '';
	$checkPostClient = explode( '//', $checkPostClient );
	$checkPostClient = $checkPostClient[1];
	$checkPostClient = str_replace ( 'www.', '', $checkPostClient );
	$checkPostClient = explode ( '/', $checkPostClient );
	$checkPostClient = $checkPostClient [0];
	
	//
	if ( strtolower ( $checkPostServer ) != strtolower ( $checkPostClient ) ) {
		die ( '<h1>REFERER DIE</h1>' );
		exit ();
	}
	
	
	
	/*
	* xử lý an toàn cho chuỗi
	*/
	
	// kiểm tra get_magic_quotes_gpc đang bật hay tắt
	// Magic_quotes_gpc là 1 giá trị tùy chọn bật chế độ tự động thêm ký tự escape vào trước các ký tự đặc biệt như: nháy đơn ('), nháy kép ("), dấu backslash (\) khi nó đc POST hoặc GET từ client lên
	$magic_quotes = 0;
	if ( get_magic_quotes_gpc () ) {
		$magic_quotes = 1;
	}
	
	//
	foreach ( $_POST as $k => $v ) {
		if ( gettype( $v ) == 'string' ) {
			// nếu Magic_quotes_gpc đang tắt -> loại bỏ ký tự đặc biệt
//				if ( $magic_quotes == 1 ) {
//					$v = stripslashes ( $v );
//				}
			
			// nếu Magic_quotes_gpc đang tắt -> add dữ liệu an toàn thủ công vào
			if ( $magic_quotes == 0 ) {
				// xử lý an toàn cho chuỗi
				$v = addslashes ( $v );
				
				// mysqli_real_escape_string tương tự như addslashes, nhưng công việc sẽ do mysql xử lý
//					$str = mysqli_real_escape_string ( $str );
			}
			
			$_POST [$k] = $v;
		}
	}
	
	
	//
	return $_POST;
}

//
function EBE_stripPostServerClient() {
	foreach ( $_POST as $k => $v ) {
		$_POST[$K] = trim( strip_tags( $v ) );
	}
}



function _eb_checkDevice () {
	if ( wp_is_mobile() ) {
		return 'mobile';
	}
	// mặc định cho rằng đây là PC
	return 'pc';
}

function _eb_checkDevice_v1 () {
	if ( isset($_SERVER['HTTP_USER_AGENT']) ) {
		// lấy thông tin hệ điều hành của người dùng
		$_ebArrUAAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
		// mảng các thiết bị mobile chuyên dụng
		$_ebArrMobi = array('midp', 'j2me', 'avantg', 'ipad', 'iphone', 'docomo', 'novarra', 'palmos', 'palmsource', '240x320', 'opwv', 'chtml', 'pda', 'windows ce', 'mmp/', 'mib/', 'symbian', 'wireless', 'nokia', 'hand', 'mobi', 'phone', 'cdm', 'up.b', 'audio', 'sie-', 'sec-', 'samsung', 'htc', 'mot-', 'mitsu', 'sagem', 'sony', 'alcatel', 'lg', 'erics', 'vx', 'nec', 'philips', 'mmm', 'xx', 'panasonic', 'sharp', 'wap', 'sch', 'rover', 'pocket', 'benq', 'java', 'pt', 'pg', 'vox', 'amoi', 'bird', 'compal', 'kg', 'voda', 'sany', 'kdd', 'dbt', 'sendo', 'sgh', 'gradi', 'jb', 'dddi', 'moto', 'opera mobi', 'opera mini', 'android');
		foreach ($_ebArrMobi as $k => $v) {
			// nếu không xác định được chuỗi
			if (strpos($_ebArrUAAgent, $v) === false) {
				// ~> bỏ qua ko làm gì cả
			}
			// nếu tìm được -> trả về thông tin rằng đây là thiết bị mobile
			else {
				return 'mobile';
				break;
			}
		}
	}
	// mặc định cho rằng đây là PC
	return 'pc';
}



// Chuyển ký tự UTF-8 -> ra bảng mã mới
function _eb_str_block_fix_content ($str) {
	if ($str == '') {
		return '';
	}
	
//	$str = iconv('UTF-16', 'UTF-8', $str);
//	$str = mb_convert_encoding($str, 'UTF-8', 'UTF-16');
//	$str = mysqli_escape_string($str);
//	$str = htmlentities($str, ENT_COMPAT, 'UTF-16');
	// https://www.google.com/search?q=site:charbase.com+%E1%BB%9D#q=site:charbase.com+%E1%BA%A3
	$arr = array(
		'á' => '\u00e1',
		'à' => '\u00e0',
		'ả' => '\u1ea3',
		'ã' => '\u00e3',
		'ạ' => '\u1ea1',
		'ă' => '\u0103',
		'ắ' => '\u1eaf',
		'ặ' => '\u1eb7',
		'ằ' => '\u1eb1',
		'ẳ' => '\u1eb3',
		'ẵ' => '\u1eb5',
		'â' => '\u00e2',
		'ấ' => '\u1ea5',
		'ầ' => '\u1ea7',
		'ẩ' => '\u1ea9',
		'ẫ' => '\u1eab',
		'ậ' => '\u1ead',
		'Á' => '\u00c1',
		'À' => '\u00c0',
		'Ả' => '\u1ea2',
		'Ã' => '\u00c3',
		'Ạ' => '\u1ea0',
		'Ă' => '\u0102',
		'Ắ' => '\u1eae',
		'Ặ' => '\u1eb6',
		'Ằ' => '\u1eb0',
		'Ẳ' => '\u1eb2',
		'Ẵ' => '\u1eb4',
		'Â' => '\u00c2',
		'Ấ' => '\u1ea4',
		'Ầ' => '\u1ea6',
		'Ẩ' => '\u1ea8',
		'Ẫ' => '\u1eaa',
		'Ậ' => '\u1eac',
		'đ' => '\u0111',
		'Đ' => '\u0110',
		'é' => '\u00e9',
		'è' => '\u00e8',
		'ẻ' => '\u1ebb',
		'ẽ' => '\u1ebd',
		'ẹ' => '\u1eb9',
		'ê' => '\u00ea',
		'ế' => '\u1ebf',
		'ề' => '\u1ec1',
		'ể' => '\u1ec3',
		'ễ' => '\u1ec5',
		'ệ' => '\u1ec7',
		'É' => '\u00c9',
		'È' => '\u00c8',
		'Ẻ' => '\u1eba',
		'Ẽ' => '\u1ebc',
		'Ẹ' => '\u1eb8',
		'Ê' => '\u00ca',
		'Ế' => '\u1ebe',
		'Ề' => '\u1ec0',
		'Ể' => '\u1ec2',
		'Ễ' => '\u1ec4',
		'Ệ' => '\u1ec6',
		'í' => '\u00ed',
		'ì' => '\u00ec',
		'ỉ' => '\u1ec9',
		'ĩ' => '\u0129',
		'ị' => '\u1ecb',
		'Í' => '\u00cd',
		'Ì' => '\u00cc',
		'Ỉ' => '\u1ec8',
		'Ĩ' => '\u0128',
		'Ị' => '\u1eca',
		'ó' => '\u00f3',
		'ò' => '\u00f2',
		'ỏ' => '\u1ecf',
		'õ' => '\u00f5',
		'ọ' => '\u1ecd',
		'ô' => '\u00f4',
		'ố' => '\u1ed1',
		'ồ' => '\u1ed3',
		'ổ' => '\u1ed5',
		'ỗ' => '\u1ed7',
		'ộ' => '\u1ed9',
		'ơ' => '\u01a1',
		'ớ' => '\u1edb',
		'ờ' => '\u1edd',
		'ở' => '\u1edf',
		'ỡ' => '\u1ee1',
		'ợ' => '\u1ee3',
		'Ó' => '\u00d3',
		'Ò' => '\u00d2',
		'Ỏ' => '\u1ece',
		'Õ' => '\u00d5',
		'Ọ' => '\u1ecc',
		'Ô' => '\u00d4',
		'Ố' => '\u1ed0',
		'Ồ' => '\u1ed2',
		'Ổ' => '\u1ed4',
		'Ỗ' => '\u1ed6',
		'Ộ' => '\u1ed8',
		'Ơ' => '\u01a0',
		'Ớ' => '\u1eda',
		'Ờ' => '\u1edc',
		'Ở' => '\u1ede',
		'Ỡ' => '\u1ee0',
		'Ợ' => '\u1ee2',
		'ú' => '\u00fa',
		'ù' => '\u00f9',
		'ủ' => '\u1ee7',
		'ũ' => '\u0169',
		'ụ' => '\u1ee5',
		'ư' => '\u01b0',
		'ứ' => '\u1ee9',
		'ừ' => '\u1eeb',
		'ử' => '\u1eed',
		'ữ' => '\u1eef',
		'ự' => '\u1ef1',
		'Ú' => '\u00da',
		'Ù' => '\u00d9',
		'Ủ' => '\u1ee6',
		'Ũ' => '\u0168',
		'Ụ' => '\u1ee4',
		'Ư' => '\u01af',
		'Ứ' => '\u1ee8',
		'Ừ' => '\u1eea',
		'Ử' => '\u1eec',
		'Ữ' => '\u1eee',
		'Ự' => '\u1ef0',
		'ý' => '\u00fd',
		'ỳ' => '\u1ef3',
		'ỷ' => '\u1ef7',
		'ỹ' => '\u1ef9',
		'ỵ' => '\u1ef5',
		'Ý' => '\u00dd',
		'Ỳ' => '\u1ef2',
		'Ỷ' => '\u1ef6',
		'Ỹ' => '\u1ef8',
		'Ỵ' => '\u1ef4',
		// Loại bỏ dòng trắng
//			';if (' => ';if(',
//			'{if (' => '{if(',
//			'}if (' => '}if(',
//			'} else if (' => '}else if(',
//			'} else {' => '}else{',
//			'}else {' => '}else{',
//			';for (' => ';for(',
//			'}for (' => '}for(',
//			'function (' => 'function(',
//			//
//			' != ' => '!=',
//			' == ' => '==',
//			' || ' => '||',
//			' -= ' => '-=',
//			' += ' => '+=',
//			' && ' => '&&',
//			//
//			') {' => '){',
//			';}' => '}',
//			' = \'' => '=\'',
//			'\' +' => '\'+',
//			'+ \'' => '+\'',
//			' = ' => '=',
//			'}, {' => '},{',
//			'}, ' => '},',
		'\'' => '\\\'',
		'"' => '&quot;',
//		'' => ''
	);
	foreach ($arr as $k => $v) {
		if ($v != '') {
			$str = str_replace($k, $v, $str);
		}
	}
//	$str = str_replace('\\', '/', str_replace("'", "\'", $str) );
	return $str;
}




function _eb_postUrlContent($url, $data = '', $head = 0) {
	global $post_get_cc;
	
	return $post_get_cc->post ( $url, $data, $head );
}
function _eb_getUrlContent($url, $agent = '', $options = array(), $head = 0) {
	global $post_get_cc;
	
	return $post_get_cc->get ( $url, $agent, $options, $head );
}




// fix URL theo 1 chuẩn nhất định
function _eb_fix_url( $url ) {
//	echo $url . '<br>' . "\n";
//	echo _eb_full_url() . '<br>' . "\n";
	
	//
//	if ( strstr( $url, '//' ) != strstr( _eb_full_url (), '//' ) ) {
	// nếu không có dấu ? -> không có tham số nào được truyền trên URL
	if ( strstr( _eb_full_url (), '?' ) == false
	// nếu URL khác nhau
	&& strstr( strstr( _eb_full_url (), '//' ), strstr( $url, '//' ) ) == false ) {
//	if ( count( explode( strstr( $url, '//' ), strstr( _eb_full_url (), '//' ) ) ) == 1 ) {
		
//		header ( 'Location:' . $url, true, 301 );
		
		wp_redirect( $url, 301 );
		
		exit();
		
	}
	
	return true;
}

// short link
function _eb_s_link ($id, $seo = 'p') {
	return web_link . '?' . $seo . '=' . $id;
}

// link cho sản phẩm
function _eb_p_link ($id, $seo = '') {
	$strCacheFilter = 'prod_link' . $id;
	$a = _eb_get_static_html ( $strCacheFilter, '', '', eb_default_cache_time );
	if ($a == false) {
		$a = get_the_permalink( $id );
		if ( $a == '' ) {
			$a = _eb_s_link($id);
		}
		
		//
		_eb_get_static_html ( $strCacheFilter, $a, '', 60 );
	}
//	echo $a . '<br>' . "\n";
	
	return $a;
}



// link cho phân nhóm
$arr_cache_for_get_cat_url = array();

function _eb_c_link ( $id, $taxx = '' ) {
	global $arr_cache_for_get_cat_url;
	
	//
	if ( isset($arr_cache_for_get_cat_url[ $id ]) ) {
		return $arr_cache_for_get_cat_url[ $id ];
	}
	
	$strCacheFilter = 'cat_link' . $id;
	$a = _eb_get_static_html ( $strCacheFilter, '', '', eb_default_cache_time );
//	$a = _eb_get_static_html ( $strCacheFilter, '', '', 5 );
	if ($a == false) {
		
		//
		if ( $taxx == '' || $taxx == 'category' ) {
			$a = get_category_link( $id );
		}
		else {
			$a = get_term_link( get_term_by( 'id', $id, $taxx ), $taxx );
		}
		
		//
		if ( isset($a->errors) || $a == '' ) {
//			print_r($a);
			
			// lấy theo blog
//			if ( $taxx != '' ) {
//				$a = get_term_link( get_term_by( 'id', $id, $taxx ), $taxx );
//			}
//			else {
				$a = get_term_link( get_term_by( 'id', $id, EB_BLOG_POST_LINK ), EB_BLOG_POST_LINK );
				if ( isset($a->errors) || $a == '' ) {
					$a = get_term_link( get_term_by( 'id', $id, 'post_options' ), 'post_options' );
					
					// lấy theo post_tag
					if ( isset($a->errors) || $a == '' ) {
						$a = get_term_link( get_term_by( 'id', $id, 'post_tag' ), 'post_tag' );
					}
				}
//			}
			
			//
			if ( isset($a->errors) || $a == '' ) {
				$a = '#';
			}
		}
		// xóa ký tự đặc biệt khi rút link category
		$a = str_replace( '/./', '/', $a );
		
		//
		_eb_get_static_html ( $strCacheFilter, $a, '', 60 );
	}
	
	//
	$arr_cache_for_get_cat_url[ $id ] = $a;
	
	return $a;
}

function _eb_c_link_v1 ( $id, $taxx = 'category' ) {
	global $arr_cache_for_get_cat_url;
	
	//
	if ( isset($arr_cache_for_get_cat_url[ $id ]) ) {
		return $arr_cache_for_get_cat_url[ $id ];
	}
	
	$strCacheFilter = 'cat_link' . $id;
	$a = _eb_get_static_html ( $strCacheFilter, '', '', eb_default_cache_time );
//		$a = _eb_get_static_html ( $strCacheFilter, '', '', 5 );
	if ($a == false) {
		
		//
		$a = get_category_link( $id );
//		$a = get_term_link( get_term( $id, $taxx ), $taxx );
		
		// nếu trùng với short link -> không ghi cache nữa
		/*
		if ( $a == $default_return ) {
			return $a;
		}
		*/
		
		//
		if ( isset($a->errors) || $a == '' ) {
//			print_r($a);
			
			$tem = get_term_by( 'id', $id, EB_BLOG_POST_LINK );
			
			// lấy theo blog
			$a = get_term_link( $tem, EB_BLOG_POST_LINK );
//				$a = get_term_link( get_term( $id, EB_BLOG_POST_LINK ), EB_BLOG_POST_LINK );
			
			// lấy theo post_options
			if ( isset($a->errors) || $a == '' ) {
				$a = get_term_link( $tem, 'post_options' );
				
				// lấy theo post_tag
				if ( isset($a->errors) || $a == '' ) {
					$a = get_term_link( $tem, 'post_tag' );
				}
			}
		}
		
		//
		if ( isset($a->errors) || $a == '' ) {
			$a = '#';
		}
		// xóa ký tự đặc biệt khi rút link category
		else {
			$a = str_replace( '/./', '/', $a );
		}
//			echo $id . ' -> ' . $a . '<br>' . "\n";
		
		//
		_eb_get_static_html ( $strCacheFilter, $a, '', 60 );
	}
//		echo $a . '<br>' . "\n";
	
	//
	$arr_cache_for_get_cat_url[ $id ] = $a;
	
	return $a;
}

// blog
function _eb_b_link($id, $seo = '') {
	return _eb_p_link($id);
}

// blog group
function _eb_bs_link($id, $seo = '') {
	return _eb_c_link( $id );
}



function _eb_remove_file ($file_, $ftp = 1) {
	if ( file_exists( $file_ ) ) {
		if ( ! unlink( $file_ ) ) {
			// thử xóa bằng ftp
			if ( $ftp == 1 ) {
				return EBE_ftp_remove_file($file_);
			}
		} else {
			return true;
		}
	}
	
	return false;
}

function _eb_create_file (
	$file_,
	$content_,
	$add_line = '',
	$ftp = 1,
	$set_permission = 0777
) {
	
	//
	if ( ! file_exists( $file_ ) ) {
		$filew = fopen( $file_, 'x+' );
		
		// nếu không tạo được file
		if ( ! $filew ) {
			// thử tạo bằng ftp
			if ( $ftp == 1 ) {
				return EBE_ftp_create_file( $file_, $content_, $add_line );
			}
			
			//
			echo 'ERROR create file: ' . $file_ . '<br>' . "\n";
			return false;
		}
		else {
			// nhớ set 777 cho file
			chmod($file_, $set_permission);
		}
		fclose($filew);
	}
	
	//
	if ( $add_line != '' ) {
		$aa = file_put_contents( $file_, $content_, FILE_APPEND );
//		chmod($file_, 0777);
	}
	//
	else {
//		file_put_contents( $file_, $content_, LOCK_EX ) or die('ERROR: write to file');
		$aa = file_put_contents( $file_, $content_ );
//		chmod($file_, 0777);
	}
	
	//
	if ( ! $aa ) {
		if ( EBE_ftp_create_file( $file_, $content_, $add_line ) != true ) {
			echo 'ERROR write to file: ' . $file_ . '<br>' . "\n";
			return false;
		}
	}
	
	
	
	/*
	* add_line: thêm dòng mới
	*/
//	$content_ = str_replace('\"', '"', $content_);
//	$content_ = str_replace("\'", "'", $content_);
	
	/*
	// nếu tồn tại file rồi -> sửa
	if (file_exists($file_)) {
//			if( flock( $file_, LOCK_EX ) ) {
			// open
	//		$fh = fopen($file_, 'r+') or die('ERROR: open 1');
	//		$str_data = fread($fh, filesize($file_));
			if ($add_line != '') {
				$fh = fopen($file_, 'a+') or die('ERROR: add to file');
			} else {
				$fh = fopen($file_, 'w+') or die('ERROR: write to file');
			}
//			}
	}
	// chưa tồn tại file -> tạo
	else {
		// open
		$fh = fopen($file_, 'x+') or die('ERROR: create file');
		chmod($file_, 0777);
	}
	
	// write
	fwrite($fh, $content_) or die('ERROR: write');
	// close
	fclose($fh) or die('ERROR: close');
	*/
	
	return true;
}

function WGR_copy ( $source, $path, $ftp = 1, $ch_mod = 0644 ) {
	if ( copy( $source, $path ) ) {
		chmod($path, $ch_mod) or die('ERROR chmod WGR_copy: ' . $path);
		return true;
	}
	
	// Không thì tạo thông qua FTP
	if ( $ftp == 1 ) {
		return WGR_ftp_copy( $source, $path );
	}
	
	return false;
}

function WGR_ftp_copy ( $source, $path ) {
	
	//
	$ftp_server = EBE_check_ftp_account();
	if ( $ftp_server == false ) {
		echo 'FTP account not found';
		return false;
	}
	$ftp_user_name = FTP_USER;
	$ftp_user_pass = FTP_PASS;
	
	
	// tạo kết nối
	$conn_id = ftp_connect($ftp_server);
	if ( ! $conn_id ) {
		echo 'ERROR FTP connect to server<br>' . "\n";
		return false;
	}
	
	
	// đăng nhập
	if ( ! ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) ) {
		echo 'ERROR FTP login false<br>' . "\n";
		return false;
	}
	
	
	//
	$ftp_dir_root = EBE_get_config_ftp_root_dir( date_time );
	
	//
	$file_for_ftp = $path;
//	echo $file_for_ftp . '<br>';
	if ( $ftp_dir_root != '' ) {
//		echo $ftp_dir_root . '<br>';
		
		// nếu trong chuỗi file không có root dir -> báo lỗi
		if ( strstr( $file_for_ftp, '/' . $ftp_dir_root . '/' ) == false ) {
			echo 'ERROR FTP root dir not found #' . $ftp_dir_root . '<br>' . "\n";
			return false;
		}
		
		$file_for_ftp = strstr( $file_for_ftp, '/' . $ftp_dir_root . '/' );
//		echo $file_for_ftp . '<br>';
	}
	
	
	// copy qua FTP_BINARY thì mới copy ảnh chuẩn được
	ftp_put($conn_id, $file_for_ftp, $source, FTP_BINARY) or die( 'ERROR copy file via FTP #' . $path );
	
	
	//
	return false;
	
}

function EBE_create_dir ( $path, $ftp = 1 ) {
	if ( is_dir( $path ) ) {
		return true;
	}
	
	//
	if ( mkdir($path, 0755) ) {
		// server window ko cần chmod
		chmod($path, 0755) or die('ERROR chmod create dir: ' . $path);
		
		return true;
	}
	
	// Không thì tạo thông qua FTP
	if ( $ftp == 1 ) {
		return WGR_ftp_create_dir( $path );
	}
	
	return false;
}

function WGR_ftp_create_dir ( $path ) {
	if ( is_dir( $path ) ) {
		return true;
	}
	
	$ftp_dir_root = EBE_get_config_ftp_root_dir( date_time );
	
	$ftp_server = EBE_check_ftp_account();
	if ( $ftp_server == false ) {
		echo 'FTP account not found';
		return false;
	}
	$ftp_user_name = FTP_USER;
	$ftp_user_pass = FTP_PASS;
	
	
	// tạo kết nối
	$conn_id = ftp_connect($ftp_server);
	if ( ! $conn_id ) {
		echo 'ERROR FTP connect to server<br>' . "\n";
		return false;
	}
	
	
	// đăng nhập
	if ( ! ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) ) {
		echo 'ERROR FTP login false<br>' . "\n";
		return false;
	}
	
	
	//
	$file_for_ftp = $path;
	if ( $ftp_dir_root != '' ) {
		$file_for_ftp = strstr( $file_for_ftp, '/' . $ftp_dir_root . '/' );
	}
//	echo $file_for_ftp . '<br>';
//	echo EBE_create_cache_for_ftp() . '<br>';
	
	// upload file
	$result = true;
	if ( ! ftp_mkdir($conn_id, $file_for_ftp) ) {
		echo 'ERROR FTP: ftp_mkdir error<br>' . "\n";
		$result = false;
	}
	
	
	// close the connection
	ftp_close($conn_id);
	
	
	//
	return $result;
	
}

function EBE_create_cache_for_ftp () {
	return EB_THEME_CACHE . 'cache_for_ftp.txt';
}

function EBE_check_ftp_account () {
	
	if ( ! defined('FTP_USER') || ! defined('FTP_PASS') ) {
		echo 'ERROR FTP: FTP_USER or FTP_PASS not found<br>' . "\n";
		return false;
	}
	
	if ( defined('FTP_HOST') ) {
		$ftp_server = FTP_HOST;
	} else {
//		$ftp_server = $_SERVER['HTTP_HOST'];
		$ftp_server = $_SERVER['SERVER_ADDR'];
	}
//	echo $ftp_server . '<br>' . "\n";
	
	return $ftp_server;
}

function EBE_get_config_ftp_root_dir ( $content_ = '1' ) {
	global $__cf_row;
	
	// Nếu chưa có thư mục root cho FTP -> bắt đầu dò tìm
	if ( $__cf_row['cf_ftp_root_dir'] == '' ) {
		$__cf_row['cf_ftp_root_dir'] = EBE_get_ftp_root_dir( $content_ );
	}
	// Tạo file cache để truyền dữ liệu
	else {
		_eb_create_file( EBE_create_cache_for_ftp(), $content_, '', 0 );
	}
	
	return $__cf_row['cf_ftp_root_dir'];
}

function EBE_get_ftp_root_dir ( $content_ = 'test' ) {
	
	$ftp_server = EBE_check_ftp_account();
	if ( $ftp_server == false ) {
		echo 'FTP account not found';
		return '';
	}
	$ftp_user_name = FTP_USER;
	$ftp_user_pass = FTP_PASS;
//	echo $ftp_user_name . '<br>';
//	echo $ftp_user_pass . '<br>';
	
	
	// tạo kết nối
	$conn_id = ftp_connect($ftp_server);
	if ( ! $conn_id ) {
		echo 'ERROR FTP connect to server<br>' . "\n";
		return '';
	}
	
	
	// đăng nhập
	if ( ! ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) ) {
		echo 'ERROR FTP login false<br>' . "\n";
		return '';
	}
	
	
	// tạo file trong cache
	$cache_for_ftp = EBE_create_cache_for_ftp();
	
	// Tạo một file bằng hàm của PHP thường -> không dùng FTP
	if ( _eb_create_file( $cache_for_ftp, $content_, '', 0 ) != true ) {
		return '';
	}
	
	
	// lấy thư mục gốc của tài khoản FTP
	$a = explode( '/', $cache_for_ftp );
	$ftp_dir_root = '';
//	print_r( $a );
	foreach ( $a as $v ) {
//		echo $v . "\n";
		if ( $ftp_dir_root == '' && $v != '' ) {
			$file_test = strstr( $cache_for_ftp, '/' . $v . '/' );
//			echo $file_test . " - \n";
			
			//
			if ( $file_test != '' ) {
				if ( ftp_nlist($conn_id, '.' . $file_test) != false ) {
					$ftp_dir_root = $v;
					break;
				}
			}
		}
	}
//	echo $ftp_dir_root . '<br>' . "\n";
	
	//
	ftp_close($conn_id);
	
	//
	if ( $ftp_dir_root == '' ) {
		echo 'ERROR FTP: ftp_dir_root not found<br>' . "\n";
	}
	
	return $ftp_dir_root;
}

// Tạo file thông qua tài khoản FTP
function EBE_ftp_create_file ($file_, $content_, $add_line = '') {
	
	$ftp_dir_root = EBE_get_config_ftp_root_dir( $content_ );
	
	
	if ( ! file_exists( $file_ ) && ! is_dir( dirname( $file_ ) ) ) {
		echo 'ERROR FTP: dir not found<br>' . "\n";
		return false;
	}
	
	$ftp_server = EBE_check_ftp_account();
	if ( $ftp_server == false ) {
		echo 'FTP account not found';
		return false;
	}
	$ftp_user_name = FTP_USER;
	$ftp_user_pass = FTP_PASS;
	
	
	// tạo kết nối
	$conn_id = ftp_connect($ftp_server);
	if ( ! $conn_id ) {
		echo 'ERROR FTP connect to server<br>' . "\n";
		return false;
	}
	
	
	// đăng nhập
	if ( ! ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) ) {
		echo 'ERROR FTP login false<br>' . "\n";
		return false;
	}
	
	
	//
	$file_for_ftp = $file_;
//	echo $file_for_ftp . '<br>';
	if ( $ftp_dir_root != '' ) {
//		echo $ftp_dir_root . '<br>';
		
		// nếu trong chuỗi file không có root dir -> báo lỗi
		if ( strstr( $file_, '/' . $ftp_dir_root . '/' ) == false ) {
			echo 'ERROR FTP root dir not found #' . $ftp_dir_root . '<br>' . "\n";
			return false;
		}
		
		$file_for_ftp = strstr( $file_, '/' . $ftp_dir_root . '/' );
//		echo $file_for_ftp . '<br>';
	}
//	echo EBE_create_cache_for_ftp() . '<br>';
	
	// upload file
	$result = true;
	if ( ! ftp_put($conn_id, '.' . $file_for_ftp, EBE_create_cache_for_ftp(), FTP_BINARY) ) {
		echo 'ERROR FTP: ftp_put error<br>' . "\n";
		$result = false;
	}
	
	
	// close the connection
	ftp_close($conn_id);
	
	
	//
	return $result;
	
}

// Xóa file thông qua tài khoản FTP
function EBE_ftp_remove_file ($file_) {
	
	$ftp_dir_root = EBE_get_config_ftp_root_dir();
	
	
	$ftp_server = EBE_check_ftp_account();
	if ( $ftp_server == false ) {
		echo 'FTP account not found';
		return false;
	}
	$ftp_user_name = FTP_USER;
	$ftp_user_pass = FTP_PASS;
	
	
	// tạo kết nối
	$conn_id = ftp_connect($ftp_server);
	if ( ! $conn_id ) {
		echo 'ERROR FTP connect to server<br>' . "\n";
		return false;
	}
	
	
	// đăng nhập
	if ( ! ftp_login($conn_id, $ftp_user_name, $ftp_user_pass) ) {
		echo 'ERROR FTP login false<br>' . "\n";
		return false;
	}
	
	
	//
	$file_for_ftp = $file_;
	if ( $ftp_dir_root != '' ) {
		$file_for_ftp = strstr( $file_, '/' . $ftp_dir_root . '/' );
	}
	
	// upload file
	$result = true;
	if ( ! ftp_delete($conn_id, $file_for_ftp) ) {
		echo 'ERROR FTP: ftp_delete error<br>' . "\n";
		$result = false;
	}
	
	
	// close the connection
	ftp_close($conn_id);
	
	
	//
	return $result;
	
}




function _eb_setCucki ( $c_name, $c_value = 0, $c_time = 0, $c_path = '/' ) {
	__eb_set_cookie( $c_name, $c_value, $c_time, $c_path );
}
function _eb_getCucki ( $c_name, $default_value = '' ) {
	if ( isset($_COOKIE[ $c_name ]) ) {
		return $_COOKIE[ $c_name ];
	}
	return $default_value;
}



function _eb_alert($m) {
	die ( '<script type="text/javascript">alert("' . $m . '");</script>' );
}



function EBE_get_file_in_folder($open_folder, $type = '', $brace = '') {
	if ($brace != '') {
		$arr = glob ( $open_folder . $brace, GLOB_BRACE );
	} else {
		$arr = glob ( $open_folder . '*' );
	}
}

function _eb_remove_ebcache_content($dir = EB_THEME_CACHE, $remove_dir = 0) {
//		echo $dir . '<br>'; exit();
	
	// nếu ký tự cuối là dấu / -> bỏ đi
	if ( substr( $dir, -1 ) == '/' ) {
		$dir = substr( $dir, 0, -1 );
	}
//		echo $dir . '<br>';
	
//		exit();
	
	
	// lấy d.sách file và thư mục trong thư mục cần xóa
	$arr = glob ( $dir . '/*' );
//	print_r( $arr ); exit();
	
	
	/*
	* v2
	*/
	foreach ( $arr as $v ) {
//		echo $v . '<br>' . "\n";
		
		// nếu là thư mục -> xóa nội dung trong thư mục
		if ( is_dir( $v ) ) {
			// gọi lệnh xóa tiếp các file trong thư mục -> đến hết mới thôi
			_eb_remove_ebcache_content ( $v );
		}
		else if ( is_file( $v ) ) {
			unlink( $v );
		}
	}
	
	//
	return true;
	
	
	
	/*
	* v1
	*/
	// lọc lấy file
	$_file = array_filter ( $arr, 'is_file' );
	// và xóa
	array_map ( 'unlink', $_file );
	
	// lọc lấy thư mục
	$_dir = array_filter ( $arr, 'is_dir' );
	foreach ( $_dir as $v ) {
		// gọi lệnh xóa tiếp đến hết mới thôi
		_eb_remove_ebcache_content ( $v );
		
		//
//		if ($remove_dir == 1) {
//			rmdir ( $v );
//			echo $v . "\n";
//		}
	}
}


function _eb_create_account_auto ( $arr = array() ) {
	if ( count( $arr ) == 0 ) {
		return 0;
	}
	
	
	//
	$user_email = _eb_non_mark( strtolower( $arr['tv_email'] ) );
	
	// tìm theo email
	$user_id = email_exists( $user_email );
	
	// có thì trả về luôn
	if ( $user_id > 0 ) {
		return $user_id;
	}
	
	
	// tạo username từ email
	if ( ! isset( $arr['user_name'] ) || trim( $arr['user_name'] ) == '' ) {
		$user_name = str_replace( '.', '_', str_replace( '@', '', $user_email ) );
	} else {
		$user_name = strtolower( $arr['user_name'] );
	}
	$user_name = str_replace( '-', '', str_replace( '.', '', _eb_text_only( trim( $user_name ) ) ) );
	
	// Kiểm tra user có chưa
	$user_id = username_exists( $user_name );
//		echo $user_id; exit();
	
	// có thì trả về luôn
	if ( $user_id > 0 ) {
		return $user_id;
	}
	
	
	// chưa có -> tạo mới ->  mật khẩu mặc định ;))
	return wp_create_user( $user_name, 'echbay.com', $user_email );
}

/*
* Tự động tạo trang nếu chưa có
*/
function _eb_create_page( $page_url, $page_name, $page_template = '' ) {
	global $wpdb;
	
	$name = $wpdb->get_var("SELECT ID
	FROM
		" . $wpdb->posts . "
	WHERE
		post_name = '" . $page_url . "'");
	
	if ($name == '') {
		$page = array(
			'post_title' => $page_name,
			'post_type' => 'page',
			'post_content' => 'Vui lòng không xóa hoặc thay đổi bất kỳ điều gì trong trang này.',
			'post_parent' => 0,
			'post_author' => mtv_id,
			'post_status' => 'publish',
			'post_name' => $page_url,
		);
		
		// tạo page mới
//			$page = apply_filters('yourplugin_add_new_page', $page, 'teams');
		$pageid = wp_insert_post ($page);
		
		
		/*
		* add template tương ứng
		*/
		if ( $page_template == '' ) {
//				$page_template = 'templates/' . $page_url . '.php';
			$page_template = 'templates/index.php';
		}
		
		add_post_meta( $pageid, '_wp_page_template', $page_template, true );
	}
}


function _eb_create_breadcrumb ( $url, $tit ) {
	global $breadcrumb_position;
	global $group_go_to;
	
	//
	$group_go_to[$url] = ' <li><a href="' . $url . '">' . $tit . '</a></li>';
	
	//
//	echo $breadcrumb_position . "\n";
	
	$breadcrumb_position++;
	
	//
	return '
	, {
		"@type": "ListItem",
		"position": ' . $breadcrumb_position . ',
		"item": {
			"@id": "' . str_replace( '/', '\/', $url ) . '",
			"name": "' . str_replace( '"', '&quot;', $tit ) . '"
		}
	}';
}

function _eb_create_html_breadcrumb ($c) {
	global $group_go_to;
	global $schema_BreadcrumbList;
	
	//
//	print_r( $c );
	
	//
	$return_id = $c->term_id;
	
	//
	if ( $c->parent > 0 ) {
		
		//
		$return_id = $c->parent;
		
		//
		$parent_cat = get_term_by( 'id', $c->parent, $c->taxonomy );
//		print_r( $parent_cat );
		
		//
		$lnk = _eb_c_link($parent_cat->term_id);
		$group_go_to[$lnk] = ' <li><a href="' . $lnk . '">' . $parent_cat->name . '</a></li>';
		
		// tìm tiếp nhóm cha khác nếu có
		if ( $parent_cat->parent > 0 ) {
			$return_id = _eb_create_html_breadcrumb( $parent_cat );
		}
	}
	
	return $return_id;
}

function _eb_echbay_category_menu ( $id, $tax = 'category' ) {
	$str = '';
	
	$strCacheFilter = 'eb_cat_menu' . $id;
//	echo $strCacheFilter;
	
	$str = _eb_get_static_html ( $strCacheFilter );
	
	if ($str == false) {
		
		// parent
		$parent_cat = get_term_by( 'id', $id, $tax );
//		print_r( $parent_cat );
		
		// sub
		$sub_cat = get_categories( array(
//			'hide_empty' => 0,
			'parent' => $parent_cat->term_id
//			'child_of' => $parent_cat->term_id
		) );
//		print_r( $sub_cat );
		
		foreach ( $sub_cat as $k => $v ) {
			$str .= '<li><a href="' . _eb_c_link( $v->term_id ) . '">' . $v->name . '</a></li>';
		}
		
		if ( $str != '' ) {
			$str = '<ul class="sub-menu">' . $str . '</ul>';
		}
		
		// tổng hợp
		$str = '<ul><li><a href="' . _eb_c_link( $parent_cat->term_id ) . '">' . $parent_cat->name . '</a>' . $str . '</li></ul>';
		
		//
		_eb_get_static_html ( $strCacheFilter, $str );
		
	}
	
	//
	return $str;
}

function _eb_get_youtube_id ( $url ) {
	if ( $url == '' ) {
		return '';
	}
	
	//
	parse_str( parse_url( $url, PHP_URL_QUERY ), $a );
	
	if ( isset( $a['v'] ) ) {
		return $a['v'];  
	} else {
		$a = explode( '/embed/', $url );
		if ( isset( $a[1] ) ) {
			$a = explode( '?', $a[1] );
			$a = explode( '&', $a[0] );
			
			return $a[0];
		}
		
		$a = explode( '/youtu.be/', $url );
		if ( isset( $a[1] ) ) {
			$a = explode( '?', $a[1] );
			$a = explode( '&', $a[0] );
			
			return $a[0];
		}
	}
	
	return '';
}

// tiêu đề tiêu chuẩn của google < 70 ký tự
function _eb_tieu_de_chuan_seo( $str ) {
	global $__cf_row;
	
	// nếu sử dụng module SEO của EchBay
	if ( $__cf_row['cf_on_off_echbay_seo'] == 1 || is_404() ) {
		$str = trim( $str );
		
		// hoặc tự bổ sung nếu có dữ liệu đầu vào
		if ( strlen( $str ) < 35 && $__cf_row ['cf_abstract'] != '' ) {
			$str .= ' - ' . $__cf_row ['cf_abstract'];
			
			//
			if ( strlen( $str ) > 70 ) {
				$str = _eb_short_string( $str, 70 );
			}
		}
//		echo '<!-- title by EchBay -->';
	}
	// mặc định thì lấy theo mẫu của wordpress
	else {
		// chỉ lấy mỗi title cho phần trang chủ
		if ( is_home() || is_front_page() ) {
			$str = web_name;
		}
		// còn lại thì không can thiệp
		else {
			$str = wp_title( '|', false, 'right' );
		}
//		$str = wp_title( '', false );
//		echo '<!-- title by other plugin -->';
	}
	
	//
	echo '<title>' . $str . '</title>' . "\n";
//	return $str;
}

function _eb_short_string( $str, $len, $more = 1 ) {
	$str = trim ( $str );
	
	if ($len > 0 && strlen ( $str ) > $len) {
		$str = substr ( $str, 0, $len );
		if (! substr_count ( $str, " " )) {
			if ($more == 1) {
				$str .= "...";
			}
			return $str;
		}
		while ( strlen ( $str ) && ($str [strlen ( $str ) - 1] != " ") ) {
			$str = substr ( $str, 0, - 1 );
		}
		$str = substr ( $str, 0, - 1 );
		if ($more == 1) {
			$str .= "...";
		}
	}
	
	return $str;
}

function _eb_del_line ( $str, $re = "", $pe = "/\r\n|\n\r|\n|\t/i" ) {
	return preg_replace( $pe, $re, trim( $str ) );
}

function _eb_supper_del_line ( $str, $add_line = '' ) {
	$a = explode( "\n", $str );
	$str = '';
	foreach ( $a as $v ) {
		$v = trim( $v );
		if ( $v != '' ) {
			$str .= $v . $add_line;
		}
	}
	return $str;
}

function _eb_lay_email_tu_cache ( $id ) {
	if ( $id <= 0 ) {
		return 'NULL';
	}
	$strCacheFilter = 'tv_mail/' . $id;
	
	$tv_email = _eb_get_static_html ( $strCacheFilter, '', '', 24 * 3600 );
	
	if ($tv_email == false) {
		$user = get_user_by( 'id', $id );
//			print_r($user);
		
		//
		if ( ! empty( $user ) ) {
			$tv_email = $user->user_email;
		} else {
			$tv_email = $id;
		}
		
		//
		_eb_get_static_html ( $strCacheFilter, $tv_email, '', 60 );
	}
	
	return $tv_email;
}

function _eb_categories_list_list_v3 ( $taxx = 'category' ) {
	$arr = get_categories( array(
		'taxonomy' => $taxx,
		'hide_empty' => 0,
	) );
//	print_r($arr);
	
	//
//		echo count( $arr ) . "\n";
	
	//
	$str = '';
	
	foreach ( $arr as $v ) {
		$str .= '<option data-parent="' . $v->category_parent . '" value="' . $v->term_id . '">' . $v->name . '</option>';
	}
	
	return $str;
}

function _eb_categories_list_v3 ( $select_name = 't_ant', $taxx = 'category' ) {
	$str = '<option value="0">[ Lựa chọn phân nhóm ]</option>';
	
	$str .= _eb_categories_list_list_v3( $taxx );
	
	$str .= '<option data-show="1" data-href="' . web_link . WP_ADMIN_DIR . '/edit-tags.php?taxonomy=category">[+] Thêm phân nhóm mới</option>';
	
	return '<select name="' . $select_name . '">' . $str . '</select>';
}


$cache_thumbnail_id = array();
//$cache_attachment_image_src = array();
function _eb_get_post_img ( $id, $_size = 'full' ) {
	global $cache_thumbnail_id;
//	global $cache_attachment_image_src;
	
	//
//	if ( isset( $cache_attachment_image_src[ $id . $_size ] ) ) {
//		return $cache_attachment_image_src[ $id . $_size ];
//	}
	
	/*
	if ( $_size == '' ) {
		global $__cf_row;
		
		$_size =  $__cf_row['cf_product_thumbnail_size'];
//		$_size =  $__cf_row['cf_ads_thumbnail_size'];
	}
	*/
	
	/*
	$strCacheFilter = 'post_img/' . $id;
	$a = _eb_get_static_html ( $strCacheFilter );
	if ($a == false) {
		global $__cf_row;
		*/
		
		if ( has_post_thumbnail( $id ) ) {
			
			// lưu ID thumbnail vào biến để sử dụng lại
			if ( ! isset( $cache_thumbnail_id[ $id ] ) ) {
				$cache_thumbnail_id[ $id ] = get_post_thumbnail_id( $id );
			}
			
			// size riêng cho bản EchBay mobile
			if ( $_size == 'ebmobile' ) {
				// nếu server có hỗ trợ Imagick
				if ( class_exists('Imagick') ) {
					return EBE_resize_mobile_table_img( $cache_thumbnail_id[ $id ], $_size );
				}
				// không thì lấy size medium
				else {
					$_size = 'medium';
//					$_size = 'thumbnail';
				}
			}
			
			//
			$a = wp_get_attachment_image_src ( $cache_thumbnail_id[ $id ], $_size );
//			print_r( $a );
//			$a = esc_url( $a[0] );
			$a = $a[0];
		} else {
			$a = _eb_get_post_object( $id, '_eb_product_avatar' );
		}
		
		/*
		
		//
		if ($a != '') {
			_eb_get_static_html ( $strCacheFilter, $a );
		}
	}
	*/
	
	//
//	$cache_attachment_image_src[ $id . $_size ] = $a;
	
	//
	return $a;
}

function EBE_resize_mobile_table_img ( $attachment_id, $_size ) {
	// lấy ảnh full
	$source_file = wp_get_attachment_image_src ( $attachment_id, 'full' );
	$source_file = $source_file[0];
	
	// -> ảnh cho bản mobile
	$file_type = explode( '.', $source_file );
	$file_type = $file_type[ count($file_type) - 1 ];
	
	$new_file = $source_file . '_' . $_size . '.' . $file_type;
	
	// xem file này có tồn tại không -> không thì tạo
	$check_file = ABSPATH . strstr( $new_file, EB_DIR_CONTENT . '/' );
	if ( ! file_exists( $check_file ) ) {
		// Kiểm tra file nguồn
		$source_file = ABSPATH . strstr( $source_file, EB_DIR_CONTENT . '/' );
		if ( ! file_exists( $source_file ) ) {
			return 'source not found!';
		}
		$arr_parent_size = getimagesize( $source_file );
		
		// resize sang ảnh mới
		$image = new Imagick();
		$image->readImage($source_file);
		
		// copy và resize theo chiều rộng
		if ( $arr_parent_size[0] > $arr_parent_size[1] ) {
			$image->resizeImage(160, 0, Imagick::FILTER_CATROM, 1);
		}
		// theo chiều cao
		else {
			$image->resizeImage(0, 160, Imagick::FILTER_CATROM, 1);
		}
		/*
		if ( $arr_parent_size['mime'] == 'image/jpeg' ) {
			$image->setImageFormat( 'jpg' );
			$image->setImageCompression(Imagick::COMPRESSION_JPEG);
		}
		else {
			$image->setImageCompression(Imagick::COMPRESSION_UNDEFINED);
		}
		$image->setImageCompressionQuality( 75 );
		$image->optimizeImageLayers();
		*/
		
		$image->writeImages($check_file, true);
		$image->destroy();
		
		chmod ( $check_file, 0666 );
		
//		return $check_file;
	}
	
	return $new_file;
}


/*
* Chức năng lấy post meta dưới dạng object
*/
function _eb_get_object_post_meta ( $id, $key = eb_post_obj_data, $sing = true, $default_value = array() ) {
	$a = get_post_meta( $id, $key, $sing );
	if ($a == '') {
		$a = $default_value;
	} 
	// thêm ID của mảng để sau còn check lại dữ liệu cho chuẩn
	else {
		$a['id'] = $id;
	}
	
	return $a;
}

/*
* Chức năng dùng để gộp các post metae vào 1 post meta duy nhất -> select sẽ nhanh gọn hơn -> giảm thiểu việc mysql sử dụng quá nhiều ram server
*/
//function _eb_convert_postmeta_to_v2 ( $id, $key, $meta_key ) {
function _eb_convert_postmeta_to_v2 ( $id, $key = '_eb_product_', $meta_key = eb_post_obj_data ) {
	
	//
	$strFilter = " meta_key LIKE '{$key}%' ";
	if ( $key != '_eb_category_' ) {
		$key1 = '_eb_product_';
		$key2 = '_eb_ads_';
		
		$strFilter = " ( meta_key LIKE '{$key1}%' OR meta_key LIKE '{$key2}%' ) ";
	}
	
	// lấy tất cả các post meta thuộc post tương ứng
	$row = _eb_q("SELECT *
	FROM
		`" . wp_postmeta . "`
	WHERE
		post_id = " . $id . "
		AND " . $strFilter . "
	ORDER BY
		meta_id DESC");
//			print_r($old_post_meta);
	
	// lưu vào 1 mảng tạm để xuất dữ liệu ra cho chuẩn
	$arr = array();
	$arr_update = array();
	foreach ( $row as $v ) {
		
		// mảng hiển thị thì phải cắt bỏ hết các ký tự không liên quan
		$arr[ $v->meta_key ] = stripslashes( stripslashes( stripslashes( $v->meta_value ) ) );
		
		// còn mảng dùng để update thì bắt buộc phải lắp thêm vào -> nếu không sẽ gây lỗi chuỗi khi convert sang mảng
		$arr_update[ $v->meta_key ] = addslashes( $arr[ $v->meta_key ] );
		
	}
//	print_r($arr);
//	print_r($arr_update);
	
	// gán thêm ID để đỡ phải lấy lại lần sau
	$arr['id'] = $id;
	$arr_update['id'] = $id;
//	print_r($arr);
//	print_r($arr_update);
	
	// cập nhật theo chức năng mới luôn
	update_post_meta( $id, $meta_key, $arr_update );
	
//	exit();
	
	//
	return $arr;
	
}

/*
* Gán vào một tham số khác để phân định giữa category với post
*/
//$arr_object_cat_meta = array();

function _eb_get_cat_object ( $id, $key, $default_value = '' ) {
	return _eb_get_post_object ( $id, $key, $default_value, eb_cat_obj_data, '_eb_category_' );
	
	/*
	global $arr_object_cat_meta;
	
	//
	if ( ! isset( $arr_object_cat_meta['id'] ) || $arr_object_cat_meta['id'] != $id ) {
		$arr_object_cat_meta = _eb_get_object_post_meta( $id, eb_cat_obj_data );
		
		// nếu không tồn tại mảng tiêu đề -> vẫn là kiểu dữ liệu cũ -> chuyển sang dữ liệu mới luôn và ngay
		if ( ! isset( $arr_object_cat_meta['id'] ) ) {
			$arr_object_cat_meta = _eb_convert_postmeta_to_v2( $id, '_eb_category_', eb_cat_obj_data );
		}
	}
	
	if ( ! isset ( $arr_object_cat_meta[ $key ] ) ) {
		return $default_value;
	}
	
	return $arr_object_cat_meta[ $key ];
	*/
	
}

/*
* Gán vào một tham số khác để phân định giữa ads với post
*/
function _eb_get_ads_object ( $id, $key, $default_value = '' ) {
	return _eb_get_post_object ( $id, $key, $default_value, eb_post_obj_data, '_eb_ads_' );
	
	/*
	global $arr_object_cat_meta;
	
	//
	if ( ! isset( $arr_object_cat_meta['id'] ) || $arr_object_cat_meta['id'] != $id ) {
		$arr_object_cat_meta = _eb_get_object_post_meta( $id );
		
		// nếu không tồn tại mảng tiêu đề -> vẫn là kiểu dữ liệu cũ -> chuyển sang dữ liệu mới luôn và ngay
		if ( ! isset( $arr_object_cat_meta['id'] ) ) {
			$arr_object_cat_meta = _eb_convert_postmeta_to_v2( $id, '_eb_ads_' );
		}
	}
	
	if ( ! isset ( $arr_object_cat_meta[ $key ] ) ) {
		return $default_value;
	}
	
	return $arr_object_cat_meta[ $key ];
	*/
	
}

/*
* Hàm này dùng để lấy object của post, object này bao gồm các thông tin khác tương tự như post meta riêng lẻ. Ví dụ: giá bán, ảnh đại diện...
*/
$arr_object_post_meta = array();

function _eb_get_post_object ( $id, $key, $default_value = '', $meta_key = eb_post_obj_data, $meta_convert = '_eb_product_' ) {
	global $arr_object_post_meta;
	
	//
	$check_id = ( $meta_convert == '_eb_category_' ) ? 'cid' : 'id';
	$check_id .= $id;
	
	//
//	echo $check_id . ' -------<br>' . "\n";
//	echo $meta_convert . ' -------<br>' . "\n";
	
	
	/*
	* Đỡ phải select nhiều -> nhẹ server, host -> hàm sẽ kiểm tra mảng dữ liệu cũ. Nếu trùng ID thì sử dụng luôn, không cần lấy lại nữa.
	* Trường hợp không tìm thấy hoặc ID truyền vào khác ID trước đó -> sẽ tiền hành lấy mới trong CSDL
	*/
//	if ( ! isset( $arr_object_post_meta[$check_id] ) || $arr_object_post_meta[$check_id] != $id ) {
	if ( ! isset( $arr_object_post_meta[$check_id] ) ) {
		// v2
		$sql = _eb_q ("SELECT meta_key, meta_value
		FROM
			`" . wp_postmeta . "`
		WHERE
			post_id = " . $id);
//		print_r($sql); exit();
		
		//
		$arr = array();
//		if ( count($sql) > 0 ) {
			foreach ( $sql as $v ) {
				$arr[ $v->meta_key ] = $v->meta_value;
			}
//		}
		
		
		
		// v1
		/*
		$arr_object_post_meta = _eb_get_object_post_meta( $id, $meta_key );
		
		// nếu không tồn tại mảng ID -> vẫn là kiểu dữ liệu cũ -> chuyển sang dữ liệu mới luôn và ngay
		if ( ! isset( $arr_object_post_meta[check_id] ) ) {
			$arr_object_post_meta = _eb_convert_postmeta_to_v2( $id, $meta_convert, $meta_key );
		}
		*/
		
		// nếu không có kết quả trả về -> trả về dữ liệu mặc định
		if ( ! isset ( $arr[ $key ] ) || $arr[ $key ] == '' ) {
			$arr[ $key ] = $default_value;
			
			// chuyển về dạng số nếu dữ liệu mặc định cũng là số
			if ( is_numeric( $default_value ) ) {
				$arr[ $key ] = (int)$arr[ $key ];
			}
		}
		$arr[ $meta_key ] = '';
		
		//
//		echo $key . ' --------0<br>' . "\n";
//		print_r( $arr );
		
		// gán ID để lần sau còn dùng lại
		$arr_object_post_meta[$check_id] = $arr;
	}
	else {
//		echo $key . ' --------1<br>' . "\n";
		$arr = $arr_object_post_meta[$check_id];
	}
//	echo '=====================<br>' . "\n";
	
	//
//	print_r($arr_object_post_meta);
	
	// có kết quả thì trả về kết quả tìm được
	/*
	if ( isset( $arr[ $key ] ) ) {
		return $arr[ $key ];
	}
	else {
//		echo $key . '<br>' . "\n";
//		print_r( $arr );
		return '';
	}
	*/
	return isset( $arr[ $key ] ) ? $arr[ $key ] : $default_value;
}

function _eb_get_post_meta ( $id, $key, $sing = true, $default_value = '' ) {
	
	// chuyển sang sử dụng phiên bản code mới
//	if ( strstr( $key, '_eb_product_' ) == true ) {
		return _eb_get_post_object( $id, $key, $default_value );
		/*
	}
	else if ( strstr( $key, '_eb_category_' ) == true ) {
		return _eb_get_cat_object( $id, $key, $default_value );
	}
	*/
	
	
	
	// bản code cũ
	$strCacheFilter = 'post_meta/' . $key . $id;
	$a = _eb_get_static_html ( $strCacheFilter );
	if ($a == false) {
		$a = get_post_meta( $id, $key, $sing );
		if ($a == '') {
			$row = _eb_q("SELECT meta_value
			FROM
				`" . wp_postmeta . "`
			WHERE
				post_id = " . $id . "
				AND meta_key = '" . $key . "'
			ORDER BY
				meta_id DESC
			LIMIT 0, 1");
//			print_r($row);
//			echo $id . "\n";
//			echo $key . "\n";
			
			//
			if ( isset($row[0]->meta_value) ) {
				$a = $row[0]->meta_value;
			}
			
			//
			if ($a == '') {
				$a = $default_value;
			}
		}
		
		//
		if ($a != '') {
			_eb_get_static_html ( $strCacheFilter, $a );
		}
	}
	
	//
	return $a;
}


// kiểm tra nếu có file html riêng -> sử dụng html riêng
function _eb_get_html_for_module ( $check_file ) {
	// kiểm tra ở thư mục code riêng
	if ( file_exists( EB_THEME_HTML . $check_file ) ) {
		$f = EB_THEME_HTML . $check_file;
	}
	// nếu không -> kiểm tra ở thư mục dùng chung
	else if ( file_exists( EB_THEME_PLUGIN_INDEX . 'html/' . $check_file ) ) {
		$f = EB_THEME_PLUGIN_INDEX . 'html/' . $check_file;
	}
	
	return file_get_contents( $f, 1 );
}

function _eb_get_private_html ( $f, $f2 = '' ) {
	$check = EB_THEME_HTML . $f;
	$dir = EB_THEME_HTML;
	
	//
	if ( $f2 == '' ) {
		$f2 = $f;
	}
	
	// sử dụng html riêng (nếu có)
	if ( file_exists($check) ) {
		$html = EB_THEME_HTML . $f2;
	}
	// mặc định là html chung
	else {
		$dir = EB_THEME_PLUGIN_INDEX . 'html/';
		
		$html = EB_THEME_PLUGIN_INDEX . 'html/' . $f2;
	}
	
	//
	return array(
		'dir' => $dir,
		'html' => file_get_contents( $html, 1 ),
	);
}



//
function _eb_get_full_category_v2($this_id = 0, $taxx = 'category') {
	global $web_link;
	
	$arr = get_categories( array(
		'taxonomy' => $taxx,
//		'hide_empty' => 0,
		'parent' => $this_id
	) );
//	print_r($arr);
	
	//
	$link_for_taxonomy = '';
	if ( $taxx != 'category' ) {
		$link_for_taxonomy = $taxx . '=blogs&';
	}
	
	//
	$str = '';
	foreach ( $arr as $v ) {
//			print_r($v);
		
		//
//		$c_link = _eb_c_link( $v->term_id, $web_link . '?cat=' . $v->term_id );
//		$c_link = _eb_c_link( $v->term_id, $taxx );
		$c_link = $web_link . '?' . $link_for_taxonomy . 'cat=' . $v->term_id;
		
		//
		$cat_order = 0;
		if ( $this_id == 0 ) {
			$cat_order = (int) _eb_get_cat_object( $v->term_id, '_eb_category_order', 0 );
		}
		
		//
		$str .= ',{id:' . $v->term_id . ',ten:"' . _eb_str_block_fix_content ( $v->name ) . '",lnk:"' . $c_link . '",order:' . $cat_order . ',arr:[' . _eb_get_full_category_v2 ( $v->term_id, $taxx ) . ']}';
	}
	$str = substr ( $str, 1 );
	
	//
	return $str;
}



function WGR_get_arr_taxonomy ( $tax = 'category' ) {
	$arrs = get_categories( array(
		'taxonomy' => $tax,
		'parent' => 0,
	) );
//	print_r( $arrs );
	
	//
	$oders = array();
	$options = array();
	
	//
	foreach ( $arrs as $v ) {
		$oders[ $v->term_id ] = (int) _eb_get_post_meta( $v->term_id, '_eb_category_order', true, 0 );
		$options[$v->term_id] = $v;
	}
	arsort( $oders );
//	print_r( $oders );
	
	//
	foreach ( $oders as $k => $v ) {
		$v = $options[$k];
		print_r($v);
	}
}




function _eb_get_tax_post_options ( $arr_option = array(), $taxo = 'post_options' ) {
//	global $func;
	
	/*
	* arr_option -> bao gồm các giá trị sau:
	* ul_before: nội dung khi bắt đầu UL -> trước LI đầu
	* ul_after: nội dung khi kết thúc UL -> sau LI cuối
	* ul_class: class CSS cho thẻ UL
	*
	* select_before: nội dung khi bắt đầu SELECT -> trước OPTION đầu
	* select_after: nội dung khi kết thúc SELECT -> sau OPTION cuối
	* select_class: class CSS cho thẻ SELECT
	*/
	
	
	$arrs = get_categories( array(
		'taxonomy' => $taxo,
//		'hide_empty' => 0,
		'parent' => 0,
	) );
	
	//
	$oders = array();
	$options = array();
	
	//
	foreach ( $arrs as $v ) {
		$oders[ $v->term_id ] = (int) _eb_get_post_meta( $v->term_id, '_eb_category_order', true, 0 );
		$options[$v->term_id] = $v;
	}
	arsort( $oders );
	
	//
	$javascripts = '';
	$strs = '';
	$selects = '';
	
	//
	foreach ( $oders as $k => $v ) {
		$v = $options[$k];
		
		//
		$arr = get_categories( array(
			'taxonomy' => 'post_options',
//			'hide_empty' => 0,
			'parent' => $v->term_id,
		) );
		
		//
		$oder = array();
		$option = array();
		
		//
		foreach ( $arr as $v2 ) {
			$oder[ $v2->term_id ] = (int) _eb_get_post_meta( $v2->term_id, '_eb_category_order', true, 0 );
			$option[$v2->term_id] = $v2;
		}
		arsort( $oder );
		
		//
		$javascript = '';
		$str = '';
		$select = '';
		foreach ( $oder as $k2 => $v2 ) {
			$v2 = $option[$k2];
			
			$op_link = _eb_c_link( $v2->term_id );
			
			$str .= '<li><a data-parent="' . $v->term_id . '" data-id="' . $v2->term_id . '" href="' . $op_link . '">' . $v2->name . '</a></li>';
			
			$select .= '<option value="' . $v2->term_id . '">' . $v2->name . '</option>';
			
			$javascript .= ',{id:"' . $v2->term_id . '",ten:"' . $v2->name . '",url:"' . $op_link . '"}';
		}
		
		//
		if ( $str != '' ) {
			
			//
			$strs .= '
			<li>
				<div class="search-advanced-padding click-add-id-to-sa">
					<div class="search-advanced-name"><a data-parent="0" data-id="' . $v->term_id . '" href="' . _eb_c_link( $v->term_id ) . '" title="' . $v->name . '">' . $v->name . ' <i class="fa fa-caret-down"></i></a></div>
					<ul class="sub-menu">
						' . $str . '
					</ul>
				</div>
			</li>';
			
			//
			$selects .= '
			<select class="change-add-id-to-sa">
				<option value="0">' . $v->name . '</option>
				' . $select . '
			</select>';
			
			//
			$javascripts .= ',{id:"' . $v->term_id . '",ten:"' . $v->name . '",arr:[' . substr( $javascript, 1 ) . ']}';
		}
	}
	
	// tổng hợp dữ liệu trả về
	if ( !isset( $arr_option['ul_before'] ) ) {
		$arr_option['ul_before'] = '';
	}
	if ( !isset( $arr_option['ul_after'] ) ) {
		$arr_option['ul_after'] = '';
	}
	if ( !isset( $arr_option['ul_class'] ) ) {
		$arr_option['ul_class'] = '';
	}
	
	if ( !isset( $arr_option['select_before'] ) ) {
		$arr_option['select_before'] = '';
	}
	if ( !isset( $arr_option['select_after'] ) ) {
		$arr_option['select_after'] = '';
	}
	if ( !isset( $arr_option['select_class'] ) ) {
		$arr_option['select_class'] = '';
	}
	
	
	// js
//	if ( $type == 'js' ) {
//	}
	// html
//	else {
		return '
		<ul class="widget-search-advanced ul-eb-postoptions ' . $arr_option['ul_class'] . '">' . $arr_option['ul_before'] . $strs . $arr_option['ul_after'] . '</ul>
		<div class="select-eb-postoptions ' . $arr_option['select_class'] . '2 d-none">' . $arr_option['select_before'] . $selects . $arr_option['select_after'] . '</div>
		<script type="text/javascript">var js_eb_postoptions=[' . substr( $javascripts, 1 ) . '];</script>';
//	}
}




/*
* chức năng thay thế cho hàm thread-remove-endbegin trên javascript
*/
function _eb_thread_remove_endbegin ( $arr, $begin = 0, $end = 0, $tag = '</li>' ) {
	$arr = explode( $tag, $arr );
	$str = '';
//	$str = array();
	foreach ( $arr as $k => $v ) {
		if ( $k >= $begin && $k <= $end ) {
			$v = trim( $v );
			if ( $v != '' ) {
				$str .=  '<!-- ' . $k . ' -->' . $v . $tag;
			}
//			$str[] =  '<!-- ' . $k . ' -->' . $v;
		}
	}
	
	return $str;
//	return implode( $tag, $str );
}



function _eb_selected ( $k, $v ) {
	return $k == $v ? ' selected="selected"' : '';
}




function _eb_parse_args ( $arr, $default ) {
	// sử dụng hàm của wp
	return wp_parse_args ( $arr, $default );
}

function _eb_widget_parse_args ( $arr, $default ) {
	// tìm ở mảng default -> nếu mảng chính không có thì gán thêm vào
	/*
	foreach ( $default as $k => $v ) {
		if ( ! isset( $arr[$k] ) ) {
			$arr[$k] = $v;
		}
	}
	*/
	
	// bỏ HTML cho mảng chính
	foreach ( $arr as $k => $v ) {
		$arr [$k] = strip_tags ( $v );
	}
	
	return $arr;
}




function _eb_update_option ( $name, $value ) {
	global $wpdb;
	
	_eb_q("UPDATE `" . $wpdb->options . "`
	SET
		option_value = '" . $value . "'
	WHERE
		option_name = '" . $name . "'");
}
function _eb_set_option ( $name, $value ) {
	_eb_update_option( $name, $value );
}




function EBE_create_in_con_voi_table ( $table, $pri_key, $arr ) {
	
	// mảng các cột mẫu
//	print_r($arr);
//	$arr = array_reverse( $arr );
//	print_r($arr);
	
	// các cột hiện tại trong database
	$arr_check = _eb_q( "SHOW TABLES LIKE '" . $table . "'" );
	
	// nếu chưa có bảng hóa đơn
	if ( count( $arr_check ) == 0 ) {
		
		//  -> thêm bảng -> thêm cột khóa chính
		$sql = trim('
		CREATE TABLE `' . $table . '` (
			`' . $pri_key . '` ' . strtoupper( $arr[ $pri_key ]['type'] ) . ' NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		');
		_eb_q($sql);
		
		// tạo khóa chính
		$sql = trim('ALTER TABLE `' . $table . '` ADD PRIMARY KEY(`' . $pri_key . '`)');
		_eb_q($sql);
		
		// sửa lại cột
		$sql = trim('ALTER TABLE `' . $table . '` CHANGE `' . $pri_key . '` `' . $pri_key . '` ' . strtoupper( $arr[ $pri_key ]['type'] ) . ' NOT NULL AUTO_INCREMENT' );
		_eb_q($sql);
		
		// lấy lại danh sách cột sau khi tạo mới
		$arr_check = _eb_q( "SHOW TABLES LIKE '" . $table . "'" );
		
	}
	//print_r( $arr_check );
	
	// cấu trúc bảng
	$strsql = _eb_q( "DESCRIBE `" . $table . "`" );
//	print_r( $strsql );
	
	// chạy lệnh để kiểm tra cột có hay chưa
	$arr_current = array();
	foreach ( $strsql as $v2 ) {
//		print_r( $v2 );
		$v2 = (array)$v2;
//		print_r( $v2 );
		
		$arr_current[ $v2['Field'] ] = 1;
	}
//	print_r( $arr_current );
	
	//
	$first_cloumn = $pri_key;
	foreach ( $arr as $k => $v ) {
		if ( ! isset( $arr_current[$k] ) ) {
			$v['field'] = $k;
			
			//
			$sql = 'ALTER TABLE `' . $table . '` ADD `' . $k . '` ' . strtoupper( $v['type'] ) . ' ' . ( $v['null'] == 'no' ? 'NOT NULL' : 'NULL' ) . ' AFTER `' . $first_cloumn . '`;';
//			echo $sql . "\n";
			_eb_q( $sql );
			
			// UNIQUE
			if ( $v['key'] == 'uni' ) {
				$sql = 'ALTER TABLE `' . $table . '` ADD UNIQUE(`' .$k. '`)';
//				echo $sql . "\n";
				_eb_q( $sql );
			}
			// INDEX
			else if ( $v['key'] == 'mul' ) {
				$sql = 'ALTER TABLE `' . $table . '` ADD INDEX(`' .$k. '`);';
//				echo $sql . "\n";
				_eb_q( $sql );
			}
//			echo $sql . "\n";
//			_eb_q( $sql );
		}
		
		// thay đổi cột tiếp theo
		$first_cloumn = $k;
	}
}



function EBE_tao_bang_hoa_don_cho_echbay_wp () {
	
	//
	EBE_create_in_con_voi_table( 'eb_in_con_voi', 'order_id', array(
		'order_id' => array(
			'type' => 'bigint(20)',
			'null' => 'no',
			'key' => 'pri',
			'default' => '',
			'extra' => 'auto_increment',
		),
		'order_sku' => array(
			'type' => 'varchar(191)',
			'null' => 'no',
			'key' => 'mul',
			'default' => '',
			'extra' => '',
		),
		'order_products' => array(
			'type' => 'longtext',
			'null' => 'no',
			'key' => '',
			'default' => '',
			'extra' => '',
		),
		'order_customer' => array(
			'type' => 'longtext',
			'null' => 'no',
			'key' => '',
			'default' => '',
			'extra' => '',
		),
		'order_agent' => array(
			'type' => 'longtext',
			'null' => 'no',
			'key' => '',
			'default' => '',
			'extra' => '',
		),
		'order_ip' => array(
			'type' => 'varchar(191)',
			'null' => 'no',
			'key' => 'mul',
			'default' => '',
			'extra' => '',
		),
		'order_time' => array(
			'type' => 'bigint(20)',
			'null' => 'no',
			'key' => '',
			'default' => '',
			'extra' => '',
		),
		'order_status' => array(
			'type' => 'bigint(20)',
			'null' => 'no',
			'key' => 'mul',
			'default' => '',
			'extra' => '',
		),
		'tv_id' => array(
			'type' => 'bigint(20)',
			'null' => 'no',
			'key' => 'mul',
			'default' => '',
			'extra' => '',
		),
	) );
	
	EBE_create_in_con_voi_table( 'eb_details_in_con_voi', 'dorder_id', array(
		'dorder_id' => array(
			'type' => 'bigint(20)',
			'null' => 'no',
			'key' => 'pri',
			'default' => '',
			'extra' => 'auto_increment',
		),
		'dorder_key' => array(
			'type' => 'varchar(191)',
			'null' => 'no',
			'key' => 'mul',
			'default' => '',
			'extra' => '',
		),
		'dorder_name' => array(
			'type' => 'longtext',
			'null' => 'no',
			'key' => '',
			'default' => '',
			'extra' => '',
		),
		'order_id' => array(
			'type' => 'bigint(20)',
			'null' => 'no',
			'key' => 'mul',
			'default' => '',
			'extra' => '',
		),
	) );
	
}



function EBE_part_page($Page, $TotalPage, $strLinkPager) {
	$show_page = 8;
	$str_page = '';
	if ($Page <= $show_page) {
		if ($TotalPage <= $show_page) {
			for ($i = 1; $i <= $TotalPage; $i++) {
				if ($i == $Page) {
					$str_page .= '<strong>' .$i. '</strong>';
				} else {
					$str_page .= '<a rel="nofollow" href="' .$strLinkPager . $i. '">' .$i. '</a>';
				}
			}
		} else {
			for ($i = 1; $i <= $show_page; $i++) {
				if ($i == $Page) {
					$str_page .= '<strong>' .$i. '</strong>';
				} else {
					$str_page .= '<a rel="nofollow" href="' .$strLinkPager . $i. '">' .$i. '</a>';
				}
			}
			$str_page .= ' ... <a rel="nofollow" href="' .$strLinkPager .$i. '">&gt;</a>';
		}
	} else {
		$chiadoi = $show_page / 2;
		$i = $Page - ($chiadoi + 1);
		$str_page = '<a rel="nofollow" href="' .$strLinkPager .$i. '">&lt;&lt;</a> <a rel="nofollow" href="' .$strLinkPager. '1">1</a> ... ';
		$i++;
		for ($i; $i < $Page; $i++) {
			$str_page .= '<a rel="nofollow" href="' .$strLinkPager . $i. '">' .$i. '</a>';
		}
		$str_page .= '<strong>' .$i. '</strong>';
		$i++;
		$_Page = $Page + $chiadoi;
		if ($_Page > $TotalPage) {
			$_Page = $TotalPage;
		}
		for ($i; $i < $_Page; $i++) {
			$str_page .= '<a rel="nofollow" href="' .$strLinkPager . $i. '">' .$i. '</a>';
		}
		$str_page .= ' ... <a rel="nofollow" href="' .$strLinkPager . $TotalPage. '">' .$TotalPage. '</a> <a href="' .$strLinkPager .$i. '" rel="nofollow">&gt;&gt;</a>';
	}
	
	return $str_page;
}


function EBE_part_page_ajax($Page, $TotalPage, $strLinkPager, $return) {
	$show_page = 8;
	$str_page = '';
	if ($Page <= $show_page) {
		if ($TotalPage <= $show_page) {
			for ($i = 1; $i <= $TotalPage; $i++) {
				if ($i == $Page) {
					$str_page .= ' <a title="Trang ' .$i. '" href="javascript:;"><span class="bold">[ ' .$i. ' ]</span></a>';
				} else {
					$str_page .= ' <a title="Trang ' .$i. '" onclick="ajaxl(\'' .$strLinkPager . $i. '\',\''.$return.'\',1)" href="javascript:;">' .$i. '</a>';
				}
			}
		} else {
			for ($i = 1; $i <= $show_page; $i++) {
				if ($i == $Page) {
					$str_page .= ' <a title="Trang ' .$i. '" href="javascript:;"><span class="bold">[ ' .$i. ' ]</span></a>';
				} else {
					$str_page .= ' <a title="Trang ' .$i. '" onclick="ajaxl(\'' .$strLinkPager . $i. '\',\''.$return.'\',1)" href="javascript:;">' .$i. '</a>';
				}
			}
			$str_page .= ' ... <a title="Tiếp" onclick="ajaxl(\'' .$strLinkPager . $i. '\',\''.$return.'\',1)" href="javascript:;">&gt;&gt;</a>';
		}
	} else {
		$chiadoi = $show_page / 2;
		$i = $Page - ($chiadoi + 1);
		$str_page = '<a title="Trước" onclick="ajaxl(\'' .$strLinkPager . $i. '\',\''.$return.'\',1)" href="javascript:;">&lt;&lt;</a> <a title="Trang 1" onclick="ajaxl(\'' .$strLinkPager. '1\',\''.$return.'\',1)" href="javascript:;">1</a> ... ';
		$i++;
		for ($i; $i < $Page; $i++) {
			$str_page .= ' <a title="Trang ' .$i. '" onclick="ajaxl(\'' .$strLinkPager . $i. '\',\''.$return.'\',1)" href="javascript:;">' .$i. '</a>';
		}
		$str_page .= ' <a title="Trang ' .$i. '" href="javascript:;"><span class="bold">[ ' .$i. ' ]</span></a>';
		$i++;
		$_Page = $Page + $chiadoi;
		if ($_Page > $TotalPage) {
			$_Page = $TotalPage;
		}
		for ($i; $i <= $_Page; $i++) {
			$str_page .= ' <a title="Trang ' .$i. '" onclick="ajaxl(\'' .$strLinkPager . $i. '\',\''.$return.'\',1)" href="javascript:;">' .$i. '</a>';
		}
		$str_page .= ' ... <a title="Tiếp" onclick="ajaxl(\'' .$strLinkPager . $i. '\',\''.$return.'\',1)" href="javascript:;">&gt;&gt;</a>';
	}
	return '<div class="public-part-page"><span class="bold">Trang: </span> ' .$str_page. '</div>';
}





function EBE_check_list_post_null ( $str = '' ) {
	if ( $str == '' ) {
		global $__cf_row;
		
		$__cf_row ["cf_blog_public"] = 0;
		
		$str = '
		<li class="no-set-width-this-li" style="width:100% !important;padding:0;margin:0;">
			<div class="text-center big bold" style="padding:90px 20px;">Chưa có dữ liệu</div>
		</li>';
	}
	
	return $str;
}



// ebp -> ech bay post
function EBE_print_product_img_css_class ( $arr, $in = 'Header' ) {
	echo '<!-- EchBay Product Image in ' . $in . ' -->
<style type="text/css">' . str_replace( 'http://' . $_SERVER['HTTP_HOST'] . '/', './', str_replace( 'https://' . $_SERVER['HTTP_HOST'] . '/', './', implode( "\n", $arr ) ) ) . '</style>';
}



// https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
function EBE_set_header ( $status = 200 ) {
	
	// sử dụng hàm set header của wp
	return status_header ( $status );
	
	
	
	// hoặc tự mình làm
	$pcol = ( isset($_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
//	echo $pcol;
	
	// Chuyển header sang 200
	if ( $status == 200 ) {
		header( $pcol . ' 200 OK' );
	}
	else if ( $status == 404 ) {
		header( $pcol . ' 404 Not Found' );
	}
	else if ( $status == 401 ) {
		header( $pcol . ' 401 Unauthorized' );
	}
}



// chuyển tên file php, html... thành css
function WGR_convert_fiename_to_css ( $f ) {
	$f = explode('.', $f);
	return $f[0] . '.css';
}

// lấy css theo plugin
function EBE_get_css_for_config_design ( $f, $type = '.php' ) {
//	return EB_THEME_PLUGIN_INDEX . 'themes/css/' . str_replace( $type, '.css', $f );
	return EB_THEME_PLUGIN_INDEX . 'themes/css/' . WGR_convert_fiename_to_css( $f );
}

// lấy css theo theme
function EBE_get_css_for_theme_design ( $f, $dir = EB_THEME_URL, $type = '.php' ) {
//	return $dir . 'css/' . str_replace( $type, '.css', $f );
//	return $dir . 'ui/' . str_replace( $type, '.css', $f );
	return $dir . 'ui/' . WGR_convert_fiename_to_css( $f );
}

// kiểm tra file template xem nằm ở đâu thì nhúng css tương ứng ở đó
function WGR_check_add_add_css_themes_or_plugin ( $f ) {
//	echo EB_CHILD_THEME_URL . 'ui/' . WGR_convert_fiename_to_css( $f ) . '<br>' . "\n";
//	echo EB_THEME_URL . 'ui/' . WGR_convert_fiename_to_css( $f ) . '<br>' . "\n";
	
	// ưu tiên hàng của theme trước
	if ( defined('EB_CHILD_THEME_URL') && file_exists( EB_CHILD_THEME_URL . 'ui/' . WGR_convert_fiename_to_css( $f ) ) ) {
		return EBE_get_css_for_theme_design ( $f, EB_CHILD_THEME_URL );
	}
	else if ( file_exists( EB_THEME_URL . 'ui/' . WGR_convert_fiename_to_css( $f ) ) ) {
		return EBE_get_css_for_theme_design ( $f );
	}
	
	// còn lại sẽ là của plugin
	return EBE_get_css_for_config_design ( $f );
}


// load các module của web theo phương thức chung
function WGR_load_module_name_css (
	// phương thức lấy
	$module_name,
	// add css vào body hoặc head (0)
	$css_body = 1
) {
	global $__cf_row_default;
	global $__cf_row;
	global $arr_for_add_css;
	
	$arr = array();
	
	for ( $i = 1; $i < 20; $i++ ) {
		$j = 'cf_' . $module_name . $i . '_include_file';
		
		if ( ! isset( $__cf_row_default[ $j ] ) ) {
			break;
		}
//		echo $j . ' -> ' . $__cf_row[ $j ] . '<br>' . "\n";
		
		//
		if ( $__cf_row[ $j ] != '' ) {
			// nếu là widget -> chỉ nhúng, và nhúng theo kiểu khác
			if ( $__cf_row[ $j ] == $module_name . '_widget.php' ) {
				$arr[] = EB_THEME_PLUGIN_INDEX . $__cf_row[ $j ];
			}
			else {
				// ưu tiên hàng của theme trước
				if ( defined('EB_CHILD_THEME_URL')
				&& file_exists( EB_CHILD_THEME_URL . 'ui/' . $__cf_row[ $j ] ) ) {
					$arr[] = EB_CHILD_THEME_URL . 'ui/' . $__cf_row[ $j ];
					
					$arr_for_add_css[ EBE_get_css_for_theme_design ( $__cf_row[ $j ], EB_CHILD_THEME_URL ) ] = $css_body;
				}
				else if ( file_exists( EB_THEME_URL . 'ui/' . $__cf_row[ $j ] ) ) {
					$arr[] = EB_THEME_URL . 'ui/' . $__cf_row[ $j ];
					
					$arr_for_add_css[ EBE_get_css_for_theme_design ( $__cf_row[ $j ] ) ] = $css_body;
				}
				// còn lại sẽ là của plugin
				else {
					$arr[] = EB_THEME_PLUGIN_INDEX . 'themes/' . $module_name . '/' . $__cf_row[ $j ];
					
					$arr_for_add_css[ EBE_get_css_for_config_design ( $__cf_row[ $j ] ) ] = $css_body;
				}
			}
		}
	}
//	print_r($arr_for_add_css);
//	print_r($arr);
	
	return $arr;
}


// Tạo comment theo chuẩn chung
function EBE_insert_comment ( $data =  array() ) {
	global $client_ip;
	
	// dữ liệu mặc định
	$arr = array(
		// mặc định thì cho vào thành contact
		'comment_post_ID' => eb_contact_id_comments,
		'comment_author' => '',
		'comment_author_email' => mtv_email,
		'comment_author_url' => '',
		'comment_content' => '',
		'comment_type' => '',
		'comment_parent' => 0,
		'user_id' => mtv_id,
		'comment_author_IP' => $client_ip,
		'comment_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
		'comment_date' => date( 'Y-m-d H:i:s', date_time ),
		'comment_approved' => 1,
	);
	
	// dữ liệu phủ định
	foreach ( $data as $k => $v ) {
		if ( isset( $arr[$k] ) ) {
			$arr[$k] = $v;
		}
	}
//	print_r($arr);
	
	wp_insert_comment($arr);
	
}


/*
* Các đoạn HTML thường dùng
*/
function EBE_get_html_logo () {
	global $__cf_row;
	
	// v2 -> custom height
	return '<div><a href="./" class="web-logo d-block" style="background-image:url(' . $__cf_row['cf_logo'] . ');">&nbsp;</a></div>';
	
	// v1 -> auto set height
//	return '<div><a data-size="' . $__cf_row['cf_size_logo'] . '" href="./" class="web-logo ti-le-global d-block" style="background-image:url(' . $__cf_row['cf_logo'] . ');">&nbsp;</a></div>';
}

function EBE_get_html_search () {
	global $current_search_key;
	
	return '
<div class="div-search-margin">
	<div class="div-search">
		<form role="search" method="get" action="' . web_link . '">
			<input type="search" placeholder="' . EBE_get_lang('searchp') . '" value="' . $current_search_key . '" name="s" aria-required="true" required>
			<input type="hidden" name="post_type" value="post" />
			<button type="submit" class="cur default-bg"><i class="fa fa-search"></i><span class="d-none">' . EBE_get_lang('search') . '</span></button>
		</form>
	</div>
	<div id="oiSearchAjax"></div>
</div>';
}

function EBE_get_html_cart ( $icon_only = 0 ) {
	$a = EBE_get_lang('cart');
	
	if ( $icon_only == 1 ) {
		return '<div class="btn-to-cart cf"><a title="' . $a . '" href="' . web_link . 'cart" rel="nofollow"><i class="fa fa-shopping-cart"></i> <em class="show_count_cart d-none">0</em></a></div>';
	}
	else {
		return '<div class="btn-to-cart cf"><a title="' . $a . '" href="' . web_link . 'cart" rel="nofollow"><i class="fa fa-shopping-cart"></i> ' . $a . ' <em class="show_count_cart d-none">0</em></a></div>';
	}
}

function EBE_get_html_profile () {
	return '<div class="oi_member_func">.</div>';
}

function EBE_get_html_address () {
	global $__cf_row;
	
	return '
	<div class="footer-address">
		<div class="footer-address-company bold">' . $__cf_row['cf_ten_cty'] . '</div>
		<div class="footer-address-info l19">
			<div class="footer-address-address"><strong>Địa chỉ:</strong> <i class="fa fa-map-marker"></i> ' . nl2br( $__cf_row['cf_diachi'] ) . '</div>
			<div class="footer-address-phone">
				<div class="footer-address-hotline"><strong>Hotline:</strong> <i class="fa fa-phone"></i> <span class="phone-numbers-inline">' . $__cf_row['cf_call_hotline'] . '</span></div>
				<div class="footer-address-cell"><strong>Điện thoại:</strong> <span class="phone-numbers-inline">' . $__cf_row['cf_call_dienthoai'] . '</span></div>
			</div>
			<div class="footer-address-email"><strong>Email:</strong> <i class="fa fa-envelope-o"></i> <a href="mailto:' . $__cf_row['cf_email'] . '" rel="nofollow" target="_blank">' . $__cf_row['cf_email'] . '</a></div>
		</div>
	</div>';
}

function WGR_get_bigbanner () {
	global $str_big_banner;
	
	//
	if ( $str_big_banner == '' ) {
		return '<!-- HTML for big banner-->';
	}
	
	//
//	return '<div class="oi_big_banner">' . $str_big_banner . '</div>';
	return $str_big_banner;
}

function WGR_get_footer_social () {
	return '
	<ul class="footer-social text-center cf">
		<li class="footer-social-fb"><a href="javascript:;" class="ahref-to-facebook" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>
		<li class="footer-social-tw"><a href="javascript:;" class="each-to-twitter-page" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>
		<li class="footer-social-yt"><a href="javascript:;" class="each-to-youtube-chanel" target="_blank" rel="nofollow"><i class="fa fa-youtube"></i></a></li>
		<li class="footer-social-gg"><a href="javascript:;" class="ahref-to-gooplus" target="_blank" rel="nofollow"><i class="fa fa-google-plus"></i></a></li>
	</ul>';
}

function WGR_get_fb_like_box () {
	return '
	<div class="each-to-facebook">
		<div class="fb-page" data-small-header="false" data-hide-cover="false" data-show-facepile="true"></div>
	</div>';
}


