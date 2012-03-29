<div class="alert alert-info fade in">
	<a class="close" data-dismiss="alert">&times;</a>
	<h4 class="alert-heading"><?php echo anchor(SITE_AREA .'/content/news', 'Return to News Article Management'); ?></h4>
</div>

<div class="admin-box">
<h3><?php echo lang('us_published_articles'); ?></h3>

	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal "'); ?>

<?php if (isset($articles) && is_array($articles) && count($articles)) : ?>
		<table class="table table-striped">
			<thead>
				<tr>
						<th class="column-check"><input class="check-all" type="checkbox" /></th>
						<th style="width: 40%"><?php echo lang('us_title'); ?></th>
						<th style="width: 20%"><?php echo lang('us_author'); ?></th>
						<th style="width: 20%"><?php echo lang('us_date'); ?></th>
						<th style="width: 20%" class="text-right"><?php echo lang('us_status_change'); ?></th>
    </tr>
    </thead>

    <tbody>

				<?php foreach ($articles as $article) : ?>
    <tr>
						<td><input type="checkbox" name="checked[]" value="<?php echo $article->id ?>" /></td>
						<td><?php echo $article->title ?></td>
						<td><?php echo find_author_name ( $article->author ); /*$this->author_model->find_author ($article->author);*/ ?></td>
						<td><?php echo $article->date ? date('m/d/Y h:i:s A',$article->date) : '--' ?></td>
						<td class="text-right">
								<?php echo anchor(SITE_AREA .'/content/news/set_status/'. $article->id. '/1', lang('us_action_draft'), 'class="ajaxify"') ?> |
								<?php echo anchor(SITE_AREA .'/content/news/set_status/'. $article->id. '/32', lang('us_action_review'), 'class="ajaxify"') ?> |
								<?php echo anchor(SITE_AREA .'/content/news/set_status/'. $article->id. '/4', lang('us_action_archive'), 'class="ajaxify"') ?>
						</td>
    </tr>
				<?php endforeach; ?>
    </tbody>
</table>

<?php else : ?>
<div class="notification information">
	<p><?php echo lang('us_no_published'); ?> <?php echo anchor(SITE_AREA .'/content/news', lang('bf_go_back')) ?></p>
</div>
<?php endif; ?>
