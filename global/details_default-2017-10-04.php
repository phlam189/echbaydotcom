<?php





//
$custom_product_flex_css = '';
$custom_blog_node_flex_css = '';


//
include EB_THEME_PLUGIN_INDEX . 'global/post.php';

//
/*
if ( $__cf_row['cf_set_news_version'] == 0 ) {
	$web_og_type = 'product';
}
else {
	*/
	$web_og_type = 'article';
//}




// nếu bài viết được đánh dấu để set noindex -> set thuộc tính noindex
if ( _eb_get_post_object( $pid, '_eb_product_noindex', 0 ) == 1 ) {
	$__cf_row ["cf_blog_public"] = 0;
}




//
$trv_giaban = _eb_float_only( _eb_get_post_object( $pid, '_eb_product_oldprice', 0 ) );
$trv_giamoi = _eb_float_only( _eb_get_post_object( $pid, '_eb_product_price', 0 ) );
/*
if ( $trv_giamoi == 0 ) {
	$trv_giamoi = _eb_float_only( _eb_get_post_object( $pid, '_price', 0 ) );
}
*/








/*
* bổ sung 1 số kiểu dữ liệu mới (nếu chưa có)
*/

// chuyển kiểu dữ liệu sang array để kiểm tra cho dễ
/*
$arr_post_for_check_mysql = (array) $__post;

// nếu chưa có cột giá mới -> add thêm cột giá mới
if ( ! isset( $arr_post_for_check_mysql['_eb_product_price'] ) ) {
	$eb_install_sql = "ALTER TABLE `" . $wpdb->posts . "`
	ADD
		`_eb_product_price` INT(11) NOT NULL
	AFTER
		`menu_order`";
//	echo $eb_install_sql . '<br>' . "\n";
	_eb_q($eb_install_sql);
}
// nếu có giá mới, mà cột giá mới chưa có update -> update cột giá
else if ( $arr_post_for_check_mysql['_eb_product_price'] != $trv_giamoi ) {
	_eb_q("UPDATE `" . $wpdb->posts . "`
	SET
		_eb_product_price = " . $trv_giamoi . "
	WHERE
		ID = " . $__post->ID);
}
*/
/*
else {
	echo 'no update<br>';
}
*/


//
$arr_product_js = array (
	'tieude' => '\'' . _eb_str_block_fix_content ( $__post->post_title ) . '\'',
	'gia' => $trv_giaban,
	'gm' => $trv_giamoi,
);
$product_js = '';
foreach ( $arr_product_js as $k => $v ) {
	$product_js .= ',' . $k . ':' . $v;
}






// Nếu config không tạo menu -> không load sidebar
if ( $__cf_row['cf_post_column_style'] == '' ) {
	$id_for_get_sidebar = '';
} else {
	$id_for_get_sidebar = 'post_sidebar';
}




// blog
if ( $__post->post_type == EB_BLOG_POST_TYPE ) {
	
//	$link_for_fb_comment = web_link . '?post_type=' . EB_BLOG_POST_TYPE . '&p=' . $pid;
	
	// bài báo
//	$web_og_type = 'article';
	
	$post_categories = get_the_terms( $pid, EB_BLOG_POST_LINK );
	
	//
	if ( $__cf_row['cf_on_off_amp_blog'] == 1 ) {
		$global_dymanic_meta .= '<link rel="amphtml" href="' . $url_og_url . '?amp" />';
	}
	
	// Nếu config không tạo menu -> không load sidebar
	if ( $__cf_row['cf_blog_column_style'] == '' ) {
		$id_for_get_sidebar = '';
	} else {
		$id_for_get_sidebar = 'blog_details_sidebar';
	}
}
// post, page
else {
	$post_categories = wp_get_post_categories( $pid );
	
	if ( $__post->post_type == 'post' && $__cf_row['cf_on_off_amp_product'] == 1 ) {
		$global_dymanic_meta .= '<link rel="amphtml" href="' . $url_og_url . '?amp" />';
	}
}
//if ( mtv_id == 1 ) print_r( $post_categories );


//
$post_format = get_post_format( $pid );
echo $post_format . '<br>' . "\n";


//
$cats = array();
$cats_child = array();
$ant_link = '';
$ant_ten = '';
$ant_id = 0;
$bnt_id = 0;
$other_option_list = '';

