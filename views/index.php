		<!-- Header -->
		<div id="article">
			<div id="title" class="news_title"><?php echo($article->title); ?></div>
			
			<div id="article_date" class="news_date"><?php echo(date('m/d/Y h:i:s A',$article->date)); ?></div>
			
			<?php if (isset($article->image_path) && !empty($article->image_path)) : ?>
				<div id="image" class="news_image"><img src="<?php echo(Template::theme_url('media/uploads/news/'.$article->image_path)); ?>" /></div>
			<?php endif; ?>
			
			<div id="body" class="news_body"><?php echo($article->body); ?></div>
			
			<div id="author" class="author"><?php echo(anchor('users/profile/'.$article->author,$article->author_name)); ?></div>
		</div>