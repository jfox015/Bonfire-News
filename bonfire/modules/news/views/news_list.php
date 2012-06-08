		<!-- Begin Articles -->
	<div class="widget">
        <h3><?php echo lang('us_recent_news'); ?></h3>
        <ul class="articles">
		<?php if (isset($articles) && is_array($articles) && count($articles)) { 
            foreach($articles as $article) {
                echo ('<li>'.anchor('/news/article/'.$article->id,$article->title).'</li>');
            }
        ?>
        </ul>
        <?php
        } else { ?>
		<p><?php echo lang('us_no_articles'); ?></p>
		<?php } ?>
	</div>
		<!-- End Articles -->
		