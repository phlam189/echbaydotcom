


/*
* Toàn bộ function dành cho admin sẽ được viết vào đây
*/


//console.log( typeof $ );
//console.log( typeof jQuery );
if ( typeof $ == 'undefined' ) {
	$ = jQuery;
}



function eb_drop_menu(fix_id, select_id) {
//	console.log( select_id );
	if (typeof select_id == 'undefined') {
		select_id = 0;
	}
	else {
		$('#' + fix_id + ' option[value=\'' + select_id + '\']').attr({
			selected: 'selected'
		});
	}
	
	var str = '',
		sl = $('#' + fix_id + ' select').val(),
		sl_name = $('#' + fix_id + ' select').attr('name'),
		search_id = sl_name + Math.random().toString(32) + Math.random().toString(32),
		//
//		sl_html = $('#' + fix_id).html(),
		sl_html = '<input type="hidden" name="' +sl_name+ '" value="' +select_id+ '" />',
		list = '';
	
	search_id = search_id.replace(/\./g, '_');
	str += '<i class="fa fa-caret-down search-select-down"></i>';
	str += '<input type="text" title="T\u00ecm ki\u1ebfm nhanh" id="' + search_id + '" autocomplete="off" />';
	
	$('#' + fix_id + ' option').each(function() {
		var a = $(this).val() || '',
			b = $(this).text(),
			lnk = $(this).attr('data-href') || '',
			level = $(this).attr('data-level') || '0',
			al_show = $(this).attr('data-show') || '0',
			c = g_func.non_mark_seo(a + b);
		
		if (lnk == '') {
			lnk = b;
		} else {
			lnk = '<a href="' + lnk + '">' + b + '</a>';
		}
		
		list += '<li title="' + b + '" data-show="' + al_show + '" data-level="' + level + '" data-value="' + a + '" data-key="' + c.replace(/-/g, '') + '" class="fa">' + lnk + '</li>';
	});
	
	list = '<div><ul>' + list + '</ul></div>';
	$('#' + fix_id).html(str + list + sl_html).addClass('search-select-option');
	
	/*
	$('#' + fix_id + ' option[value=\'' + select_id + '\']').attr({
		selected: 'selected'
	});
	*/
	
	var z_index = $('.search-select-option').length + 1;
	$('.search-select-option').each(function() {
		$(this).css({
			'z-index': z_index
		});
		
		z_index--;
	});
	
	$('#' + fix_id + ' li').off('click').click(function() {
		$('#' + fix_id + ' li').removeClass('selected');
		$(this).addClass('selected');
		
		var tit = $(this).attr('title') || '';
		tit = tit.replace(/\s+\s/g, ' ');
		
		$('#' + search_id).attr({
			placeholder: tit
		}).val('');
		
		
		// sử dụng text type thay vì selext box
		$('#' + fix_id + ' input[name=\'' +sl_name+ '\']').val( $(this).attr('data-value') || '' );
		
		//
		/*
		$('#' + fix_id + ' option').removeAttr('selected');
		
		$('#' + fix_id + ' option[value=\'' + ($(this).attr('data-value') || '') + '\']').attr({
			selected: 'selected'
		});
		*/
	});
	
	$('#' + fix_id + ' li[data-value=\'' + sl + '\']').click();
	$('#' + fix_id + ' div').addClass('search-select-scroll');
	
	$(document).mouseup(function(e) {
		var container = $("#" + fix_id + " div");
		if (!container.is(e.target) && container.has(e.target).length === 0) {
			container.hide();
		}
	});
	
	$('#' + search_id).off('click').click(function() {
		$('#' + fix_id + ' div').show()
	}).off('keyup').keyup(function(e) {
		if (e.keyCode == 13) {
			return false
		} else if (e.keyCode == 27) {
			$("#" + fix_id + " div").hide();
			return false
		} else if (e.keyCode == 32) {
			$('#' + fix_id + ' div').show();
		}
		
		var key = $(this).val() || '';
		if (key != '') {
			key = g_func.non_mark_seo(key);
			key = key.replace(/[^0-9a-zA-Z]/g, '');
		}
		
		if (key != '') {
			$('#' + fix_id + ' li').hide().each(function() {
				if (a != '') {
					var a = $(this).attr('data-key') || '';
					if (a != '' && a.split(key).length > 1) {
						$(this).show();
					}
				}
			});
			
			$('#' + fix_id + ' li[data-show=1]').show()
		} else {
			$('#' + fix_id + ' li').show()
		}
		
		if ($('#' + fix_id + ' ul').height() > 250) {
			$('#' + fix_id + ' div').addClass('search-select-scroll');
		} else {
			$('#' + fix_id + ' div').removeClass('search-select-scroll');
		}
	});
}

// chức năng đồng bộ nội dung website theo chuẩn chung của EchBay
/*
function click_remove_style_of_content () {
	
	//
	$('.click_remove_content_style').click(function () {
		
		// hủy check ngay và luôn
		$(this).prop({
			checked : false
		});
		
		//
		var content_id = $(this).attr('data-editer') || 't_noidung';
		
		// tên đầy đủ của text editter
		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm remove all style in this content!') == false ) {
			return false;
		}
		
		// Các thẻ sẽ loại bỏ các attr gây ảnh hưởng đến style
		var arr = [
			'article',
			'font',
			'span',
			'ul',
			'ol',
			'li',
			'br',
			
			'strong',
			'blockquote',
			'b',
			'u',
			'i',
			'em',
			
			'pre',
			'code',
			'section',
			
			'table',
			'tr',
			'td',
			
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			
			'a',
			'p',
			'div'
		];
		
		//
		for ( var i = 0; i < arr.length; i++ ) {
			$( content_id ).contents().find( arr[i] ).removeAttr('style').removeAttr('id').removeAttr('face').removeAttr('dir').removeAttr('size');
		}
		
		
		// Các thẻ sẽ bị loại bỏ khỏi html
		var arr = [
			'figure',
			'figcaption'
		];
		
		//
		for ( var i = 0; i < arr.length; i++ ) {
			$( content_id ).contents().find( arr[i] ).each(function() {
				$(this).before( $(this).html() ).remove();
			});
		}
		
		
		// xử lý riêng với IM
		$( content_id ).contents().find( 'img' ).removeAttr('style');
		
		
		// xử lý riêng với TABLE
		$( content_id ).contents().find( 'table' ).removeAttr('width').attr({
			border : 0,
//			width : '100%',
			cellpadding : 6,
			cellspacing : 0
		}).addClass('table-list');
		
		//
		$( content_id ).contents().find( 'table p' ).each(function () {
			$(this).before( $(this).html() ).remove();
		});
		
		//
		$( content_id ).contents().find( 'td' ).removeAttr('width');
		
		
		// loại bỏ thẻ style nếu có
//		console.log( 1 );
//		console.log( $( content_id ).contents().find( 'style' ).length );
		$( content_id ).contents().find( 'style' ).remove();
	});
}
*/



