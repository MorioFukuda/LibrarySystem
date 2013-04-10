$(function(){

	//--------------------------------------
	// 共通
	//--------------------------------------
	$("img.rollover")
		.mouseover(function(){
			$(this).attr("src", $(this).attr("src").replace("_1", "_2"));
		})
		.mouseout(function(){
			$(this).attr("src", $(this).attr("src").replace("_2", "_1"));
		}
	);

	$('img#loading').bind("ajaxSend", function(){
		$(this).fadeIn();
	}).bind("ajaxComplete", function(){
		$(this).fadeOut();
	});
 
	$(".js-tooltip").popup({
		trigger: 'hover',
		animation: 'growUp',
		animationDuration: 400,
		theme: 'dark',
		placement: 'top',
		offset: [-30, 0],
		template: ' <div class="-tooltip _huge" style="padding:20px 40px;"> <i class="-arrow"></i> <div class="js-content"></div> </div>'
	});


	//--------------------------------------
	// 本棚一括変更ページ
	//--------------------------------------
	$('input#search_box').change(function(){
		query = $(this).val();

		if(query !== ''){
			$('select#candidate_list > option').remove();
			$(this).val('');

			$.ajax({
				url: '/book/getBookList?query=' + query,
				type: 'GET',
				dataType: 'json',
				cache : false,
				beforeSend:function(){},
				complete: function(){},

				success: function(response){
					$.each(response.bookList, function(i, book){
						$('select#candidate_list').append('<option value="' + book.id + '" selected>【' + book.shelf_name + '】' + book.title + '</option>');
					});
				},

				error: function(){
					alert('接続に失敗しました。');
				}
			});
		}
	});

	$('input#add_list').click(function(){
		$.each($('select#candidate_list > option:selected'), function(){
			id = $(this).val();
			title = $(this).text();

			// 既にリストに追加してあるものは追加しない
			canAdd = true;
			$.each($('form > input[type="hidden"]'), function(){
				if($(this).val() == id){
					canAdd =false;

					return false;	// $.eachのbreak
				}
			});

			if(canAdd){
				$('select#book_list').append('<option value="' + id +'" selected>' + title + '</option>');
				$('form').append('<input type="hidden" name="book_id[]" value="' + id + '" />');
			}

		});
	});

	$('input#remove_list').click(function(){
		$.each($('select#book_list > option:selected'), function(){
			id = $(this).val();
			$('form > input[value="' + id + '"]').remove();
			$(this).remove();
		});
	});

	//--------------------------------------
	// ISBN書籍登録ページ
	//--------------------------------------
	$('input.delete').click(function(){
		// 削除するhidden要素の番号を取得
		num = $(this).attr('name');

		// サムネイルを削除
		$(this).parents('div.book_thumbnail').remove();

		// nameにnumを含む全てのhidden要素を削除
		$('input[name*="' + num + '"]').remove();
	});
					
	$('input.isbn').change(function(){
		isbnForm = $(this);

		// 入力されたISBNを取得
		isbn = isbnForm.val();
		isbn = isbn.replace(/[Ａ-Ｚａ-ｚ０-９]/g,function(s){return String.fromCharCode(s.charCodeAt(0)-0xFEE0)});
		isbnForm.val(isbn);

		$.ajax({
			url: '/book/getBookData/' + isbn,
			type: "GET",
			dataType: 'json',

			beforeSend: function(){
				$('div#book_list').prepend(''
					+	'<div class="book_thumbnail effect8">'
					+ '<img src="/img/common/loading.gif" alt="loadgin..." id="loading" />'
					+ '</div>'
				);
				$('img#loading:first').fadeIn(200);

				// 通信が終わるまでフォームを入力できないようにする
				isbnForm.attr('disabled', 'disabled');
				$('input#book_submit').attr('disabled', 'disabled');
			},

			complete: function(){
				// ローディングアニメーションを非表示に
				$('img#loading').fadeOut();

				// 通信が終わったらフォームをクリアして、入力できるようにする
				isbnForm.val('');
				isbnForm.removeAttr('disabled');
				$('input#book_submit').removeAttr('disabled');
				isbnForm.focus();

			},

			success: function(book) {
				if(book.result == false){
					// サーバー側でエラーを返したらアラートで表示
					// バリデーションエラー、該当ISBN無し、通信エラー
					alert(book.error);
					$('div.book_thumbnail:first').fadeOut();
					$('div.book_thumbnail:first').remove();
				}else{
					// ローディングアニメーションを非表示に
					$('img#loading').fadeOut();

					// hidden要素の名前用カウンタ
					counter = parseInt($('input#counter').val());

					// サムネイルを追加する
					$('div.book_thumbnail:first').prepend(''
						+ 	'<img src="' + book.imageUrl + '" class="book_image" onclick="TINY.box.show({image: "' + book.imageUrl + '", maskid: "tinymask", boxid: "frameless", animate: false, })" />'
						+ 	'<div class="book_detail">'
						+ 		'<dl>'
						+ 			'<dt>収録棚<dt>'
						+ 				'<dd><span class="shelf_name">ー</span></dd>'
						+				'<dt>著者</dt>'
						+					'<dd><span class="book_author">' + book.author + '</span></dd>'
						+				'<dt>タイトル</dt>'
						+					'<dd><span class="book_title"><a href="' + book.amazonUrl + '" target="_blank">' + book.title + '</a></span></dd>'
						+			'</dl>'
						+ 		'<input type="button" class="delete" name="' + counter + '" value="削除">'
						+		'</div>'
						+ 	'<div class="clearfix"></div>'
					);

					// hidden要素に本の情報を格納
					$('form#book_data').prepend(''
						+ '<input type="hidden" name="book_list[' + counter + '][isbn]" value="' + isbn +'">'
						+ '<input type="hidden" name="book_list[' + counter + '][title]" value="' + book.title +'">'
						+ '<input type="hidden" name="book_list[' + counter + '][author]" value="' + book.author +'">'
						+ '<input type="hidden" name="book_list[' + counter + '][image_url]" value="' + book.imageUrl +'">'
						+ '<input type="hidden" name="book_list[' + counter + '][amazon_url]" value="' + book.amazonUrl +'">'
					);

					$('input#counter').val(counter + 1);

					// 新たに追加された削除ボタンにも削除の機能をつける
					$('input.delete').click(function(){
						// 削除するhidden要素の番号を取得
						num = $(this).attr('name');

						// サムネイルを削除
						$(this).parents('div.book_thumbnail').remove();

						// nameにnumを含む全てのhidden要素を削除
						$('input[name*="' + num + '"]').remove();
					});
				}
			},
			error: function(){
					$('div.book_thumbnail:first').fadeOut();
					$('div.book_thumbnail:first').remove();
				alert('通信に失敗しました。もう一度入力してください。');
			}
		});

		$('input.delete').click(function(){
			// 削除するhidden要素の番号を取得
			num = $(this).attr('name');

			// サムネイルを削除
			$(this).parent().remove();

			// nameにnumを含む全てのhidden要素を削除
			$('input[name*="' + num + '"]').remove();
		});
	});


});