//
if ( isset( $post_categories[0] ) ) {
	
	// parent
	foreach($post_categories as $c){
		$cat = get_term( $c );
	//	print_r( $cat );
	//	$cat = get_category( $c );
	//	print_r( $cat );
		
		// parent
		if ( $cat->parent == 0 ) {
			$cats[] = $cat;
			
			//
			$ant_link = _eb_c_link($cat->term_id);
//			echo $ant_link . '<br>';
			
			//
			$schema_BreadcrumbList[$ant_link] = _eb_create_breadcrumb( $ant_link, $cat->name );
		}
		// child
		else {
			if ( $bnt_id == 0 ) {
				$bnt_id = $cat->term_id;
			}
			
			$cats_child[] = $cat;
		}
	}
	
	// child
	foreach($cats_child as $cat){
		$ant_link = _eb_c_link($cat->term_id);
//		echo $ant_link . '<br>';
		
		//
		$schema_BreadcrumbList[$ant_link] = _eb_create_breadcrumb( $ant_link, $cat->name );
	}
	
}
//if ( mtv_id == 1 ) print_r( $cats );

//
if ( isset( $cats[0] ) ) {
	$ant_ten = $cats[0]->name;
	$ant_id = $cats[0]->term_id;
	$cid = $ant_id;
	
	// tìm nhóm cha (nếu có)
	_eb_create_html_breadcrumb( $cats[0] );
} else if ( $bnt_id > 0 ) {
	$ant_id = $bnt_id;
	$cid = $bnt_id;
}




// Chỉ lấy banner riêng khi chế độ global không được kích hoạt
if ( $__cf_row['cf_post_big_banner'] == 0 ) {
	$str_big_banner = '<!-- Big banner current set not load in post details -->';
}
else if ( $__cf_row['cf_global_big_banner'] == 0 ) {
	// Mặc định chỉ lấy cho phần post
	if ( $cid > 0 ) {
		$str_big_banner = EBE_get_big_banner( 5, array(
			'category__in' => '',
		) );
	}
	// còn đây là page -> chưa làm
}




// Hết hoặc Còn hàng
$trv_mua = _eb_get_post_object( $pid, '_eb_product_buyer', 0 );
$trv_max_mua = _eb_get_post_object( $pid, '_eb_product_quantity', 0 );
$str_tinh_trang = '<span class="greencolor">Sẵn hàng</span>';

$con_hay_het = 1;

//
$trv_trangthai = _eb_get_post_object( $pid, '_eb_product_status', 0 );
$schema_availability = 'http://schema.org/InStock';
if ( $trv_trangthai == 7 || $trv_mua > $trv_max_mua ) {
	$schema_availability = 'http://schema.org/SoldOut';
	
	$str_tinh_trang = '<span class="redcolor">Hết hàng</span>';
	
	$con_hay_het = 0;
	
	// thêm class ẩn nút mua hàng
	$css_m_css .= ' details-hideif-hethang';
}



//
//$arr_product_color = '';



//
$post_modified = strtotime( $__post->post_modified );
$schema_priceValidUntil = $post_modified + 24 * 3600 * 365;





//
$trv_rating_value = _eb_get_post_object( $pid, '_eb_product_rating_value', 0 );
if ( $trv_rating_value == '' ) {
	$trv_rating_value = 0;
}
//echo $trv_rating_value . "\n";

$trv_rating_count = _eb_get_post_object( $pid, '_eb_product_rating_count', 0 );
if ( $trv_rating_count == '' ) {
	$trv_rating_count = 0;
}
//echo $trv_rating_count . "\n";

// Tạo rate ngẫu nhiên
if ($trv_rating_value < 6 || $trv_rating_count == 0) {
	$trv_rating_value = rand ( 6, 10 );
	$trv_rating_count = rand ( 1, 5 );
	
	// dùng update_post_meta thay cho add_post_meta
	update_post_meta( $pid, '_eb_product_rating_value', $trv_rating_value );
	update_post_meta( $pid, '_eb_product_rating_count', $trv_rating_count );
	
	//
	/*
	$arr_object_post_meta['_eb_product_rating_value'] = $trv_rating_value;
	$arr_object_post_meta['_eb_product_rating_count'] = $trv_rating_count;
	
	update_post_meta( $pid, eb_post_obj_data, $arr_object_post_meta );
	*/
}