// chức năng đồng bộ hình ảnh trong nội dung website theo chuẩn chung của EchBay
/*
function click_remove_style_of_img_content () {
	
	//
	$('.click_remove_content_img_style').click(function () {
		
		// hủy check ngay và luôn
		$(this).prop({
			checked : false
		});
		
		//
		var content_id = $(this).attr('data-editer') || 't_noidung';
		
		// tên đầy đủ của text editter
		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm remove image style in this content!') == false ) {
			return false;
		}
		
		//
		$( content_id ).contents().find( 'img' ).each(function() {
			var a = $(this).attr('src') || '';
			
			if ( a != '' ) {
				$(this).before( '<img src="' +a+ '" />' );
			}
			
			$(this).remove();
		});
	});
}
*/




function create_content_editer(id) {
	var frame_id = id + 'wysiwyg';
	
	// tự hủy nếu không có ID
	if ( dog ( frame_id ) == null ) {
		return false;
	}
	
	// thi thoảng bị lỗi mất scoll hiện tại -> thêm lệnh này để trỏ về đúng vị trí
	var current_scroll_top = window.scrollY || $(window).scrollTop();
	
	// đưa về kích thước chuẩn để tính toán lại
	$('#' + frame_id).height( 300 );
	
	//
	var famre_height = $('#' + frame_id).contents().find('body').height();
	famre_height = $w.number_only(famre_height);
//	console.log(famre_height);
	
	if (isNaN(famre_height) || famre_height < 300) {
		famre_height = 300
	}
//	console.log(famre_height);
	
	$('#' + frame_id).height( famre_height - -80 );
	console.log('Fix editer height #' + frame_id);
	
	click_edit_img_in_content();
	
	//
	window.scroll( 0, current_scroll_top );
}





function click_download_img_in_content() {
	// lấy URL của frm upload hiện tại -> dùng để copy ảnh
	var a = document.frm_multi_upload.action || '',
		b = $('#img_edit_source').val() || '';
//			alert(a);
	
	if ( a == '' ) {
		alert('Không xác định được nguồn xử lý');
		return false;
	}
	
	if ( b == '' ) {
		alert('Không xác định được file nguồn');
		return false;
	}
	
	if ( b.split('//').length == 1 ) {
		alert('Tính năng chỉ áp dụng cho URL tuyệt đối');
		return false;
	}
	
	// lấy nguồn hiện tại để so sánh
	var c = web_link.split('//')[1].split('/')[0];
	if ( c.substr(0, 4) == 'www.' ) {
		c = c.substr(4);
	}
	//alert(c);
	
	// ảnh cần copy phải khác nguồn với site này
	if ( b.split( c ).length > 1 ) {
		alert('Tính năng chỉ hỗ trợ download ảnh từ website khác về');
		return false;
	}
	
	// kiểm tra xem có phải là ảnh không
	var fname = b.split('/').pop().split('?')[0].split('&')[0].split('#')[0].toLowerCase(),
		ftype = fname.split('.').pop();
//				alert(ftype);
	
	// định dạng được phép upload
	switch ( ftype ) {
		case "gif":
		case "jpg":
		case "jpeg":
		case "png":
//					case "swf":
			break;
		
		default:
			alert('Định dạng chưa được hỗ trợ');
			return false;
			break;
	}
	
//				return false;
	
	// tạo link download
	if ( a.split('?').length == 1 ) {
		a += '?';
	} else {
		a += '&';
	}
	
	window.open( a + 'download_img=' + encodeURIComponent( b ) + '&fname=' + fname, 'target_eb_iframe' );
}




