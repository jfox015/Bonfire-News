/*
	News.js
	
	Most of the functions in this file respond to actions published 
	through the UI.js functions.
*/
$.subscribe('list-view/list-item/click', function(article_id) {
	$('#content').load('<?php echo site_url(SITE_AREA .'/content/news/edit/') ?>/'+ article_id, function(response, status, xhr){
		if (status != 'error')
		{
			
		}
	});
	
	
});

/*
	Category Filter
*/
$('#category-filter').change(function(){
	
	var category = $(this).val();
	
	$('#article-list .list-item').css('display', 'block');
	
	if (category != '0')
	{
		$('#article-list .list-item[data-category!="'+ category +'"]').css('display', 'none');
	} 
});