$rating_value_img = $trv_rating_value / 2;
if (strlen ( $rating_value_img ) == 1) {
	$rating_value_img = $rating_value_img . '.0';
}
	
	
	
	
// dữ liệu có cấu trúc
$structured_data_detail = '';
$structured_data_post_title = str_replace( '"', '&quot;', $__post->post_title );

if ( $trv_giamoi > 0 ) {
	
	$structured_data_detail = '
<script type="application/ld+json">
{
	"@context": "http:\/\/schema.org\/",
	"@type": "Product",
	"name": "' . $structured_data_post_title . '",
	"image": "' . str_replace( '/', '\/', $trv_img ) . '",
	"description": "' . $__cf_row ['cf_description'] . '",
//	"mpn": "' .$pid. '"
	"url": "' . str_replace( '/', '\/', $url_og_url ) . '",
	
	//
	/*
	"aggregateRating": {
		"@type": "AggregateRating",
		"ratingValue": "' .$rating_value_img. '",
		"reviewCount": "' .$trv_rating_count. '"
	},
	*/
	"offers": {
		"@type": "Offer",
		"priceCurrency": "VND",
		"price": "' .$trv_giamoi. '",
		"priceValidUntil": "' .date( 'Y-m-d', $schema_priceValidUntil ). '",
		/*
		"seller": {
			"@type": "Organization",
			"name": "Executive Objects"
		},
		*/
//		"itemCondition": "http:\/\/schema.org\/UsedCondition",
		"availability": "' .$schema_availability. '"
	},
	
	"brand": {
		"@type": "Thing",
		"name": "' . str_replace( '"', '&quot;', $ant_ten ) . '"
	},
	
	//
	"productID": "' .$pid. '"
}
</script>';
	
}
else {
	
	//
	$blog_img_logo = $__cf_row['cf_logo'];
	if ( strstr( $blog_img_logo, '//' ) == false ) {
		if ( substr( $blog_img_logo, 0, 1 ) == '/' ) {
			$blog_img_logo = substr( $blog_img_logo, 1 );
		}
		
		//
		$blog_img_logo = web_link . $blog_img_logo;
	}
	
	//
	$structured_data_detail = '
<script type="application/ld+json">
{
	"@context": "http:\/\/schema.org",
	"@type": "BlogPosting",
	"publisher": {
		"@type": "Organization",
		"name": "' . str_replace( '"', '&quot;', web_name ) . '",
		"logo": {
			"@type": "ImageObject",
			"url": "' . str_replace( '/', '\/', $blog_img_logo ) . '"
		}
	},
	"mainEntityOfPage": "' . str_replace( '/', '\/', $url_og_url ) . '",
	"headline": "' . $structured_data_post_title . '",
	"datePublished": "' . $__post->post_date . '",
	"dateModified": "' . $__post->post_modified . '",
	"author": {
		"@type": "Person",
		"name": "itvn9online"
	},
	"description": "' . $__cf_row ['cf_description'] . '",
	"image": {
		"@type": "ImageObject",
		"width": "400",
		"height": "400",
		"url": "' . str_replace( '/', '\/', $trv_img ) . '"
	}
}
</script>';
	
}

//
if ( $structured_data_detail != '' ) {
	$structured_data_detail = preg_replace( "/\t/", "", trim( $structured_data_detail ) );
	
	$dynamic_meta .= $structured_data_detail;
}






//
//$group_go_to[] = ' <li>' . $__post->post_title . '</li>';
//echo $group_go_to;
//print_r($group_go_to);

//
$schema_BreadcrumbList[$url_og_url] = _eb_create_breadcrumb( $url_og_url, $__post->post_title );







// tự làm amp cho khách hàng
if ( isset($_GET['amp']) ) {
	if ( ( $__post->post_type == EB_BLOG_POST_TYPE && $__cf_row['cf_on_off_amp_blog'] == 1 )
	|| ( $__post->post_type == 'post' && $__cf_row['cf_on_off_amp_product'] == 1 ) ) {
		include EB_THEME_PLUGIN_INDEX . 'amp.php';
	}
}







/*
* cho những thứ không cần real vào cache
*/
/*
$strCacheFilter = 'details/' . $pid;
$main_content = _eb_get_static_html ( $strCacheFilter );
if ($main_content == false) {
	*/
	
	
	
	