function click_edit_img_in_content() {
	if ( dog('t_noidungwysiwyg') != null ) {
		
		
		var rand_img_a_id = function ( t ) {
			return t + '-for-contant-' + pid + '_' + Math.random().toString(32).substr(3, 4);
		};
		
		// chỉnh sửa URL
		$("#t_noidungwysiwyg").contents().find('a').off('click').click(function() {
			var x = $(this).offset().left + $("#t_noidungwysiwyg").offset().left,
				y = $(this).offset().top + $("#t_noidungwysiwyg").offset().top + 20,
				jd = $(this).attr('id') || '';
//			console.log(x);
//			console.log(y);
			
			//
			if (jd == '') {
				jd = rand_img_a_id('url');
				$(this).attr({
					id : jd
				});
			}
			
			//
			$('.img-click-edit-img').show().css({
				left : x + 'px',
				top : y + 'px',
			}).attr({
				'data-process' : jd
			}).off('click').click(function () {
				$(this).fadeOut();
//				console.log($(this).attr('data-process'));
				
//				$( 'img#' + $(this).attr('data-process') ).dblclick();
				$("#t_noidungwysiwyg").contents().find( 'a#' + $(this).attr('data-process') ).dblclick();
			}).html('Chỉnh sửa URL <i class="fa fa-link"></i>');
		}).off('dblclick').dblclick(function() {
			// Nếu trong thẻ a này có thẻ IMG -> tắt chức năng sửa URL đi
			if ( $('img', this).length > 0 ) {
//				$(this).off('dblclick');
//				return false;
			}
			
			//
			var a = $(this).attr('title') || '',
				tex = $(this).html() || '',
				jd = $(this).attr('id') || '',
				lnk = $(this).attr('href') || '',
				tar = $(this).attr('target') || '',
				ren = $(this).attr('rel') || '',
				s = window.scrollY || $(window).scrollTop(),
				edit_frm = $('.admin-edit-url');
			
			edit_frm.show();
			
			var l = $(window).width() - edit_frm.width(),
				t = $(window).height() - edit_frm.height();
			l = l / 2;
			t = t / 5;
			
			edit_frm.show().css({
				top: (t + s) + 'px',
				left: l + 'px'
			});
			
			if (jd == '') {
				jd = rand_img_a_id('url');
				$(this).attr({
					id: jd
				});
			}
			
			//
			$('#editer_url_edit_url').val(lnk);
			$('#editer_url_edit_title').val(a);
			$('#editer_url_edit_text').val(tex);
			
			//
			$('#editer_url_edit_target option:first, #editer_url_edit_rel option:first').prop({
				selected : true
			});
			if ( tar != '' ) {
				$('#editer_url_edit_target option[value="' +tar+ '"]').prop({
					selected : true
				});
			}
			if ( ren != '' ) {
				$('#editer_url_edit_rel option[value="' +ren+ '"]').prop({
					selected : true
				});
			}
			
			//
			edit_frm.find('.img_edit_ok').off('click').click(function() {
				var arr_attr = {
					href: $('#editer_url_edit_url').val()
				};
				
				//
				var tit = $('#editer_url_edit_title').val() || '',
					tar = $('#editer_url_edit_target').val() || '',
					rel = $('#editer_url_edit_rel').val() || '';
				
				//
				if ( tit != '' ) {
					arr_attr['title'] = tit;
				}
				
				//
				if ( tar != '' ) {
					arr_attr['target'] = tar;
				}
				
				//
				if ( rel != '' ) {
					arr_attr['rel'] = rel;
				}
				
				$("#t_noidungwysiwyg").contents().find('a#' + jd).removeAttr('title').removeAttr('target').removeAttr('rel').attr(arr_attr).html( $('#editer_url_edit_text').val() || '' );
				
				$('.admin-edit-url .img_edit_cancel').click();
				
				click_edit_img_in_content();
			});
		});
		
		// chỉnh sửa ảnh
		$("#t_noidungwysiwyg").contents().find('img').off('click').click(function() {
			var x = $(this).offset().left + $("#t_noidungwysiwyg").offset().left,
				y = $(this).offset().top + $("#t_noidungwysiwyg").offset().top + $(this).height()/ 2,
				jd = $(this).attr('id') || '';
//			console.log(x);
//			console.log(y);
			
			//
			if (jd == '') {
				jd = rand_img_a_id('img');
				$(this).attr({
					id : jd
				});
			}
			
			//
			$('.img-click-edit-img').show().css({
				left : x + 'px',
				top : y + 'px',
			}).attr({
				'data-process' : jd
			}).off('click').click(function () {
				$(this).fadeOut();
//				console.log($(this).attr('data-process'));
				
//				$( 'img#' + $(this).attr('data-process') ).dblclick();
				$("#t_noidungwysiwyg").contents().find( 'img#' + $(this).attr('data-process') ).dblclick();
			}).html('Chỉnh sửa ảnh <i class="fa fa-image"></i>');
		}).off('dblclick').dblclick(function() {
			var a = $(this).attr('alt') || '',
				jd = $(this).attr('id') || '',
				img = $(this).attr('src') || '',
				wit = $(this).width(),
				hai = $(this).height(),
				s = window.scrollY || $(window).scrollTop(),
				edit_frm = $('.img-edit-img');
			
			edit_frm.show();
			
			var l = $(window).width() - edit_frm.width(),
				t = $(window).height() - edit_frm.height();
			l = l / 2;
			t = t / 5;
			
			edit_frm.show().css({
				top: (t + s) + 'px',
				left: l + 'px'
			});
			
			if (a == '') {
				if ( typeof document.frm_admin_edit_content != 'undefined' ) {
					a = document.frm_admin_edit_content.t_tieude.value;
				}
				else if ( typeof document.frm_thread_add != 'undefined' ) {
					a = document.frm_thread_add.t_tieude.value;
				}
			}
			
			if (jd == '') {
				jd = rand_img_a_id('img');
				$(this).attr({
					id: jd
				});
			}
			
			// v2
			edit_frm.find('input[name="t_source"]').val( img );
			edit_frm.find('input[name="t_description"]').val( a ).focus();
			edit_frm.find('input[name="t_with"]').val( wit );
			edit_frm.find('input[name="t_height"]').val( hai );
			
			// v1
			/*
			$('#img_edit_source').val(img);
			$('#img_edit_description').val(a).focus();
			$('#img_edit_width').val(wit);
			$('#img_edit_height').val(hai);
			*/
			
			//
			edit_frm.find('.img_edit_ok').off('click').click(function() {
				var arr_attr = {
					src: $('#img_edit_source').val(),
					alt: $('#img_edit_description').val()
				};
				
				//
				var a = $("#t_noidungwysiwyg").contents().find('img#' + jd);
				
				//
				a.removeAttr('width').removeAttr('height').attr(arr_attr).width('auto').height('auto');
				
				var wit = $('#img_edit_width').val() || '',
					hai = $('#img_edit_height').val() || '';
				
				if (wit != '') {
					wit = $w.number_only(wit);
					if (wit > 0) {
						a.width(wit);
//						arr_attr['width'] = wit;
					}
				}
				
				if (hai != '') {
					hai = $w.number_only(hai);
					if (hai > 0) {
						a.height(hai);
//						arr_attr['height'] = hai;
					}
				}
				
				$('.img-edit-img .img_edit_cancel').click();
				
				click_edit_img_in_content();
			});
		});
		
		//
		$('#img_edit_width, #img_edit_height').off('click').click(function() {
			$(this).select();
		});
		
		//
		$('.img-edit-img .img_edit_cancel').off('click').click(function() {
			$('.img-edit-img').hide();
		});
		
		//
		$('.admin-edit-url .img_edit_cancel').off('click').click(function() {
			$('.admin-edit-url').hide();
		});
		
		//
		$('.img_edit_downoad').off('click').click(function() {
			// lấy URL của frm upload hiện tại -> dùng để copy ảnh
			var a = document.frm_multi_upload.action || '',
				b = $('#img_edit_source').val() || '';
//			alert(a);
			
			if ( a != '' && b != '' ) {
				// lấy nguồn hiện tại để so sánh
				var c = web_link.split('//')[1].split('/')[0];
				if ( c.substr(0, 4) == 'www.' ) {
					c = c.substr(4);
				}
				//alert(c);
				
				// ảnh cần copy phải khác nguồn với site này
				if ( b.split( c ).length > 1 ) {
					alert('Tính năng chỉ hỗ trợ download ảnh từ website khác về');
					return false;
				}
				
				// kiểm tra xem có phải là ảnh không
				var fname = b.split('/').pop().split('?')[0].split('&')[0].split('#')[0].toLowerCase(),
					ftype = fname.split('.').pop();
//				alert(ftype);
				
				// định dạng được phép upload
				switch ( ftype ) {
					case "gif":
					case "jpg":
					case "jpeg":
					case "png":
//					case "swf":
						break;
					
					default:
						alert('Định dạng chưa được hỗ trợ');
						return false;
						break;
				}
				
//				return false;
				
				// tạo link download
				if ( a.split('?').length == 1 ) {
					a += '?';
				} else {
					a += '&';
				}
				
				window.open( a + 'download_img=' + encodeURIComponent( b ) + '&fname=' + fname, 'target_eb_iframe' );
			}
		});
		
		//
		$(document).mouseup(function(e) {
			var container = $(".img-edit-img");
			if (!container.is(e.target) && container.has(e.target).length === 0) {
				container.hide();
			}
		});
	}
}




