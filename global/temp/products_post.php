<?php



$post_id = isset( $_GET['post_id'] ) ? (int)$_GET['post_id'] : 0;
$by_post_type = isset( $_GET['by_post_type'] ) ? trim( $_GET['by_post_type'] ) : 'post';
$arr_for_update_post = array();




if ( $post_id > 0 && $type != '' ) {
	
	// thay đổi STT sản phẩm
	if ( isset( $_GET['stt'] ) ) {
		$trv_stt = (int) $_GET['stt'];
		
		if ( $type == 'auto' ) {
			$sql = _eb_q ( "SELECT menu_order
			FROM
				`" . $wpdb->posts . "`
			WHERE
				post_type = '" . $by_post_type . "'
				AND ID != " . $post_id . "
			ORDER BY
				menu_order DESC
			LIMIT 0, 1" );
//			print_r($sql); exit();
			$sql = $sql[0];
			foreach ( $sql as $v ) {
				$trv_stt = $v;
			}
			$trv_stt ++;
		}
		else if ( $type == 'up' ) {
			$trv_stt ++;
		}
		else if ( $type == 'down' ) {
			$trv_stt --;
			if ($trv_stt < 0) {
				$trv_stt = 0;
			}
		}
//		echo $trv_stt . '<br>' . "\n";
		
		//
		$arr_for_update_post['menu_order'] = $trv_stt;
	}
	// thay đổi trạng thái của sản phẩm
	else if ( isset( $_GET['toggle_status'] ) ) {
		$current_status = (int) $_GET['toggle_status'];
		
		// Đang mở -> ẩn
		if ( $current_status == 1 ) {
			$new_status = 'draft';
		}
		// Mặc định là hiển thị
		else {
			$new_status = 'publish';
		}
		
		//
		$arr_for_update_post['post_status'] = $new_status;
	}
	
	//
	if ( count( $arr_for_update_post ) > 0 ) {
		$arr_for_update_post['ID'] = $post_id;
//		print_r( $arr_for_update_post );
		
		// chạy lệnh cập nhật
		$update_id = wp_update_post( $arr_for_update_post, true );
		
		// nếu có lỗi thì trả về lỗi
		if ( is_wp_error($update_id) ) {
			$errors = $update_id->get_error_messages();
			foreach ($errors as $error) {
				echo $error . '<br>' . "\n";
			}
		}
	}
	
}