//
//$thu_muc_for_html = EB_THEME_HTML;



//
$pt = 0;
if ($trv_giaban > $trv_giamoi) {
	$pt = 100 - ( int ) ($trv_giamoi * 100 / $trv_giaban);
}




// Mặc định là cho vào sản phẩm
$html_v2_file = 'thread_details';
//$html_file = 'thread_details.html';

$arr_list_tag = array();
$blog_list_medium = '';
$product_list_medium = '';
$other_post_right = '<!-- Chi tiết Sản phẩm -->';
$other_post_2right = '<!-- Chi tiết Sản phẩm (2) -->';
$other_post_3right = '<!-- Chi tiết Sản phẩm (3) -->';
$str_for_details_sidebar = '';

// với blog -> sử dụng giao diện khác post
if ( $__post->post_type == EB_BLOG_POST_TYPE ) {
	
	// tag of blog
	$arr_list_tag = wp_get_object_terms( $pid, 'blog_tag' );
	
	
	// bài xem nhiều
	$args = array(
		'post_type' => EB_BLOG_POST_TYPE,
		'offset' => 0,
		'tax_query' => array(
			array(
				'taxonomy' => EB_BLOG_POST_LINK,
				'terms' => $ant_id,
			)
		),
	);
	
	
	//
	$html_v2_file = 'blog_details';
//	$html_file = $html_v2_file . '.html';
	
	
	// nếu không tồn tại file thiết kế riêng -> kiểm tra file HTML mẫu
//	if ( ! file_exists( EB_THEME_HTML . $html_file ) ) {
		if ( $__cf_row['cf_blog_column_style'] != '' ) {
//			$html_v2_file = $html_v2_file . '_' . $__cf_row['cf_blog_column_style'];
			
			$custom_product_flex_css = EBE_get_html_file_addon( $html_v2_file, $__cf_row['cf_blog_column_style'] );
		}
//	}
//	echo $__cf_row['cf_blog_column_style'] . '<br>' . "\n";
//	echo $html_v2_file . '<br>' . "\n";
	
	
	// kiểm tra nếu có file html riêng -> sử dụng html riêng
//	$check_html_rieng = _eb_get_private_html( $html_file, 'blog_node.html' );
	
//	$thu_muc_for_html = $check_html_rieng['dir'];
//	$blog_html_small_node = $check_html_rieng['html'];
	
	//
//	$blog_list_medium = _eb_load_post( 10, $args, _eb_get_html_for_module( 'blog_node.html' ) );
	
	//
//	$blog_list_medium = _eb_load_post( 10, $args, EBE_get_page_template( 'blog_node' ) );
//	$custom_blog_node_flex_css = EBE_get_html_file_addon( 'blog_node', $__cf_row['cf_blog_node_html'] );
	
	//
	if ( $__cf_row['cf_num_details_blog_list'] > 0 ) {
		$blog_list_medium = _eb_load_post( $__cf_row['cf_num_details_blog_list'], $args, EBE_get_page_template( 'blogs_node' ) );
	}
	$custom_blog_node_flex_css = EBE_get_html_file_addon( 'blogs_node', $__cf_row['cf_blog_node_html'] );
	
	//
	$str_for_details_sidebar = _eb_echbay_get_sidebar( 'blog_content_details_sidebar' );
	
}
else if ( $__post->post_type == 'page' ) {
	$html_v2_file = 'page';
//	$html_file = $html_v2_file . '.html';
	
	// nếu không tồn tại file thiết kế riêng -> kiểm tra file HTML mẫu
//	if ( ! file_exists( EB_THEME_HTML . $html_file ) ) {
		if ( $__cf_row['cf_page_column_style'] != '' ) {
//			$html_v2_file = $html_v2_file . '_' . $__cf_row['cf_page_column_style'];
			
			$custom_product_flex_css = EBE_get_html_file_addon( $html_v2_file, $__cf_row['cf_page_column_style'] );
		}
//	}
//	echo $__cf_row['cf_page_column_style'] . '<br>' . "\n";
//	echo $html_v2_file . '<br>' . "\n";
	
	// kiểm tra nếu có file html riêng -> sử dụng html riêng
//	$check_html_rieng = _eb_get_private_html( $html_file, 'blog_node.html' );
//	$thu_muc_for_html = $check_html_rieng['dir'];
}
else {
	
	// set trạng thái trang là sản phẩm
	$web_og_type = 'product';
	
	//
//	$check_html_rieng = _eb_get_private_html( 'blog_details.html', 'blog_node.html' );
	
//	$product_list_medium = _eb_load_post( 10, array(), $check_html_rieng['html'] );
	
	
	
	// lấy màu sắc sản phẩm
	/*
	$sql = _eb_q("SELECT *
	FROM
		" . wp_postmeta . "
	WHERE
		meta_key = '_eb_category_status'
		AND meta_value = 7");
//	print_r($sql);
	
	//
	$arr_post_options = wp_get_object_terms( $pid, 'post_options' );
//	print_r($arr_post_options);
	
	foreach ( $sql as $v ) {
//		print_r($v);
		
		foreach ( $arr_post_options as $v2 ) {
//			print_r($v2);
			
			//
			if ( $v->post_id == $v2->parent ) {
				$arr_product_color .= ',{ten:"' . $v2->name . '",val:"' . _eb_get_cat_object( $v2->term_id, '_eb_category_title', '#fff' ) . '"}';
			}
		}
	}
	*/
	
	
	
	
	// Tạo menu cho post option
	$arr_post_options = wp_get_object_terms( $pid, 'post_options' );
//	if ( mtv_id == 1 ) print_r($arr_post_options);
	foreach ( $arr_post_options as $v ) {
		if ( $v->parent > 0 ) {
//			$parent_name = get_term_by( 'id', $v->parent, $v->taxonomy );
			$parent_name = WGR_get_taxonomy_parent( $v );
//			if ( mtv_id == 1 ) print_r( $parent_name );
			
			//
			$other_option_list .= '
<tr>
	<td><div>' . $parent_name->name . '</div></td>
	<td><div><a href="' . _eb_c_link( $v->term_id, $v->taxonomy ) . '" target="_blank">' . $v->name . '</a></div></td>
</tr>';
		}
	}
	
	
	
	
	// tag of post
	$arr_list_tag = get_the_tags( $pid );
	
	
	//
	$limit_other_post = $__cf_row['cf_num_details_list'];
	
	/*
	* other post
	*/
	
	// Thử kiểm tra xem trong này có nhóm nào được set là nhóm chính không
	$post_primary_categories = array();
//		print_r( $post_categories );
	foreach ( $post_categories as $v ) {
		if ( _eb_get_post_meta( $v, '_eb_category_primary', true, 0 ) > 0 ) {
			$post_primary_categories[] = $v;
		}
	}
//		print_r( $post_primary_categories );
	
	// nếu không tìm được -> lấy tất
	if ( count( $post_primary_categories ) == 0 ) {
		$post_primary_categories = $post_categories;
	}
//	print_r( $post_primary_categories );
	
	
	//
	if ( $limit_other_post > 0 ) {
		
		//
		$prev_post = get_previous_post();
	//	print_r($prev_post);
		if ( isset($prev_post->ID) ) {
			$limit_other_post--;
			
			$other_post_right .= _eb_load_post( 1, array(
				'post__in' => array(
					$prev_post->ID
				),
			) );
		}
		
		//
		$next_post = get_next_post();
	//	print_r($next_post);
		if ( isset($next_post->ID) ) {
			$limit_other_post--;
			
			$other_post_right .= _eb_load_post( 1, array(
				'post__in' => array(
					$next_post->ID
				),
			) );
		}
		
		// nếu không có giới hạn bài viết cho phần other post -> lấy mặc định 10 bài
//		if ( ! isset($limit_other_post) ) {
//			$limit_other_post = $__cf_row['cf_num_details_list'];
//		}
		
		
		//
		$other_post_right .= _eb_load_post( $limit_other_post, array(
//			'category__in' => wp_get_post_categories( $__post->ID ),
			'category__in' => $post_primary_categories,
			'post__not_in' => array(
				$__post->ID
			),
		) );
		
	}
	
	
	
	// lấy thêm loạt bài tiếp theo, 1 số giao diện sẽ sử dụng
	if ( $__cf_row['cf_num_details2_list'] > 0 ) {
		$other_post_2right .= _eb_load_post( $__cf_row['cf_num_details2_list'], array(
			'category__in' => $post_primary_categories,
			'post__not_in' => array(
				$__post->ID
			),
		) );
	}
	
	
	
	// lấy thêm loạt bài tiếp theo, 1 số giao diện sẽ sử dụng
	if ( $__cf_row['cf_num_details3_list'] > 0 ) {
		$other_post_3right .= _eb_load_post( $__cf_row['cf_num_details3_list'], array(
			'category__in' => $post_primary_categories,
			'post__not_in' => array(
				$__post->ID
			),
		) );
	}
	
	
	
	
	// xem định dạng bài viết có được hỗ trợ theo theme không
//	$post_format = get_post_format( $pid );
//	echo $post_format . '<br>' . "\n";
	if ( $post_format != '' ) {
//		$check_new_format = 'thread_details-' . $post_format . '.html';
		$check_new_format = $html_v2_file . '-' . $post_format . '.html';
//		echo $check_new_format . '<br>' . "\n";
		
		//
		if ( file_exists( EB_THEME_HTML . $check_new_format ) ) {
//			$html_file = $check_new_format;
//			echo $html_file . '<br>' . "\n";
//			$html_v2_file = 'thread_details-' . $post_format;
			$html_v2_file = $html_v2_file . '-' . $post_format;
//			echo $html_v2_file . '<br>' . "\n";
			
//			$thu_muc_for_html = EB_THEME_HTML;
		}
	}
//	else if ( $__cf_row['cf_post_column_style'] != '' ) {
//	else {
//		$html_v2_file = EBE_get_html_file_addon( $html_v2_file, $__cf_row['cf_post_column_style'] );
//		$html_v2_file = $html_v2_file . '_' . $__cf_row['cf_post_column_style'];
//	}
	
	
	//
	if ( $__cf_row['cf_post_column_style'] != '' ) {
		$custom_product_flex_css = EBE_get_html_file_addon( $html_v2_file, $__cf_row['cf_post_column_style'] );
	}
	
	
	
	//
	$str_for_details_sidebar = _eb_echbay_get_sidebar( 'post_content_sidebar' );
	
	
	
}