function fix_textarea_height() {
	$('.fix-textarea-height textarea, textarea.fix-textarea-height').each(function() {
		var a = $(this).attr('data-resize') || '',
			min_height = $(this).attr('data-min-height') || 60,
			add_height = $(this).attr('data-add-height') || 20;
//		console.log(min_height);
		
		if (a == '') {
			$(this).height(20);
			
			//
			var new_height = $(this).get(0).scrollHeight || 0;
			new_height -= 0 - add_height;
			if (new_height < min_height) {
				new_height = min_height;
			}
			
			//
			$(this).height(new_height);
			
			//
			console.log('Fix textarea height #' + ( $(this).attr('name') || $(this).attr('id') || 'NULL' ) );
		}
	}).off('click').click(function() {
		fix_textarea_height()
	}).off('change').change(function() {
		fix_textarea_height()
	});
}





/*
* Định vị vị trí trụ sở chính của website
*/
function create_img_gg_map ( latlon ) {
	if ( typeof latlon == 'undefined' || latlon == '' ) {
		return '';
	}
	
	var wit = $('#mapholder').width() || 400;
	if ( wit > 640 ) {
		wit = 640;
	}
	
	// Bản đồ trực tuyến
	var site = 'https://www.google.com/maps/@' + latlon + ',15z';
//	var site = 'https://maps.google.com/maps?sspn=' + latlon + '&t=h&hnear=London,+United+Kingdom&z=15&output=embed';
//	console.log(site);
	
	// URL only
	return '<a title="' +site+ '" href="' +site+ '" rel="nofollow" target="_blank">' +site+ '</a>';
	
	//
	/*
	var img = '//maps.googleapis.com/maps/api/staticmap?center=' + latlon + '&zoom=14&size=' + wit + 'x300&sensor=true';
	
	// iframe img
//	return '<iframe src="' +img+ '" width="' +wit+ '" height="300"></iframe>';
	
	// url and img
	return '<a title="' +site+ '" href="' +site+ '" rel="nofollow" target="_blank" class="d-block"><img src="' +img+ '" /></a>';
	*/
}


//
function auto_get_user_position ( current_position ) {
	if ( typeof document.frm_config == 'undefined' ) {
		console.log('frm_config not found');
		return false;
	}
	
	if ( dog('mapholder') == null ) {
		console.log('mapholder not found');
		return false;
	}
	
	var f = document.frm_config;
	
	//
	dog( 'mapholder', create_img_gg_map ( f.cf_position.value.replace( ';', ',' ) ) );
	
	/*
	if ( f.cf_content_language.value == '' ) {
		f.cf_content_language.value = navigator.userLanguage || navigator.language || '';
	}
	*/
	
	
	// lấy vị trí chính xác
	if ( typeof current_position != 'undefined' ) {
		navigator.geolocation.getCurrentPosition( function ( position ) {
			var lat = position.coords.latitude,
				lon = position.coords.longitude;
//			console.log( lat );
//			console.log( lon );
			
			//
			$('input[name=cf_position]').val( lat+ ';' +lon );
			
			//
			dog( 'mapholder', create_img_gg_map ( lat+ ',' +lon ) );
		}, function () {
			console.log( 'Not get user Position' );
		}, {
			timeout : 10000
		});
	}
	
	
	// lấy vị trí gần đúng
	if ( f.cf_region.value == '' || f.cf_placename.value == '' || f.cf_position.value == '' ) {
//		console.log( window.location.protocol );
		var url_get_ip_info = window.location.protocol + '//ipinfo.io';
		if ( typeof client_ip != 'undefined' && client_ip != '' ) {
			url_get_ip_info += '/' + client_ip;
		}
//		console.log( url_get_ip_info );
		
		//
		$.getJSON( url_get_ip_info, function(data) {
//			console.log( data ); return;
			
			if ( f.cf_region.value == '' && typeof data['country'] != 'undefined' ) {
				f.cf_region.value = data['country'];
			}
			
			if ( f.cf_placename.value == '' && typeof data['region'] != 'undefined' ) {
				f.cf_placename.value = data['region'];
			}
			
			if ( f.cf_position.value == '' && typeof data['loc'] != 'undefined' ) {
				f.cf_position.value = data['loc'];
				
				//
				dog( 'mapholder', create_img_gg_map ( f.cf_position.value.replace( ';', ',' ) ) );
			}
		});
	}
}

function checkFlashT(img) {
	img = img.split('.');
	img = img[img.length - 1];
	
	//
	if (img == 'swf') {
		return true;
	}
	
	//
	return false;
}


