<h2><?php echo lang('us_deleted_articles'); ?></h2>

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
				<th style="width: 20%"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($articles as $article) : ?>
			<tr>
				<td><?php echo $article->title ?></td>
				<td><?php echo $this->auth->username($article->author) ?></td>
				<td><?php echo $article->date ? date('m/d/Y h:i:s A',$article->date) : '--' ?></td>
				<td class="text-right">
					<?php echo anchor(SITE_AREA .'/content/news/purge/'. $article->id, lang('bf_action_purge'), 'class="ajaxify"') ?> |
					<?php echo anchor(SITE_AREA .'/content/news/restore/'. $article->id, lang('bf_action_restore'), 'class="ajaxify"') ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<br/><br/>

	<div class="box delete rounded">
		<a class="button delete ajaxify" href="<?php echo site_url(SITE_AREA .'/content/news/purge'); ?>"><?php echo lang('us_purge_del_articles'); ?></a>

		<?php echo lang('us_purge_del_note'); ?>
	</div>

<?php else : ?>
<div class="notification information">
	<p><?php echo lang('us_no_deleted'); ?> <?php echo anchor(SITE_AREA .'/content/news', lang('bf_go_back')) ?></p>
</div>
<?php endif; ?>