//
$str_tags = '';
//print_r( $arr_list_tag );
if ( ! empty ( $arr_list_tag ) ) {
	foreach ( $arr_list_tag as $v ) {
		$str_tags .= '<a href="' . get_tag_link( $v->term_id ) . '">' . $v->name . '</a> ';
	}
}



// the_content -> chỉ chạy khi gọi the_post -> dùng hàm the_content để tạo nội dung theo chuẩn wp
the_post();

// dùng ob để lấy nội dung đã được echo thay vì echo trực tiếp
ob_start();

the_content();
$trv_noidung = ob_get_contents();

//ob_clean();
//ob_end_flush();
ob_end_clean();

//echo $trv_noidung;





//
$trv_masanpham = _eb_get_post_object( $pid, '_eb_product_sku' );
if ( $trv_masanpham == '' ) {
	$trv_masanpham = $pid;
}

$product_gallery = _eb_get_post_object( $pid, '_eb_product_gallery' );
$product_gallery = str_replace( ' src=', ' data-src=', $product_gallery );
$product_gallery = str_replace( ' data-src=', ' src="' . EB_URL_OF_PLUGIN . 'images-global/_blank.png" data-src=', $product_gallery );

$product_list_color = _eb_get_post_object( $pid, '_eb_product_list_color' );
$product_list_color = str_replace( ' src=', ' data-src=', $product_list_color );
$product_list_color = str_replace( ' data-src=', ' src="' . EB_URL_OF_PLUGIN . 'images-global/_blank.png" data-src=', $product_list_color );

