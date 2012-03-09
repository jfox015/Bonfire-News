<h2><?php echo lang('us_draft_articles'); ?></h2>

<div class="text-right">
	<?php echo anchor(SITE_AREA .'/content/news', 'Return to News Article Management'); ?>
</div>

<?php if (isset($articles) && is_array($articles) && count($articles)) : ?>

	<table cellspacing="0">
		<thead>
			<tr>
				<th style="width: 40%"><?php echo lang('us_title'); ?></th>
				<th style="width: 20%"><?php echo lang('us_author'); ?></th>
				<th style="width: 20%"><?php echo lang('us_date'); ?></th>
				<th style="width: 20%" class="text-right"><?php echo lang('us_status_change'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($articles as $article) : ?>
			<tr>
				<td><?php echo $article->title ?></td>
						<td><?php echo $current_user->username; //($article->author) ?></td>
						<td><?php echo $article->date ? date('m/d/Y h:i:s A',$article->date) : '--' ?></td>
						<td class="text-right">
					<?php echo anchor(SITE_AREA .'/content/news/set_status/'. $article->id. '/2', lang('us_action_review'), 'class="ajaxify"') ?> |
					<?php echo anchor(SITE_AREA .'/content/news/set_status/'. $article->id. '/3', lang('us_action_publish'), 'class="ajaxify"') ?> |
					<?php echo anchor(SITE_AREA .'/content/news/set_status/'. $article->id. '/4', lang('us_action_archive'), 'class="ajaxify"') ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

<?php else : ?>
<div class="notification information">
	<p><?php echo lang('us_no_drafts'); ?> <?php echo anchor(SITE_AREA .'/content/news', lang('bf_go_back')) ?></p>
</div>
<?php endif; ?>