function insertPictureContent(image) {
	if (checkFlashT(image) == true) {
		alert('Không hỗ trợ Flash cho mục này');
		return;
	}
	
	//
	if (image.split('//').length == 1) {
		image = web_link.replace('https://', 'http://') + image;
	}
	
	// thi thoảng bị lỗi mất scoll hiện tại -> thêm lệnh này để trỏ về đúng vị trí
	var current_scroll_top = window.scrollY || $(window).scrollTop();
	
	//
	$w.fm('t_noidung', 'img', image);
	
	//
	create_content_editer('t_noidung');
	
	//
	window.scroll( 0, current_scroll_top );
}




function EBA_add_img_to ( img, id ) {
	dog(id).value = img;
}

function EBA_preview_img_logo ( img, id ) {
//	if ( typeof img == 'undefined' || img == '' ) {
	if ( typeof img == 'undefined' ) {
		return false;
	}
	
	if ( img != '' && img.split('//').length == 1 ) {
		if ( img.substr( 0, 1 ) == '/' ) {
			img = img.substr(1);
		}
		img = web_link + img;
	}
	
	$('.' + id).css({
		'background-image' : 'url(\'' + img + '\')'
	});
}

function EBA_add_img_logo ( img, id, set_full_link ) {
	console.log(img);
	if ( typeof set_full_link == 'undefined' || set_full_link == false || set_full_link == 0 ) {
		img = img.replace( web_link, '' );
	}
	EBA_add_img_to( img, id );
	EBA_preview_img_logo( img, id );
}


// phần thiết lập thông số của size -> chỉnh về 1 định dạng
function convert_size_to_one_format () {
	$('.fixed-size-for-config').off('change').change(function () {
		var a = $(this).val() || '';
		if ( a != '' ) {
			a = a.replace( /\s/g, '' );
			
			// nếu có dấu x -> chuyển về định dạng của Cao/ Rộng
			if ( a.split('x').length > 1 ) {
				a = a.split( 'x' );
				
				a = a[1] + '/' + a[0];
			}
			a = a.toString().replace(/[^0-9\/]/g, '');
			
			$(this).val( a );
		}
	}).off('blur').blur(function () {
		$(this).change();
	});
}





function eb_func_add_nut_product_size ( str, i ) {
	if ( typeof str == 'undefined' ) {
		str = '';
	}
	if ( typeof i == 'undefined' ) {
		i = 0;
	}
	
	//
	return '\
	<div class="eb-admin-product-size">\
		<ul class="cf">\
			' + str + '\
			<li data-parent="' + i + '" data-add="1" title="Thêm size mới"><i class="fa fa-plus"></i></li>\
		</ul>\
	</div>';
}

function eb_func_show_product_size () {
	
	// nếu mảng size chưa được tạo -> tìm và tạo từ string
	if ( typeof eb_global_product_size != 'object' ) {
		
		// TEST
		/*
		console.log('TEST');
		if ( $('#_eb_product_size').val() == '' ) {
			$('#_eb_product_size').val(',{name:"800x1200",val:"1554000"},{name:"1000x1200",val:"1849000"},{name:"1200x1500",val:"2432000"},{name:"1200x1600",val:"2566000"},{name:"1200x1800",val:"2814000"},{name:"1200x2000",val:"2981000"},{name:"1200x2200",val:"3239000"},{name:"1200x2400",val:"3496000"}');
		}
		*/
		
		//
		eb_global_product_size = $('#_eb_product_size').val() || '';
//		console.log( eb_global_product_size );
		if ( eb_global_product_size != '' ) {
			
			// xử lý với các dữ liệu cũ đang bị lệch sóng
			if ( eb_global_product_size.substr(0, 1) == ',' ) {
				eb_global_product_size = eb_global_product_size.substr(1);
			}
			
			if ( eb_global_product_size.substr(0, 1) != '[' ) {
				eb_global_product_size = "[" + eb_global_product_size + "]";
			}
//			console.log( eb_global_product_size );
			
			// chuyển từ string sang object
			eb_global_product_size = eval( eb_global_product_size );
			
		} else {
			eb_global_product_size = [];
		}
//		console.log( JSON.stringify( eb_global_product_size ) );
		
		// nếu mảng số 0 tồn tại tham số name -> kiểu dữ liệu cũ -> convert sang dữ liệu mới
		if ( typeof eb_global_product_size[0] != 'undefined' ) {
			if ( typeof eb_global_product_size[0][0] == 'undefined' ) {
				var eb_global_product_size_v2 = eb_global_product_size.slice();
//				console.log( eb_global_product_size );
//				console.log( eb_global_product_size_v2 );
				
				eb_global_product_size = [];
				eb_global_product_size[0] = eb_global_product_size_v2;
//				console.log( JSON.stringify( eb_global_product_size ) );
			}
		}
		
	}
//	console.log( eb_global_product_size );
//	console.log( eb_global_product_size.length );
	
	var str_size = '';
	if ( eb_global_product_size.length > 0 ) {
		for ( var i = 0; i < eb_global_product_size.length; i++ ) {
//			console.log( i );
			
			var str_node_size = (function ( arr ) {
//				console.log( arr );
				
				var str = '';
				
				if ( typeof arr == 'object' ) {
					for ( var j = 0; j < arr.length; j++ ) {
						// conver từ bản code cũ sang
						if ( typeof arr[j].name == 'undefined' ) {
							if ( typeof arr[j].ten != 'undefined' ) {
								arr[j].name = arr[j].ten;
							}
							else {
								arr[j].name = '';
							}
						}
						
						if ( typeof arr[j].val == 'undefined' ) {
							if ( typeof arr[j].soluong != 'undefined' ) {
								arr[j].val = arr[j].soluong;
							}
							else {
								arr[j].val = 0;
							}
						}
						
						//
						str += '<li data-parent="' + i + '" data-node="' + j + '" data-size="' + arr[j].name + '" data-quan="' + arr[j].val + '" title="Size: ' + arr[j].name + '/ Số lượng: ' + arr[j].val + '">' + arr[j].name + '/ ' + arr[j].val + '</li>';
					}
				}
				
				return str;
			})( eb_global_product_size[i] );
			
			//
			if ( str_node_size != '' ) {
				str_size += eb_func_add_nut_product_size( str_node_size, i );
			}
		}
	} else {
		str_size += eb_func_add_nut_product_size();
	}
	
	//
//	console.log(eb_inner_html_product_size);
	$('#' + eb_inner_html_product_size).html( str_size );
//	$('#' + eb_inner_html_product_size + ' ul:last li:last').after('<li data-add="group" title="Thêm nhóm size mới (một số theme mới hỗ trợ tính năng này)"><i class="fa fa-plus"></i> <i class="fa fa-plus"></i></li>');
	
	// chuyển từ object sang string
	/*
	eb_global_product_size = JSON.parse(eb_global_product_size);
	eb_global_product_size = $.parseJSON(eb_global_product_size);
	console.log( eb_global_product_size );
	console.log( eb_global_product_size.length );
	*/
	
	// gán trở lại để còn lưu dữ liệu
	if ( eb_global_product_size.length > 0 ) {
		$('#_eb_product_size').val( JSON.stringify( eb_global_product_size ) );
	} else {
		$('#_eb_product_size').val('');
	}
	
}

