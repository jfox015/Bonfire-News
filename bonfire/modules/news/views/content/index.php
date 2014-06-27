<div class="admin-box">
		<ul class="nav nav-tabs" >
		<li <?php echo $filter=='' ? 'class="active"' : ''; ?>><a href="<?php echo $current_url; ?>">Published Articles</a></li>
		<li <?php echo $filter=='author' ? 'class="active"' : ''; ?> class="dropdown">
			<a href="#" class="drodown-toggle" data-toggle="dropdown">
				By Author <?php echo isset($filter_author) ? ": $filter_author" : ''; ?>
				<b class="caret light-caret"></b>
			</a>
			<ul class="dropdown-menu">
			<?php if (isset($users) && is_array($users) && count($users)) :
				foreach ($users as $user_id => $display_name) : ?>
				<li>
					<a href="<?php echo $current_url .'?filter=user&user_id='. $user_id ?>">
						<?php echo $display_name; ?>
					</a>
				</li>
			<?php endforeach; 
			endif;
			?>
			</ul>
		</li>
		<li <?php echo $filter=='author' ? 'class="active"' : ''; ?> class="dropdown">
			<a href="#" class="drodown-toggle" data-toggle="dropdown">
				By Category <?php echo isset($filter_category) ? ": $filter_category" : ''; ?>
				<b class="caret light-caret"></b>
			</a>
			<ul class="dropdown-menu">
			<?php foreach ($categories as $category) : ?>
				<li>
					<a href="<?php echo $current_url .'?filter=category&category_id='. $category->id ?>">
						<?php echo $category->category; ?>
					</a>
				</li>
			<?php endforeach; ?>
			</ul>
		</li>
		<li <?php echo $filter=='draft' ? 'class="active"' : ''; ?>><a href="<?php echo $current_url .'?filter=draft'; ?>"><?php echo lang('us_action_draft') ?></a></li>
		<li <?php echo $filter=='review' ? 'class="active"' : ''; ?>><a href="<?php echo $current_url .'?filter=review'; ?>"><?php echo lang('us_action_review') ?></a></li>
		<li <?php echo $filter=='archived' ? 'class="active"' : ''; ?>><a href="<?php echo $current_url .'?filter=archived'; ?>"><?php echo lang('us_action_archived') ?></a></li>
		<li <?php echo $filter=='deleted' ? 'class="active"' : ''; ?>><a href="<?php echo $current_url .'?filter=deleted'; ?>"><?php echo lang('us_deleted_articles') ?></a></li>
		
	</ul>

	<?php echo form_open(current_url()) ;?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th class="column-check"><input class="check-all" type="checkbox" /></th>
				<th><?php echo lang('us_article_id'); ?></th>
				<th><?php echo lang('us_date'); ?></th>
				<th><?php echo lang('us_title'); ?></th>
				<th><?php echo lang('us_author'); ?></th>
				<th><?php echo lang('us_publish_date'); ?></th>
				<th><?php echo lang('us_status'); ?></th>
			</tr>
		</thead>
		<?php if (isset($articles) && is_array($articles) && count($articles)) : ?>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo lang('bf_with_selected') ?>
					<input type="submit" name="submit" class="btn btn-success" value="<?php echo lang('us_action_publish') ?>">
					<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('us_action_review') ?>">
					<input type="submit" name="submit" class="btn btn-warning" value="<?php echo lang('us_action_archive') ?>">
					<input type="submit" name="submit" class="btn btn-danger" id="delete-me" value="<?php echo lang('bf_action_delete') ?>" style="margin-bottom: 0px; " onclick="return confirm('<?php echo lang('bf_action_delete'); ?>')">
				</td>
			</tr>
		</tfoot>
		<?php endif; ?>
		<tbody>

		<?php if (isset($articles) && is_array($articles) && count($articles)) : ?>
			<?php foreach ($articles as $article) : ?>
			<tr>
				<td>
					<input type="checkbox" name="checked[]" value="<?php echo $article->id ?>" />
				</td>
				<td><?php echo $article->id ?></td>
				<td><?php
						if ($article->date != '0000-00-00 00:00:00')
						{
							echo date('m/d/Y', $article->date);
						}
						else
						{
							echo '---';
						}
					?></td>
				<td><?php echo anchor(SITE_AREA.'/content/news/edit/'. $article->id,$article->title) ?></td>
				<td><?php 
				$user = $this->user_model->find($article->author);
				if (isset($user->display_name)) :
					$author_name = $user->display_name;
				else :
					$author_name = $user->username;
				endif;
				echo($author_name); ?></td>
				<td><?php
						if ($article->date_published != '0000-00-00 00:00:00')
						{
							echo date('m/d/Y', $article->date_published);
						}
						else
						{
							echo '---';
						}
					?></td>
				<td><?php 
					$class = '';
					switch ($article->status_id)
					{
						case 1:
							$class = " label-info";
							break;
						case 2:
							$class = " label-warning";
							break;
						case 4:
							$class = "";
							break;
						case 3:
						default:
							$class = " label-success";
							break;
					}
				?>
					<span class="label<?php echo($class); ?>">
					<?php 
					if (isset($statuses) && is_array($statuses) && count($statuses)) 
					{
						foreach ($statuses as $status) 
						{
							if ($article->status_id == $status->id) 
							{
								echo($status->status);
								break;
							}
						}
					}
					?>
					</span>
				</td>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="7">No articles found that match your selection.</td>
			</tr>
		<?php endif; ?>
		</tbody>
	</table>
	<?php echo form_close(); ?>

	<?php echo $this->pagination->create_links(); ?>

</div>