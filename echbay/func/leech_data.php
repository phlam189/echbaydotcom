<?php


//print_r( $_POST ); exit();



//
$post_type = trim( $_POST['post_tai'] );
//_eb_alert($post_type);

$trv_source = trim( $_POST['t_source'] );
$trv_sku = md5( $trv_source );
$trv_tieude = trim( stripslashes( $_POST['t_tieude'] ) );
$trv_seo = trim( $_POST['t_seo'] );
$trv_tags = str_replace( '-', ' ', $trv_seo );
$trv_img = trim( $_POST['t_img'] );
$youtube_url = trim( $_POST['t_youtube_url'] );

$post_excerpt = trim( stripslashes( $_POST['t_goithieu'] ) );
$trv_noidung = trim( stripslashes( $_POST['t_noidung'] ) );

// size
$trv_giaban = _eb_number_only( $_POST['t_giacu'] );
$trv_giamoi = _eb_number_only( $_POST['t_giamoi'] );

//
$ant_id = _eb_number_only( $_POST['t_ant'] );
//_eb_alert($ant_id);



//
$m = '<span class=bluecolor>UPDATE</span>';

$trv_id = trim( $_POST['t_id'] );
$trv_id = (int)$trv_id;
//echo $trv_id . '<br>';

// dùng để kiểm tra ID bài viết đa tồn tại, mà tồn tại dưới dạng bản nháp
$check_post_exist = array();

//
$import_id = 0;

