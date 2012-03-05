<div class="view split-view">
	<!-- Articles List -->
	<div class="view">

		<div class="panel-header list-search">

			<select id="category-filter" style="display: inline-block; max-width: 40%;">
				<option value="0"><?php echo lang('bf_action_show') .' '. lang('us_category'); ?>...</option>
			<?php foreach ($categories as $category) : ?>
				<option value="<?php echo $category->id ?>"><?php echo $category->category ?></option>
			<?php endforeach; ?>
			</select>

			<?php render_search_box(); ?>
		</div>

		<?php if (isset($articles) && is_array($articles)) : ?>

		<div class="scrollable">
			<div class="list-view" id="article-list">
			<?php foreach ($articles as $article) : ?>
				<div class="list-item with-icon" data-id="<?php echo $article->id ?>" data-category="<?php echo $article->category ?>">
					<?php echo '<img src="'.Template::theme_url('images/news.png').'" />'; ?>

					<p>
						<b><?php echo $article->title; ?></b><br/>
						<span><?php echo date('m/d/Y', $article->date); ?></span>
					</p>
				</div>
			<?php endforeach; ?>
			</div>	<!-- /list -->
		</div>

		<?php else : ?>

			<div class="notification information">
				<p><?php echo lang('no_articles'); ?></p>
			</div>

		<?php endif; ?>
	</div>	<!-- /articles-list -->

	<!-- Article Editor -->
	<div id="content" class="view">
		<div class="scrollable" id="ajax-content">
			<div class="padded">

				<div class="row" style="margin-bottom: 2.5em">
					<div class="column size1of2">
						<img src="<?php echo Template::theme_url('images/news.png') ?>" style="vertical-align: bottom; position: relative; top: -5px; margin-right: 1em;" />

						<span class="big-text"><b><?php echo $article_count ?></b></span> &nbsp; Total Articles
					</div>
                    <div class="column size1of2 last-column">
                        <img src="<?php echo Template::theme_url('images/news.png') ?>" style="vertical-align: bottom; position: relative; top: -5px; margin-right: 1em;" />

                        <span class="big-text"><b><?php echo $draft_articles ?></b></span> &nbsp; <?php echo anchor(SITE_AREA .'/content/news/drafts', 'Draft Articles', 'class="ajaxify"') ?>
                    </div>
					<div class="column size1of2 last-column">
						<img src="<?php echo Template::theme_url('images/news.png') ?>" style="vertical-align: bottom; position: relative; top: -5px; margin-right: 1em;" />

						<span class="big-text"><b><?php echo $published_articles ?></b></span> &nbsp; <?php echo anchor(SITE_AREA .'/content/news/published', 'Published Articles', 'class="ajaxify"') ?>
					</div>

					<div class="column size1of2 last-column">
						<img src="<?php echo Template::theme_url('images/news.png') ?>" style="vertical-align: bottom; position: relative; top: -5px; margin-right: 1em;" />

						<span class="big-text"><b><?php echo $deleted_articles ?></b></span> &nbsp; <?php echo anchor(SITE_AREA .'/content/news/deleted', 'Deleted Articles', 'class="ajaxify"') ?>
					</div>
				</div>


				<div class="box create rounded">
					<a class="button good ajaxify" href="<?php echo site_url(SITE_AREA .'/content/news/create'); ?>"><?php echo lang('us_create_news'); ?></a>

					<?php echo lang('us_create_news_note'); ?>
				</div>

                <div class="box create rounded">
                    <a class="button good ajaxify" href="<?php echo site_url(SITE_AREA .'/settings/news/index'); ?>"><?php echo lang('us_news_options'); ?></a>

                    <?php echo lang('us_news_options_note'); ?>
                </div>


			</div>	<!-- /inner -->
		</div>	<!-- /scrollable -->
	</div>	<!-- /content -->
</div> <!-- /v-split -->