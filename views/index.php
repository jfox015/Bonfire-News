		<!-- Begin Article -->
	<div id="article">
		<?php if (isset($article)) { ?>
			<!-- Header -->
		<div id="title"><h1><?php echo($article->title); ?></h1></div>
		
		<div id="article_date" class="news_date"><?php echo(date('m/d/Y h:i:s A',$article->date)); ?></div>
		
		<?php if (isset($article->attachment) && !empty($article->attachment)) : 
			$attachment = unserialize($article->attachment);
				// IMAGE ALIGNMENT CHECK
				$alignClass = '';
				if(isset($article->image_align) && $article->image_align != -1) {
					switch ($article->image_align) {
						case 1:
							$alignClass = ' alignLeft';
							break;
						case 2:
							$alignClass = ' alignRight';
							break;
					} // END switch
				} // END if
			?>
			<div id="image" class="news_image<?php echo($alignClass); ?>">
			<img src="<?php echo($attachment['data']['file_path'].$attachment['data']['file_name']); ?>" width="<?php echo($attachment['image_width']); ?>" height="<?php echo($attachment['image_height']); ?>" alt="" title="" />
			<?php 
			// IMAGE CAPTION CHECK
			if (isset($article->image_caption) && !empty($article->image_caption)) { ?>
				<br /><span class="caption"><?php echo($article->image_caption); ?></span>
			</div>
			<?php } ?>
		<?php endif; ?>
		
		<div id="body" class="news_body"><?php echo($article->body); ?></div>
		
		<div id="author" class="author"><?php echo(anchor('users/profile/'.$article->author,((isset($article->author_name))? $article->author_name: 'Unnamed Author'))); ?></div>
		
		<?php } else { ?>
		<p>No article content was found.</p>
		<?php } ?>
	</div>
		<!-- End Article -->
		