//
$trv_h1_tieude = str_replace( '<', '&lt;', str_replace( '>', '&gt;', $__post->post_title ) );



// tạo mảng để khởi tạo nội dung
$arr_main_content = array(
	'tmp.trv_id' => $pid,
	'tmp.trv_masanpham' => $trv_masanpham,
//	'tmp.trv_masanpham' => $trv_masanpham == '' ? '#' . $pid : $trv_masanpham,
	'tmp.link_for_fb_comment' => $link_for_fb_comment,
	
	'tmp.trv_tieude' => $trv_h1_tieude,
	'tmp.trv_h1_tieude' => ( $__cf_row['cf_set_link_for_h1'] == 1 ) ? '<a href="' . $url_og_url . '">' . $trv_h1_tieude . '</a>' : $trv_h1_tieude,
	
	'tmp.trv_goithieu' => $__post->post_excerpt,
	'tmp.trv_noidung' => $trv_noidung,
	'tmp.trv_dieukien' => _eb_get_post_object( $pid, '_eb_product_dieukien' ),
	'tmp.trv_tomtat' => _eb_get_post_object( $pid, '_eb_product_noibat' ),
	
	'tmp.trv_img' => $trv_img,
	
	'tmp.ant_link' => _eb_c_link($ant_id),
	'tmp.ant_ten' => $ant_ten,
	
	'tmp.trv_galerry' => $product_gallery,
	'tmp.trv_list_color' => $product_list_color,
	
	'tmp.trv_mua' => (int) $trv_mua,
	'tmp.trv_max_mua' => (int) $trv_max_mua,
	'tmp.str_tinh_trang' => $str_tinh_trang,
	
	'tmp.blog_list_medium' => $blog_list_medium,
	'tmp.product_list_medium' => $product_list_medium,
	
	'tmp.other_post_right' => $other_post_right,
	'tmp.other_post_2right' => $other_post_2right,
	'tmp.other_post_3right' => $other_post_3right,
	
	'tmp.other_option_list' => $other_option_list,
	
	'tmp.rating_value_img' => $rating_value_img,
//	'tmp.str_tags' => substr( $str_tags, 1 ),
	'tmp.str_tags' => $str_tags,
	
	'tmp.bl_ngaygui' => date( 'd/m/Y H:i T', $post_modified ),
	
	'tmp.pt' => $pt,
	'tmp.trv_giaban' => EBE_add_ebe_currency_class( $trv_giaban, 1 ),
	'tmp.trv_giamoi' => EBE_add_ebe_currency_class( $trv_giamoi ),
	'tmp.trv_num_giamoi' => $trv_giamoi,
	'tmp.trv_tietkiem' => ( $trv_giamoi > 0 ) ? EBE_add_ebe_currency_class( $trv_giaban - $trv_giamoi ) : '',
	
	'tmp.cf_product_details_size' => $__cf_row['cf_product_details_size'],
	'tmp.cf_diachi' => nl2br( $__cf_row['cf_diachi'] ),
	
	'tmp.p_link' => $url_og_url,
	
	// chèn class tính chiều rộng cho khung
//	'tmp.custom_blog_css' => $__cf_row['cf_blog_class_style'],
	'tmp.cf_blogd_class_style' => $__cf_row['cf_blogd_class_style'],
	'tmp.cf_blog_num_line' => $__cf_row['cf_blog_num_line'],
	'tmp.custom_blog_node_flex_css' => $custom_blog_node_flex_css,
	
	'tmp.custom_page_css' => $__cf_row['cf_page_class_style'],
	
	'tmp.cf_post_class_style' => $__cf_row['cf_post_class_style'],
	
	'tmp.custom_product_flex_css' => $custom_product_flex_css,
	
	'tmp.str_for_details_sidebar' => $str_for_details_sidebar,
	
	// phom mua ngay
	'tmp.clone-show-quick-cart' => ( $__cf_row['cf_details_show_quick_cart'] ) == 1 ? '<div class="clone-show-quick-cart"></div>' : '',
	
	// tìm và tạo sidebar luôn
//	'tmp.str_sidebar' => _eb_echbay_sidebar( $id_for_get_sidebar ),
	
	// lang
	'tmp.lang_btn_muangay' => EBE_get_lang('mungay'),
	'tmp.lang_chitiet_sanpham' => EBE_get_lang('chitietsp'),
	'tmp.lang_sanpham_tuongtu' => EBE_get_lang('tuongtu'),
);