function check_eb_input_edit_product_size () {
	$('.eb-input-edit-product-size button').click();
	return false;
}

// kiểm tra và tạo size
function eb_func_global_product_size () {
	
	// nếu có module size -> chỉ sản phẩm mới có
	var kk = '_eb_product_size';
	
	if ( dog(kk) == null ) {
		return false;
	}
//	alert(1);
	
	//
	/*
	console.log('TEST');
	$('#' + kk).attr({
		type : 'text'
	});
	*/
	
	// tạo khung để sử dụng chức năng add size
	eb_inner_html_product_size = 'oi' + kk;
	
	// nếu chưa có HTML để tạo hiệu ứng -> tạo
//	if ( dog(eb_inner_html_product_size) == null ) {
	if ( $('#' + eb_inner_html_product_size).length == 0 ) {
		
		$('tr[data-row="_eb_product_color"]').after('\
		<tr data-row="' + kk + '">\
			<td class="t bold">Kích thước</div></td>\
			<td id="' + eb_inner_html_product_size + '" class="i"></td>\
		</tr>');
		
		// thêm chức năng sửa size
//		$('body').append('\');
		
	}
	
	//
	eb_func_show_product_size();
	eb_func_click_modife_product_size();
	
}

// các hiệu ứng khi click ào thẻ LI
function eb_func_click_modife_product_size () {
	
	$('#' + eb_inner_html_product_size + ' li').off('click').click(function () {
//		console.log(1);
		
		$('#' + eb_inner_html_product_size + ' li').removeClass('redcolor').removeClass('selected');
		
		//
		var a = $(this).attr('data-add') || '';
		
		// sửa size
		if ( a == '' ) {
			$(this).addClass('redcolor').addClass('selected');
		}
		else {
			// thêm nhóm size
			if ( a == 'group' ) {
				if ( typeof eb_global_product_size[0] != 'undefined' ) {
					var add_new_group_size = eb_global_product_size[0].slice();
					
//					eb_global_product_size[ eb_global_product_size.length ] = [];
					eb_global_product_size.push( add_new_group_size );
				} else {
					alert('First object not found');
					return false;
				}
			}
			// thêm size
			else {
				
				//
				var size_parent = $(this).attr('data-parent') || 0;
				
				if ( typeof eb_global_product_size[size_parent] == 'undefined' ) {
					eb_global_product_size[size_parent] = [];
				}
				
				eb_global_product_size[ size_parent ].push( {
					name : "",
					val : ""
				});
//				console.log( eb_global_product_size[ size_parent ] );
				
//				$(this).prev().addClass('redcolor').addClass('selected');
				setTimeout(function () {
					$('.eb-admin-product-size li[data-add="1"]').prev().addClass('redcolor').addClass('selected').click();
				}, 200);
				
			}
			
			//
			eb_func_global_product_size();
		}
		
		//
		if ( a == '' ) {
			var current_select = '#' + eb_inner_html_product_size + ' li.selected';
			if ( $( current_select ).length > 0 ) {
				$('.eb-input-edit-product-size').css({
					top : $(current_select).offset().top + $(current_select).height(),
					left : $(current_select).offset().left
				}).show();
				
				//
				var a_parent = $(current_select).attr('data-parent') || 0,
					a_node = $(current_select).attr('data-node') || 0;
//				console.log( a_parent );
//				console.log( a_node );
				
				if ( typeof eb_global_product_size[ a_parent ][ a_node ] == 'undefined' ) {
					alert('Object value (node) not found');
					return false;
				}
				
				$('.eb-input-edit-product-size input[name="eb_input_edit_product_size_size"]').val( eb_global_product_size[ a_parent ][ a_node ].val );
				$('.eb-input-edit-product-size input[name="eb_input_edit_product_size_name"]').val( eb_global_product_size[ a_parent ][ a_node ].name ).focus();
				
				$('.eb-input-edit-product-size button').off('click').click(function () {
					var a = $(this).attr('data-action') || '';
					
					if ( a == 'save' ) {
						var ten = $('.eb-input-edit-product-size input[name="eb_input_edit_product_size_name"]').val() || '',
							sai = $('.eb-input-edit-product-size input[name="eb_input_edit_product_size_size"]').val() || '';
						
						eb_global_product_size[ a_parent ][ a_node ] = {
							name : ten,
							val : sai
						};
						
						//
						eb_func_global_product_size();
					}
					
					//
					$('.eb-input-edit-product-size').hide();
				});
			}
		}
	});
}



