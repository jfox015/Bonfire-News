<?php if (isset($article)) : ?>
<!-- Begin Article -->
	<div class="container-fluid">

		<div class="row-fluid rowbg content">
		  <div class="span12">

			<div class="page-header" style="padding-top: 25px;">
					<h1><?php echo $article->title; ?>
						<small id="article_date" class="news_date"><?php echo(date('m/d/Y',intval($article->date))); ?></small>
					</h1>
				</div>

			<div id="article">

	        <div id="body" class="news_body well well-large">
<?php
		if (isset($article->attachment) && !empty($article->attachment)) :
			$attachment = unserialize($article->attachment);
				// IMAGE ALIGNMENT CHECK
				$alignClass = '';
				if ( isset ( $article->image_align ) && $article->image_align != -1 ) :

					switch ($article->image_align)
					{
						case 1:
							$alignClass = ' alignLeft';
							break;
						case 2:
							$alignClass = ' alignRight';
							break;
					} // END switch

				endif;
?>

				<div id="image" class="news_image<?php echo($alignClass); ?>">
				<img src="<?php echo base_url(); if (isset($attachment['image_thumb']) && !empty($attachment['image_thumb'])) { echo($article->asset_url.$attachment['image_thumb']); } else { echo($article->asset_url.$attachment['file_name']); } ?>" alt="" title="" />
				<?php 
				// IMAGE CAPTION CHECK
				if (isset($article->image_caption) && !empty($article->image_caption)) : ?>
					<br /><span class="caption"><?php echo($article->image_caption); ?></span>

				<?php endif; ?>
				</div>
		<?php endif;  ?>
		
		<?php echo $article->body ?>

		</div>
		
		<?php if ( isset ( $article->tags ) ) : ?>
		<div id="tags" class="tags"><?php echo $article->tags ?></div>
		<?php endif;  ?>
			<?php
			/*

			<div id="author" class="author"><?php echo(anchor('users/profile/'.$article->author,((isset($article->author_name))? $article->author_name: 'Unnamed Author'))); ?></div>
*/
			?>
		<?php else : ?>
		<p><?php echo lang('us_no_articles_found'); ?></p>
		<?php endif; ?>

	</div>
</div>
</div>

	</div>
		<!-- End Article -->
