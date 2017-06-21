


var jEBE_slider_cache_option = {};

function jEBE_slider ( jd, conf, callBack ) {
	
	// kiểm tra và nạp jQuery
	if ( typeof jQuery != 'function' ) {
		console.log('jQuery not found');
		return false;
	}
	if ( typeof $ != 'function' ) {
		$ = jQuery;
	}
	
	//
	if ( typeof jd == 'undefined' || jd == '' || $(jd).length == 0 ) {
		console.log( 'jEBE_slider! ' + jd + ' not found' );
		return false;
	}
	console.log('jEBE_slider! Create slider ' + jd);
	var jd_class = 'child-' + jd.substr( 1 ).replace( /\.|\#|\s/g, '-' );
	var jd_to_class = '.' + jd_class;
	
	
	if ( typeof conf != 'object' ) {
		conf = {};
	}
	
	
	// config mặc định
	var set_default_conf = function ( k, v ) {
		if ( typeof conf[k] == 'undefined' ) {
			conf[k] = v;
		}
	};
	var get_thumbnail = function ( img ) {
		if ( typeof ___eb_set_img_to_thumbnail == 'function' ) {
			return ___eb_set_img_to_thumbnail( img );
		}
		return img;
	};
	var remove_thumbnail = function ( img ) {
		if ( typeof ___eb_set_thumb_to_fullsize == 'function' ) {
			return ___eb_set_thumb_to_fullsize( img );
		}
		return img;
	};
	/*
	var inner_css = function ( str ) {
		if ( document.getElementById('jEBE_slider_css') == null ) {
//			$('head').append('<style id="jEBE_slider_css"></style>');
			$('link').after('<style id="jEBE_slider_css"></style>');
		}
		
		if ( typeof str == 'undefined' || str == '' ) {
			return false;
		}
		str = str.split("\n");
		var new_str = '';
		for ( var i = 0; i < str.length; i++ ) {
			str[i] = $.trim( str[i] );
			if ( str[i] != '' ) {
				new_str += str[i] + "\n";
			}
		}
		
		$('#jEBE_slider_css').append( $.trim( new_str ) );
	};
	*/
	
	// mặc định là ẩn nếu không có LI nào
	set_default_conf( 'hide_if_null', true );
	
	// kích thước
	set_default_conf( 'width', 1 );
	set_default_conf( 'height', 1 );
	// tỷ lệ giữa chiều cao và chiều rộng
	set_default_conf( 'size', conf['height'] + '/' + conf['width'] );
	
	// tự động chạy
	set_default_conf( 'autoplay', false );
	// tốc độ chuyển slide ( mini giây )
	set_default_conf( 'speed', 0 );
	if ( conf['speed'] > 0 ) {
		conf['speed'] = conf['speed']/ 1000;
	}
	// giãn cách chuyển slide (mini giây)
	set_default_conf( 'speedNext', 5000 );
	
	// nút bấm chuyển ảnh
	set_default_conf( 'buttonListNext', true );
	
	// thumbnail -> vì là lấy hình ảnh làm thumbnail -> cần class chứa URL ảnh (src hoặc data-img)
	set_default_conf( 'thumbnail', false );
	// kích thước của thumbnail
	set_default_conf( 'thumbnailWidth', 90 );
	set_default_conf( 'thumbnailHeight', conf['thumbnailWidth'] );
	
	// Số LI hiển thị một lúc
	set_default_conf( 'visible', 1 );
	
	// Bấm chuyển ảnh trên slider
	set_default_conf( 'sliderArrow', false );
	// nút bấm
	set_default_conf( 'sliderArrowLeft', 'fa-angle-left' );
	set_default_conf( 'sliderArrowRight', 'fa-angle-right' );
	// font-size
	set_default_conf( 'sliderArrowSize', 30 );
	// Kích thước nút bấm slider
	set_default_conf( 'sliderArrowWidthLeft', 'auto' );
	set_default_conf( 'sliderArrowWidthRight', 'auto' );
	
	// conf['sliderArrow']
	console.log( conf );
	
	
	// kiểm tra có li nào ở trong không
	var len = $(jd + ' li').length || 0;
//	console.log( len );
	if ( len == 0 ) {
		if ( conf['hide_if_null'] == true ) {
			$(jd).hide();
		}
		return false;
	}
	
	
	// chiều cao cho slide
	var wit = $(jd).width(),
		hai = wit * eval( conf['size'] )/ conf['visible'] - 1;
	
	$(jd).height( hai ).attr({
		'data-size' : conf['size']
	}).css({
		'line-height' : hai + 'px'
	});
	
	// chỉ có 1 ảnh -> thoát
//	if ( len == 1 ) {
	if ( len < conf['visible'] ) {
		return false;
	}
	
	//
	/*
	$(window).resize(function(e) {
		// chỉnh lại chiều cao cho slide
		$(jd).height( hai ).css({
			'line-height' : hai + 'px'
		});
	});
	*/
	
	
	//
	if ( conf['thumbnail'] != false ) {
		var str_btn = '',
			i = 0;
		$(jd + ' ' + conf['thumbnail']).each(function() {
			var img = $(this).attr('data-img') || $(this).attr('data-src') || $(this).attr('src') || '';
			if ( img != '' ) {
				img = get_thumbnail( img );
			}
			
			str_btn += '<li data-i="' +i+ '" data-src="' + img + '"><div style="background-image: url(\'' + img + '\');">&nbsp;</div></li>';
			
			i++;
		});
		$(jd).after('<div class="' + jd_class + '"><div class="jEBE_slider-thumbnail"><ul class="cf">' + str_btn + '</ul></div></div>');
		
		// tạo css cho thumbmail
		$(jd_to_class + ' .jEBE_slider-thumbnail div').css({
			width: conf['thumbnailWidth'] + 'px',
			height: conf['thumbnailHeight'] + 'px'
		});
	}
	
	
	// cái này phải nằm sau lệnh thumbnail thì nó mới lên trước được
	if ( conf['buttonListNext'] == true ) {
		var str_btn = '';
		for ( var i = 0; i < len; i++ ) {
			str_btn += '<li data-i="' +i+ '"><i class="fa fa-circle"></i></li>';
		}
		$(jd).after('<div class="' + jd_class + '"><div class="big-banner-button"><ul>' + str_btn + '</ul></div></div>');
	}
	
	// tạo css cho slider
	$(jd).addClass('jEBE_slider-position');
	
	/*
	$(jd).css({
		position: 'relative',
		overflow: 'hidden'
	});
	*/
	
	$(jd + ' ul').width( ( 100 * len/ conf['visible'] ) + '%' );
	if ( conf['speed'] > 0 ) {
		$(jd + ' ul').css({
			'-moz-transition': 'all ' + conf['speed'] + 's ease',
			'-o-transition': 'all ' + conf['speed'] + 's ease',
			'-webkit-transition': 'all ' + conf['speed'] + 's ease',
			transition: 'all ' + conf['speed'] + 's ease'
		});
	}
	
	$(jd + ' li').css({
//		width: ( 100/ len/ conf['visible'] ) + '%',
		width: ( 100/ len ) + '%'
	});
	
	
	// hiệu ứng khi click vào thẻ LI
	var  i = 0;
	$(jd + ' li').each(function() {
		$(this).attr({
			'data-i' : i
		});
		
		i += 1;
	}).click(function () {
		var i = $(this).attr('data-i') || 0;
		if ( i * conf['visible'] >= $(jd + ' li').length ) {
			i = 0;
		}
		
		$(jd + ' ul').css({
			left: ( 0 - i * 100 ) + '%'
//			left: ( 0 - i * 100/ conf['visible'] ) + '%'
		});
		
		$(jd).attr({
			'data-i' : i
		});
		
		//
		$('.' + jd_class + ' li').removeClass('selected');
		$('.' + jd_class + ' li[data-i="' + i + '"]').addClass('selected');
	});
	$(jd + ' li[data-i="0"]').click();
	
	
	//
	$('.' + jd_class + ' li').click(function () {
		var i = $(this).attr('data-i') || 0;
//		console.log(i);
//		console.log(jd);
		
//		if ( $(jd + ' li[data-i="' + i + '"]').length == 0 ) {
//		if ( i >= $(jd + ' li').length ) {
//			i = 0;
//		}
		
		$(jd + ' li[data-i="' + i + '"]').click();
		
		// tắt auto play
		if ( jEBE_slider_cache_option[jd]['autoplay'] == true ) {
			jEBE_slider_cache_option[jd]['autoplay'] = false;
			console.log('Stop autoplay for ' + jd);
		}
	});
	
	
	//
	if ( conf['autoplay'] == true ) {
		jEBE_slider_cache_option[jd] = {
			autoplay: true
		};
		
		setInterval(function () {
			if ( jEBE_slider_cache_option[jd]['autoplay'] == true ) {
				var i = $(jd).attr('data-i') || 0;
				i -= -1;
//				i -= 0 - conf['visible'];
//				console.log(i);
//				console.log(jd);
				
//				if ( $(jd + ' li[data-i="' + i + '"]').length == 0 ) {
				if ( i >= $(jd + ' li').length ) {
					i = 0;
				}
				
				$(jd + ' li[data-i="' + i + '"]').click();
			}
		}, conf['speedNext']);
	} else {
		jEBE_slider_cache_option[jd] = {
			autoplay: false
		};
	}
	
	
	//
	if ( conf['sliderArrow'] == true && len > conf['visible'] ) {
		$(jd).append('<div class="jEBE_slider-toLeft"><i class="fa ' + conf['sliderArrowLeft'] + '"></i></div>\
		<div class="jEBE_slider-toRight text-right"><i class="fa ' + conf['sliderArrowRight'] + '"></i></div>');
		
		//
		$(jd + ' .jEBE_slider-toLeft').click(function () {
			var i = $(jd).attr('data-i') || 0;
			i -= 1;
//			i -= conf['visible'];
//			console.log(i);
//			console.log(jd);
			
			if ( i < 0 ) {
				i = $(jd + ' li').length - 1;
			}
			
			$(jd + ' li[data-i="' + i + '"]').click();
		});
		
		$(jd + ' .jEBE_slider-toRight').click(function () {
			var i = $(jd).attr('data-i') || 0;
			i -= -1;
//			i -= 0 - conf['visible'];
//			console.log(i);
//			console.log(jd);
			
//			if ( $(jd + ' li[data-i="' + i + '"]').length == 0 ) {
			if ( i >= $(jd + ' li').length ) {
				i = 0;
			}
			
			$(jd + ' li[data-i="' + i + '"]').click();
		});
		
		// tạo css cho nut next
		$( jd + ' .jEBE_slider-toLeft, ' + jd + ' .jEBE_slider-toRight' ).css({
			'font-size': conf['sliderArrowSize'] + 'px'
		});
		$( jd + ' .jEBE_slider-toLeft' ).css({
			'width': conf['sliderArrowWidthLeft']
		});
		$( jd + ' .jEBE_slider-toRight' ).css({
			'width': conf['sliderArrowWidthRight']
		});
		
		
		// sử dụng swipe
		// https://github.com/mattbryson/TouchSwipe-Jquery-Plugin
		// http://labs.rampinteractive.co.uk/touchSwipe/demos/Basic_swipe.html
		/*
		$(jd).swipe( {
			// Generic swipe handler for all directions
			swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
				if ( direction == 'left' ) {
					$(jd + ' .jEBE_slider-toLeft').click();
				}
				else if ( direction == 'right' ) {
					$(jd + ' .jEBE_slider-toRight').click();
				}
			},
			// Default is 75px, set to 0 for demo so any distance triggers swipe
			threshold:0
		});
		*/
		
		
		// https://www.w3schools.com/jquerymobile/jquerymobile_events_touch.asp
		/*
		if ( $(window).width() < 750 ) {
			$(jd + ' .jEBE_slider-toLeft, ' + jd + ' .jEBE_slider-toRight').on("swiperight", function() {
				$(jd + ' .jEBE_slider-toLeft').click();
			}).on("swipeleft",function(){
				$(jd + ' .jEBE_slider-toRight').click();
			});
		}
		*/
		
		
		
		// https://coderwall.com/p/bxxjfq/detecting-swipe-using-jquery
		/*
		if ( $(window).width() < 750 ) {
			$(jd + ' .jEBE_slider-toLeft, ' + jd + ' .jEBE_slider-toRight')
//			.on('mousedown touchstart', function (e) {
			.on('touchstart', function (e) {
//			.on('click', function (e) {
//			.click(function(e) {
//			.on('mousedown', function (e) {
//			.mousedown(function(e) {
//			.on('mouseover', function (e) {
//				console.log( e.originalEvent.touches[0] );
//				console.log("Start: (x,y) = (" + e.pageX + "," + e.pageY +")");
				xDown = e.pageX || 0;
				yDown = e.pageY || 0;
			})
//			.on('mouseup touchend',function (e) {
			.on('touchend',function (e) {
//			.touchend(function(e) {
//			.on('mouseup',function (e) {
//			.on('mouseout',function (e) {
//				console.log( e.originalEvent.changedTouches[0] );
//				console.log("End: (x,y) = (" + e.pageX + "," + e.pageY +")");
				xUp = e.pageX || 0;
				yUp = e.pageY || 0;
				
				var xDiff = xDown - xUp;
				var yDiff = yDown - yUp;
				
				// most significant
				if ( Math.abs( xDiff ) > Math.abs( yDiff ) ) {
//				if ( xDiff > yDiff ) {
					if ( xDiff > 0 ) {
						// left swipe
//						console.log('left');
						$(jd + ' .jEBE_slider-toLeft').click();
					} else {
						// right swipe
//						console.log('right');
						$(jd + ' .jEBE_slider-toRight').click();
					}
				} else {
					if ( yDiff > 0 ) {
						// up swipe
						console.log('up');
						$(jd + ' .jEBE_slider-toLeft').click();
					} else { 
						// down swipe
						console.log('down');
						$(jd + ' .jEBE_slider-toRight').click();
					}
				}
				
				//
//				$(this)
//				.mouseout()
//				.mousedown()
//				.mouseover()
//				;
				
				return true;
				
			})
			;
		}
		*/
		
		
		//
		
	}
	
	
	//
	if ( typeof callBack == 'function' ) {
		callBack();
	}
	
}