// gọi đến function riêng của từng site
if ( function_exists('eb_details_for_current_domain') ) {
	$arr_main_new_content = eb_details_for_current_domain();
	
	// -> chạy vòng lặp, ghi đè lên mảng cũ
	foreach ( $arr_main_new_content as $k => $v ) {
		$arr_main_content[$k] = $v;
	}
}


// tạo nội dung - v1
//$main_content = EBE_str_template( $html_file, $arr_main_content, $thu_muc_for_html );

// v2
// với sản phẩm -> có thể tạo nhiều design khác nhau
$load_config_temp = $__cf_row['cf_threaddetails_include_file'];
if ( $__post->post_type == 'post' && $load_config_temp != '' ) {
	$main_content = WGR_check_and_load_tmp_theme( $load_config_temp, 'threaddetails' );
}
// mặc định thì kiểm tra theo theme và plugin
else {
	$main_content = EBE_get_page_template( $html_v2_file );
}
$main_content = EBE_html_template( $main_content, $arr_main_content );




// product size
$product_size = _eb_get_post_object( $pid, '_eb_product_size' );
if ( $product_size != '' ) {
	if ( substr( $product_size, 0, 1 ) == ',' ) {
		$product_size = substr( $product_size, 1 );
	}
	$product_size = str_replace( '"', '\"', $product_size );
}




