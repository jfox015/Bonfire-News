		<?php if (isset($article)) { ?>
		<!-- Header -->
		<div id="article">
			<div id="title" class="news_title"><?php echo($article->title); ?></div>
			
			<div id="article_date" class="news_date"><?php echo(date('m/d/Y h:i:s A',$article->date)); ?></div>
			
			<?php if (isset($article->attachment) && !empty($article->attachment)) : 
				$attachment = unserialize($article->attachment);
				if ($attachment['is_image'] == 1) :
				?>
				<div id="image" class="news_image"><img src="<?php echo($settings['upload_dir_url'].$attachment['file_name'])); ?>" width="<?php echo($attachment['image_width']); ?>" height="<?php echo($attachment['image_height']); ?>" alt="" title="" /></div>
				<?php endif; ?>
			<?php endif; ?>
			
			<div id="body" class="news_body"><?php echo($article->body); ?></div>
			
			<div id="author" class="author"><?php echo(anchor('users/profile/'.$article->author,$article->author_name)); ?></div>
		</div>
		<?php } else { ?>
		no article content was found.
		<?php } ?>