// chức năng đồng bộ nội dung website theo chuẩn chung của EchBay
function click_remove_style_of_content () {
	
	//
	$('.click_remove_content_style').click(function () {
		
		// hủy check ngay và luôn
		$(this).prop({
			checked : false
		});
		
		//
		var content_id = $(this).attr('data-editer') || 'content_ifr';
		
		// tên đầy đủ của text editter
//		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm remove all style in this content!') == false ) {
			return false;
		}
		
		// Các thẻ sẽ loại bỏ các attr gây ảnh hưởng đến style
		var arr = [
			'article',
			'font',
			'span',
			'ul',
			'ol',
			'li',
			'br',
			
			'strong',
			'blockquote',
			'b',
			'u',
			'i',
			'em',
			
			'pre',
			'code',
			'section',
			
			'table',
			'tr',
			'td',
			
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			
			'a',
			'p',
			'div'
		];
		
		//
		for ( var i = 0; i < arr.length; i++ ) {
			$( content_id ).contents().find( arr[i] ).removeAttr('style').removeAttr('id').removeAttr('face').removeAttr('dir').removeAttr('size');
		}
		
		
		// Các thẻ sẽ bị loại bỏ khỏi html
		var arr = [
			'font',
			'figure',
			'figcaption'
		];
		
		//
		for ( var i = 0; i < arr.length; i++ ) {
			$( content_id ).contents().find( arr[i] ).each(function() {
				$(this).before( $(this).html() ).remove();
			});
		}
		
		
		// xử lý riêng với IM
		$( content_id ).contents().find( 'img' ).each(function() {
			var a = $(this).attr('alt') || '';
			
			if ( a != '' ) {
				$(this).attr({
					alt : $('#title').val() || ''
				});
			}
		}).removeAttr('style').removeAttr('longdesc');
		
		
		// xử lý riêng với TABLE
		$( content_id ).contents().find( 'table' ).removeAttr('width').attr({
			border : 0,
//			width : '100%',
			cellpadding : 6,
			cellspacing : 0
		}).addClass('table-list');
		
		//
		$( content_id ).contents().find( 'table p' ).each(function () {
			$(this).before( $(this).html() ).remove();
		});
		
		//
		$( content_id ).contents().find( 'td' ).removeAttr('width');
		
		
		// loại bỏ thẻ style nếu có
//		console.log( 1 );
//		console.log( $( content_id ).contents().find( 'style' ).length );
		$( content_id ).contents().find( 'style' ).remove();
		
	});
}



// chức năng đồng bộ hình ảnh trong nội dung website theo chuẩn chung của EchBay
function click_remove_style_of_img_content () {
	
	//
	$('.click_remove_content_img_style').click(function () {
		
		// hủy check ngay và luôn
		$(this).prop({
			checked : false
		});
		
		//
		var content_id = $(this).attr('data-editer') || 'content_ifr';
		
		// tên đầy đủ của text editter
//		content_id += 'wysiwyg';
		
		//
		if ( dog( content_id ) == null ) {
			alert('Text editer #' +content_id+ ' not found');
			return false;
		}
		
		// ID cho jQuery
		content_id = '#' + content_id;
		
		//
		if ( confirm('Confirm remove image style in this content!') == false ) {
			return false;
		}
		
		//
		$( content_id ).contents().find( 'img' ).each(function() {
			var a = $(this).attr('src') || '';
			
			if ( a != '' ) {
				$(this).before( '<img src="' +a+ '" />' );
			}
			
			$(this).remove();
		});
	});
}



// Tạo ảnh đại diện mặc định nếu chưa có
function EBE_set_default_img_avt () {
	if ( dog('_eb_product_avatar') != null && dog('_eb_product_avatar').value == '' ) {
		var a = $( '#content_ifr' ).contents().find( 'img:first' ).attr('src') || '';
//		console.log(a);
		
		if ( a != '' ) {
			dog('_eb_product_avatar').value = a;
		}
	}
}



//
function EBE_set_default_title_for_seo () {
	if ( dog('postexcerpt-hide').checked == false ) {
		$('#postexcerpt-hide').click();
		if ( dog('postexcerpt-hide').checked == false ) {
			dog('postexcerpt-hide').checked = true;
		}
	}
	
	//
	var str_title = $('#title').val() || '',
		tit = '',
		str_excerpt = $('#excerpt').val() || '',
		des = '',
		des_default = '%%excerpt%%';
	
	/*
	* Yoast SEO default value
	*/
	if ( $('#snippet-editor-title').length > 0 ) {
		tit = $('#snippet-editor-title').val() || '';
		
		if ( tit == '' || tit == str_title ) {
			$('#snippet-editor-title').val( '%%title%%' );
		}
	}
	
	if ( $('#snippet-editor-meta-description').length > 0 ) {
		des = $('#snippet-editor-meta-description').val() || '';
		
//		if ( des == '' && str_excerpt != '' ) {
//			$('#snippet-editor-meta-description').val( str_excerpt );
//		}
		if ( des == '' || des == str_excerpt ) {
			$('#snippet-editor-meta-description').val( des_default );
		}
	}
	console.log(str_excerpt);
	console.log(des);
	
	//
	if ( str_excerpt == '' && des != '' && des != des_default ) {
		$('#excerpt').val( des );
	}
}



function EBE_get_current_wp_module ( s ) {
	var a = '';
	
	// chi tiết bài viết, sửa bài viết
	if ( s.split('/post.php').length > 1 ) {
		a = 'post';
	}
	// thêm bài viết mới
	else if ( s.split('/post-new.php').length > 1 ) {
		a = 'post-new';
	}
	// danh sách post, page, custom post type
	else if ( s.split('/edit.php').length > 1 ) {
		a = 'list';
	}
	// danh sách catgory, tag...
	else if ( s.split('/edit-tags.php').length > 1 ) {
		a = 'cat_list';
	}
	// chi tiết catgory, tag...
	else if ( s.split('/term.php').length > 1 ) {
		a = 'cat_details';
	}
	// thêm tài khoản thành viên
	else if ( s.split('/user-new.php').length > 1 ) {
		a = 'user-new';
	}
	// sửa tài khoản thành viên
	else if ( s.split('/user-edit.php').length > 1 ) {
		a = 'user-edit';
	}
	// không cho người dùng chỉnh sửa kích thước ảnh thumb -> để các câu lệnh dùng thumb sẽ chính xác hơn
	else if ( s.split('/options-media.php').length > 1 ) {
		a = 'media';
	}
	// chuyển rule wordpress sang nginx cho nó mượt
	else if ( s.split('/options-permalink.php').length > 1 ) {
		a = 'permalink';
	}
	// chuyển rule wordpress sang nginx cho nó mượt
	else if ( s.split('/nav-menus.php').length > 1 ) {
		a = 'menu';
	}
//	admin_act == 'menu'
	console.log(a);
	
	return a;
}




