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
				url: 'http://staff.oasis.local/book/getBookList?query=' + query,
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
	$('input.isbn').change(function(){
		isbnForm = $(this);

		// 入力されたISBNを取得
		isbn = isbnForm.val();

		$.ajax({
			url: 'http://staff.oasis.local/book/getBookData/' + isbn,
			type: "GET",
			dataType: 'json',

			beforeSend: function(){
				// ローディングアニメーションの表示
				$('img#loading').fadeIn(200);

				// 通信が終わるまでフォームを入力できないようにする
				isbnForm.attr('disabled', 'disabled');
				$('input#book_submit').attr('disabled', 'disabled');
			},

			complete: function(){
				// ローディングアニメーションを非表示に
				$('img#loadgin').fadeOut();

				// 通信が終わったらフォームをクリアして、入力できるようにする
				isbnForm.val('');
				isbnForm.removeAttr('disabled');
				$('input#book_submit').removeAttr('disabled');
			},

			success: function(book) {
				if(book.result == false){
					// サーバー側でエラーを返したらアラートで表示
					// バリデーションエラー、該当ISBN無し、通信エラー
					alert(book.error);
				}else{

					// hidden要素の名前用カウンタ
					counter = parseInt($('input#counter').val());

					// サムネイルを追加する
					$('div#book_list').prepend(''
						+ '<div class="book_thumbnail" style="display:none;">'
						+ 	'<span class="book_title"><a href="' + book.amazonUrl + '" target="_blank">' + book.title + '</a></span><br />'
						+		'<span class="book_author">' + book.author + '</span><br />'
						+		'<img src="' + book.imageUrl + '" class="book_image"/><br />'
						+ 	'<input type="button" class="delete" name="' + counter + '" value="削除">'
						+ '</div>'
					);
					$('div.book_thumbnail:hidden').fadeIn();

					// hidden要素に本の情報を格納
					$('form#book_data').prepend(''
						+ '<input type="hidden" name="book_list[' + counter + '][isbn]" value="' + isbn +'">'
						+ '<input type="hidden" name="book_list[' + counter + '][title]" value="' + book.title +'">'
						+ '<input type="hidden" name="book_list[' + counter + '][author]" value="' + book.author +'">'
						+ '<input type="hidden" name="book_list[' + counter + '][image_url]" value="' + book.imageUrl +'">'
						+ '<input type="hidden" name="book_list[' + counter + '][amazon_url]" value="' + book.amazonUrl +'">'
					);

					$('input#counter').val(counter + 1);

					$('input.delete').click(function(){
						// 削除するhidden要素の番号を取得
						num = $(this).attr('name');

						// サムネイルを削除
						$(this).parent().remove();

						// nameにnumを含む全てのhidden要素を削除
						$('input[name*="' + num + '"]').remove();
					});
				}
			},
			error: function(){
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
