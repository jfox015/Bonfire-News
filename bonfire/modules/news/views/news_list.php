		<!-- Begin Articles -->
	<div class="widget">
        <h3>Recent News</h3>
        <ul class="articles">
		<?php if (isset($articles) && is_array($articles) && count($articles)) { 
            foreach($articles as $article) {
                echo ('<li>'.anchor('/news/article/'.$article->id,$article->title).'</li>');
            }
        ?>
        </ul>
        <?php
        } else { ?>
		<p>No articles were found.</p>
		<?php } ?>
	</div>
		<!-- End Articles -->
		