//
var time_out_for_set_new_tile = null;
function WGR_show_widget_name_by_title () {
	clearTimeout(time_out_for_set_new_tile);
	
	time_out_for_set_new_tile = setTimeout(function () {
		$('#widgets-right .widget').each(function() {
//			console.log( 'eb-get-widget-title: ' + $('input.eb-get-widget-title', this).length );
			
			if ( $('input.eb-get-widget-title', this).length > 0
			|| $('select.eb-get-widget-category', this).length > 0 ) {
				
				// lấy text để hiển thị ra
				var a = $('input.eb-get-widget-title', this).val() || '';
				
				// nếu không có -> thử lấy theo tên phân nhóm
				if ( a == '' ) {
					a = $('select.eb-get-widget-category', this).val() || 0;
					
					if ( a > 0 ) {
						a = $('select.eb-get-widget-category option[value="' + a + '"]', this).text() || '';
					}
					else {
						a = '';
					}
				}
				
				//
				if ( a != '' ) {
//					console.log( a );
					
//					console.log( 'H3: ' + $('h3 .in-widget-title', this).length );
					$('h3 .in-widget-title', this).html(': ' + a).attr({
						title: a
					});
				}
			}
		});
	}, 800);
}




function WGR_check_if_value_this_is_one ( a ) {
	if ( dog(a) != null && dog(a).value == 1 ) {
		dog(a).checked = true;
		return true;
	}
	return false;
}



function WGR_view_by_time_line ( time_lnk, time_select, private_cookie ) {
	
	//
//	console.log('test');
//	return false;
	
	//
	if ( dog('oi_quick_connect') == null ) {
		console.log('oi_quick_connect not found');
		return false;
	}
	
	//
	if (typeof time_lnk == 'undefined' || time_lnk == '') {
		console.log('time_lnk not found');
		return false;
	}
//	time_lnk += '&d=';
	
	//
	if (typeof private_cookie == 'undefined' || private_cookie == '') {
		private_cookie = 'default_cookie_name_for_time_line';
	}
	
	//
	var arr_quick_connect = {
			all: 'To\u00e0n b\u1ed9 th\u1eddi gian',
			hrs24: '24 gi\u1edd qua',
			today: 'H\u00f4m nay',
			yesterday: 'H\u00f4m qua',
			last7days: '7 ng\u00e0y qua',
			last30days: '30 ng\u00e0y qua',
			thismonth: 'Th\u00e1ng n\u00e0y',
			lastmonth: 'Th\u00e1ng tr\u01b0\u1edbc',
			custom_time: 'Tùy chỉnh'
		},
		str = '',
//		click_click_lick_lick = false,
		_get = function(p) {
			var wl = window.location.href.replace(/\&amp\;/g, '&').replace(/\?/g, '&'),
				a = wl.split('&' + p + '='),
				s = '';
			if (a.length > 1) {
				s = a[1].split('&')[0];
			}
			return s;
		},
		/*
		__hide_popup_day_select = function() {
			setTimeout(function() {
				click_click_lick_lick = false;
			}, 200);
			$('#oi_quick_connect .connect-padding').hide();
		},
		*/
		betwwen1 = _get('d1'),
		betwwen2 = _get('d2'),
		sl = '';
	
	//
	if (typeof time_select == 'undefined' || time_select == '') {
		time_select = _get('d');
		
		if (time_select == '') {
			time_select = g_func.getc(private_cookie);
			console.log(time_select);
			if ( time_select == null ) {
				time_select = '';
			}
		}
	}
//	console.log(time_select);
	
	//
	for (var x in arr_quick_connect) {
		/*
		if (x == time_select && dog('oi_time_line_name') != null) {
			dog('oi_time_line_name').value = arr_quick_connect[x];
		}
		*/
		
		//
		sl = '';
		if ( x == time_select ) {
			sl = ' selected="selected"';
		}
		
		//
//		str += '<li><a href="' + time_lnk + x + '">' + arr_quick_connect[x] + '</a></li>';
		str += '<option value="' + x + '"' + sl + '>' + arr_quick_connect[x] + '</option>';
	}
	
	//
	$('#oi_quick_connect').html( '<select>' + str + '</select>' );
	
	//
	$('#oi_quick_connect select').off('change').change(function () {
		var a = $(this).val() || '';
//		console.log(a);
		
		// nếu là custom time -> hiển thị khung chọn thời gian
		if ( a == 'custom_time' ) {
		}
		// nếu không
		else {
			// nếu là all time -> xóa cookie đi cho đỡ lằng nhằng
			if ( a == 'all' ) {
				g_func.delck(private_cookie);
			}
			// lưu cookie cho phiên này
			else {
				g_func.setc(private_cookie, a, 7 );
			}
			
			// chuyển đến link cần đến
			setTimeout(function () {
				window.location = time_lnk + '&d=' + a;
			}, 600);
		}
	});
	
	//
	return false;
	
	
	
	
	//
	if ( betwwen1 != '' && betwwen2 != '' ) {
		dog('oi_time_line_name').value = betwwen1 + ' - ' + betwwen2;
	}
	dog('oi_quick_connect').innerHTML += str;
	if (run_function && typeof run_function == 'function') run_function(arr_quick_connect);
	$('.hode-hide-popup-show-day').hover(function() {
		__hide_popup_day_select()
	});
	$('.click-how-to-hide-day-selected').click(function() {
		__hide_popup_day_select()
	});
	$('#oi_quick_connect').hover(function() {
		if (click_click_lick_lick == false) {
			click_click_lick_lick = true;
			$('#oi_quick_connect .connect-padding').show()
		}
	});
	_global_js_eb.select_date('#oi_input_value_tu_ngay', {
		numberOfMonths: 3,
		defaultDate: '-2m'
	});
	_global_js_eb.select_date('#oi_input_value_den_ngay');
	$('#oi_click_get_show_by_day').click(function() {
		var a = $('#oi_input_value_tu_ngay').val(),
			b = $('#oi_input_value_den_ngay').val();
		if (a != '') {
			if (b == '') {
				b = a
			}
			window.location = web_link + time_lnk + 'between&d1=' + a + '&d2=' + b
		} else {
			alert('Ch\u1ecdn ng\u00e0y th\u00e1ng c\u1ea7n xem')
		}
	})
}




