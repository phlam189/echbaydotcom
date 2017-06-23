<?php



/*
* Widget blog ngẫu nhiên
*/
class ___echbay_widget_get_menu extends WP_Widget {
	function __construct() {
		parent::__construct ( 'get_echbay_menu', 'zEchBay Menu', array (
				'description' => 'Lấy menu tương ứng để gán vào widget' 
		) );
	}
	
	function form($instance) {
		global $arr_to_add_menu;
		
		$default = array (
			'title' => 'EchBay Menu',
			'menu' => '',
			'width' => '',
			'custom_style' => ''
		);
		$instance = wp_parse_args ( ( array ) $instance, $default );
		foreach ( $instance as $k => $v ) {
			$$k = esc_attr ( $v );
		}
		
		
		
		//
		_eb_widget_echo_widget_input_title( $this->get_field_name ( 'title' ), $title );
		
		
		//
		__eb_widget_load_select( array( '' => '[ Chọn menu hiển thị ]' ) + $arr_to_add_menu, $this->get_field_name ( 'menu' ), $menu );
		
		
		//
		_eb_menu_width_form_for_widget( $this->get_field_name ( 'width' ), $width );
		
		
		//
		_eb_widget_echo_widget_input_title( $this->get_field_name ( 'custom_style' ), $custom_style, 'Custom CSS:' );
		
	}
	
	function update($new_instance, $old_instance) {
		$instance = _eb_widget_parse_args ( $new_instance, $old_instance );
		return $instance;
	}
	
	function widget($args, $instance) {
//		global $func;
		
		extract ( $args );
		
//		$title = apply_filters ( 'widget_title', $instance ['title'] );
		$title = isset( $instance ['title'] ) ? $instance ['title'] : '';
		$menu = isset( $instance ['menu'] ) ? $instance ['menu'] : '';
		$width = isset( $instance ['width'] ) ? $instance ['width'] : '';
		$custom_style = isset( $instance ['custom_style'] ) ? $instance ['custom_style'] : '';
		
		
		//
		_eb_echo_widget_name( $this->name, $before_widget );
		
		//
		echo '<div class="lf top-footer-css ' . $width . '">';
		
		//
//		_eb_echo_widget_title( $title, 'echbay-widget-blogs-title', $before_title );
		
		
		//
		echo '<div class="' . $custom_style . '">';
		
		if ( $menu != '' ) {
			echo _eb_echbay_menu( $menu );
		} else {
			echo 'Select menu for widget';
		}
		
		echo '</div>';
		echo '</div>';
		
		//
		echo $after_widget;
	}
}