// tìm theo name
if ( $trv_id == 0 ) {
//	_eb_alert('Không xác định được ID dữ liệu');
	
	// tìm xem có bài POST nào như vậy không
	$sql = _eb_load_post_obj( 1, array(
		'post_type' => $post_type,
		'orderby' => 'ID',
		'meta_key' => '_eb_product_leech_sku',
		'meta_value' => $trv_sku,
		/*
		'meta_query' => array(
			'key' => '_eb_product_leech_sku',
			'value' => $trv_sku,
			array(
				'key' => '_eb_product_leech_sku',
				'value' => $trv_sku,
			),
		),
		*/
	) );
//	print_r($sql);
	
	if ( isset( $sql->post->ID ) ) {
		$import_id = $sql->post->ID;
		
		//
		$check_post_exist = _eb_q("SELECT *
		FROM
			" . $wpdb->posts . "
		WHERE
			ID = '" . $import_id . "'");
	}
	// tìm theo SEO
	else {
		/*
		$import_id = $wpdb->get_var("SELECT ID
		FROM
			" . $wpdb->posts . "
		WHERE
			post_name = '" . $trv_seo . "'");
		*/
		
		//
		$check_post_exist = _eb_q("SELECT *
		FROM
			`" . $wpdb->posts . "`
		WHERE
			post_name = '" . $trv_seo . "'");
	}
//	echo $import_id . '<br>'; exit();
	
	//
//	$trv_id = (int)$import_id;
}
// nếu có ID -> tìm theo ID
else {
	/*
	$import_id = $wpdb->get_var("SELECT ID
	FROM
		" . $wpdb->posts . "
	WHERE
		ID = '" . $trv_id . "'");
	*/
	
	//
	$check_post_exist = _eb_q("SELECT *
	FROM
		" . $wpdb->posts . "
	WHERE
		ID = '" . $trv_id . "'");
}

//
//print_r($check_post_exist);
if ( isset( $check_post_exist[0] ) ) {
	$check_post_exist = $check_post_exist[0];
	
	//
	if ( isset( $check_post_exist->ID ) ) {
		$import_id = $check_post_exist->ID;
	}
}
//echo $import_id . '<br>' . "\r\n";
//print_r($check_post_exist);

//
$import_id = (int)$import_id;

//
//exit();

//
//_eb_alert($import_id);





// insert
if ( $import_id == 0 ) {
	$arr = array(
		'import_id' => $trv_id,
		
		'post_title' => $trv_tieude,
		'post_type' => $post_type,
		'post_parent' => 0,
		'post_author' => mtv_id,
		'post_status' => 'publish',
		'post_name' => $trv_seo,
	);
	
	//
	$import_id = wp_insert_post ( $arr, true );
	if ( is_wp_error($import_id) ) {
		$errors = $import_id->get_error_messages();
		foreach ($errors as $error) {
			echo $error . '<br>' . "\n";
		}
		
		//
		_eb_alert('Lỗi khi import sản phẩm');
	}
	
	//
	$m = '<span class=greencolor>INSERT</span>';
}
// nếu có rồi -> kiểm tra trạng thái
else {
	
	// nếu bài viết đã tồn tại và đang được public -> bỏ qua
	if ( isset( $check_post_exist->post_status ) && $check_post_exist->post_status == 'publish' ) {
		
		// cập nhật lại url -> hiện tại đang sai
		if ( $check_post_exist->post_name != $trv_seo ) {
			$post_id = wp_update_post( array(
				'ID' => $check_post_exist->ID,
				'post_name' => $trv_seo,
				'post_excerpt' => $post_excerpt,
			), true );
			
			if ( is_wp_error($post_id) ) {
			//	print_r( $post_id ) . '<br>';
				
				$errors = $post_id->get_error_messages();
				foreach ($errors as $error) {
					echo $error . '<br>' . "\n";
				}
				
				//
				_eb_alert('Lỗi khi cập nhật sản phẩm đã tồn tại');
			}
		}
		
		
		//
		$m = '<span class=redcolor>EXIST</span>';
		
		//
		$p_link = get_permalink( $import_id );
		//$p_link = web_link . '?p=' . $trv_id;
		
		//
		if ( $p_link == '' ) {
			_eb_alert( 'Permalink not found' );
		}
		
		die('<script type="text/javascript">
		parent.ket_thuc_lay_du_lieu(' .$import_id. ', "' .$m. '", "' . $p_link . '");
		</script>');
	}
}

//
$import_id = (int)$import_id;
//echo $import_id . '<br>';
//_eb_alert($import_id);

//
if ( $import_id == 0 ) {
	_eb_alert('ID bài viết không hợp lệ');
}
if ( $trv_id > 0 && $import_id != $trv_id ) {
	_eb_alert('ID bài viết không hợp lệ (' . $import_id . ' != ' . $trv_id . ')');
}

// gán lại ID bài viết -> có trường hợp ở trên không tìm theo ID
$trv_id = $import_id;




// update
$arr_meta_box = array(
	'_eb_product_status' => 0,
//	'_eb_product_color' => '',
//	'_eb_product_sku' => trim($_POST['t_masanpham']),
//	'_eb_product_oldprice' => $trv_giaban,
//	'_eb_product_price' => $trv_giamoi,
//	'_eb_product_buyer' => '',
//	'_eb_product_quantity' => '',
	'_eb_product_leech_source' => $trv_source,
	'_eb_product_leech_sku' => $trv_sku,
//	'_eb_product_avatar' => $trv_img,
//	'_eb_product_video_url' => $youtube_url,
//	'_eb_product_gallery' => trim( stripslashes($_POST['t_gallery']) ),
);

// để tiết kiệm metabox -> đoạn nào có dữ liệu mới vào dùng
if ( $trv_img != '' ) $arr_meta_box['_eb_product_avatar'] = $trv_img;

if ( $trv_giaban > 0 ) $arr_meta_box['_eb_product_oldprice'] = $trv_giaban;

if ( $trv_giamoi > 0 ) $arr_meta_box['_eb_product_price'] = $trv_giamoi;

$t_masanpham = trim($_POST['t_masanpham']);
if ( $t_masanpham != '' ) $arr_meta_box['_eb_product_sku'] = $t_masanpham;

$t_gallery = trim( stripslashes($_POST['t_gallery']) );
if ( $t_gallery != '' ) $arr_meta_box['_eb_product_gallery'] = $t_gallery;

$t_dieukien = trim( stripslashes($_POST['t_dieukien']) );
if ( $t_dieukien != '' ) $arr_meta_box['_eb_product_noibat'] = $t_dieukien;

$t_size_list = trim( stripslashes($_POST['t_size_list']) );
if ( $t_size_list != '' ) $arr_meta_box['_eb_product_size'] = $t_size_list;

if ( $youtube_url != '' ) $arr_meta_box['_eb_product_video_url'] = $youtube_url;

//
$arr = array(
	'ID' => $trv_id,
	/*
	'post_parent' => 0,
	'post_author' => mtv_id,
	*/
	'post_status' => 'publish',
	'post_type' => $post_type,
	'post_title' => $trv_tieude,
	'post_name' => $trv_seo,
	
	'post_category' => array( $ant_id ),
	'meta_input' => $arr_meta_box,
);

if ( $post_excerpt != '' ) $arr['post_excerpt'] = $post_excerpt;
if ( $trv_noidung != '' ) $arr['post_content'] = $trv_noidung;

//print_r($arr);

// không tạo nhóm nếu không phải là post
if ( $post_type != 'post' ) {
//if ( $post_type == EB_BLOG_POST_TYPE ) {
	$arr['post_category'] = array();
	
	// tạo nhóm theo cách khác
	$update_id = wp_set_post_terms( $trv_id, array( $ant_id ), EB_BLOG_POST_LINK );
//	_eb_alert('aaaa');
	
	//
//	print_r($update_id); exit();
}

//
$post_id = wp_update_post( $arr, true );
//echo $post_id . '<br>';
if ( is_wp_error($post_id) ) {
//	print_r( $post_id ) . '<br>';
	
	$errors = $post_id->get_error_messages();
	foreach ($errors as $error) {
		echo $error . '<br>' . "\n";
	}
	
	//
	_eb_alert('Lỗi khi cập nhật sản phẩm');
}

//
/*
foreach ( $arr_meta_box as $k => $v ) {
	update_post_meta( $trv_id, $k, $v );
}
*/

//
//echo $trv_id . "\n";




//
$p_link = get_permalink( $trv_id );
//$p_link = web_link . '?p=' . $trv_id;

//
if ( $p_link == '' ) {
	_eb_alert( 'Permalink not found' );
}




die('<script type="text/javascript">
parent.ket_thuc_lay_du_lieu(' .$trv_id. ', "' .$m. '", "' . $p_link . '");
</script>');