// If comments are open or we have at least one comment, load up the comment template.
// load comment bằng ajax -> vì theme mình viết toàn có cache
$eb_site_comment_open = 0;
if ( comments_open() || get_comments_number() ) {
//	comments_template();
	
	$eb_site_comment_open = 1;
}



//
$_eb_product_ngayhethan = _eb_get_post_object( $pid, '_eb_product_ngayhethan' );
$_eb_product_giohethan = _eb_get_post_object( $pid, '_eb_product_giohethan' );
$trv_ngayhethan = 0;
if ( $_eb_product_ngayhethan != '' ) {
	if ( $_eb_product_giohethan == '' ) {
		$_eb_product_giohethan = '23:59';
	}
	
	$trv_ngayhethan = strtotime( $_eb_product_ngayhethan . ' ' . $_eb_product_giohethan );
}




// -> thêm đoạn JS dùng để xác định xem khách đang ở đâu trên web
$main_content .= '<script type="text/javascript">
var switch_taxonomy="' . $__post->post_type . '",
	pid=' . $pid . ',
	eb_site_comment_open=' . $eb_site_comment_open . ',
	con_hay_het=' . $con_hay_het . ',
	product_js={' . substr ( $product_js, 1 ) . '},
	arr_product_size="' . $product_size . '",
	arr_product_color=[],
	product_color_name="' . _eb_str_block_fix_content ( _eb_get_post_object( $pid, '_eb_product_color' ) ) . '",
	_eb_product_chinhhang="' . _eb_get_post_object( $pid, '_eb_product_chinhhang', 0 ) . '",
	_eb_product_video_url="' . _eb_get_post_object( $pid, '_eb_product_video_url' ) . '",
	_eb_product_ngayhethan="' . $_eb_product_ngayhethan . '",
	_eb_product_giohethan="' . $_eb_product_giohethan . '",
	cf_details_excerpt="' . $__cf_row['cf_details_excerpt'] . '",
	trv_ngayhethan=' . $trv_ngayhethan . ';
</script>';
//	arr_product_color=[' . substr( $arr_product_color, 1 ) . '],






//
/*
_eb_get_static_html ( $strCacheFilter, $main_content );

} // end cache
*/





// một số file gắn riêng cho post
/*
if ( $post->post_type == 'post' ) {
	// gọi file js dùng chung trước
	$arr_for_add_js[] = 'javascript/details_wp.js';
	
	// sau đó gọi file js riêng của từng domain
	$arr_for_add_js[] = 'details.js';
}
*/





// thêm link sửa bài cho admin
/*
$admin_edit = get_edit_post_link( $__post->ID );
if ( $admin_edit != '' ) {
	$admin_edit = '<a title="Edit" href="' . $admin_edit . '"><i class="fa fa-edit"></i></a>';
}
*/

$admin_edit = '';
//if ( current_user_can('editor') || current_user_can('administrator') ) {
if ( current_user_can('delete_posts') ) {
	$admin_edit = '<a title="Edit" href="' . web_link . WP_ADMIN_DIR . '/post.php?post=' . $pid . '&action=edit" class="fa fa-edit"></a>';
}
$main_content = str_replace ( '{tmp.admin_edit}', $admin_edit, $main_content );




// thêm thanh công cụ mua trên mobile
//$main_content .= EBE_html_template( EBE_get_page_template( 'details_mobilemua' ) );
$main_content .= EBE_get_page_template( 'details_mobilemua' );



//
//print_r( $_COOKIE );




// loại bỏ chức năng bỏ qua sản phẩm đã lấy, để custom code còn hoạt động được
$___eb_post__not_in = '';





