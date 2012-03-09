<?php if (validation_errors()) : ?>
<div class="alert alert-error alert-block fade in">
		<a class="close" data-dismiss="alert">&times;</a>
			<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<div class="alert alert-info fade in">
		<a class="close" data-dismiss="alert">&times;</a>
			<h4 class="alert-heading"><?php echo lang('bf_required_note'); ?></h4>
</div>

<div class="admin-box">
	<h3><?php echo $toolbar_title ?></h3>

<?php echo form_open_multipart($this->uri->uri_string(), 'class="form-horizontal ajax-form"'); ?>

	<fieldset>
		<legend><?php echo lang('bf_required_note') ?></legend>

		<div class="control-group <?php echo form_error('title') ? 'error' : '' ?>" >
			<label for="title"><?php echo lang('us_title') ?></label>
			<div class="controls">
				<input class="span6" type="text" name="title" id="title" value="<?php echo isset($article) ? $article->title : set_value('title') ?>" />
				<?php if (form_error('title')) echo '<span class="help-inline">'. form_error('title') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('us_date') ? 'error' : '' ?>">
			<label for="date"><?php echo lang('us_date') ?></label>
			<div class="controls">
				<input class="span6" type="text" name="date" id="date" value="<?php echo ( isset($article) && !empty($article->date) ) ? date('m/d/Y',strtotime($article->date)) : set_value(date('m/d/Y',time() )) ?>" />
				<?php if (form_error('date')) echo '<span class="help-inline">'. form_error('date') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('us_body') ? 'error' : '' ?>" for="body">
			<label for="body"><?php echo lang('us_body') ?></label>
			<div class="controls">
				<?php echo form_textarea( array( 'name' => 'body', 'class'=> 'span8', 'id' => 'body', 'rows' => '5', 'cols' => '80', 'value' => isset($article) ? $article->body : set_value('body') ) )?>
				<?php if (form_error('body')) echo '<span class="help-inline">'. form_error('body') .'</span>'; ?>
			</div>
		</div>


	</fieldset>
		<fieldset>
		<legend>Image Information</legend>

		<!-- ATTACH IMAGE -->
		<div class="control-group">
			<label for="attachment"><?php echo lang('us_image_path') ?></label>
			<div class="controls">
				<input type="file" id="attachment" name="attachment" /><br />

				<?php if(isset($article) && isset($article->attachment) && !empty($article->attachment)) :
						$attachment = (array) unserialize($article->attachment);
				?>

					<span class="help-block">Current Attachment: <?php echo $attachment['file_name']." (".$attachment['file_size']."kB ".$attachment['file_type'].") | ".anchor(SITE_AREA.'/content/news/remove_attachment/'.$article->id,'Remove'); ?> </span>
				<?php endif; ?>
			</div>
		</div>


		<!-- IMAGE CAPTION -->

		<div class="control-group <?php echo form_error('image_caption') ? 'error' : '' ?>">
			<label for="image_caption"><?php echo lang('us_image_caption') ?></label>
			<div class="controls">
				<input class="span6" type="text" name="image_caption" id="image_caption" value="<?php echo isset($article) ? $article->image_caption : set_value('image_caption') ?>" />
				<?php if (form_error('image_caption')) echo '<span class="help-inline">'. form_error('image_caption') .'</span>'; ?>
			</div>
		</div>

		<?php
				$alignments = array(-1=>'',1=>lang('us_image_align_left'),2=>lang('us_image_align_right'));
		?>

		<!-- IMAGE ALIGNMENT -->
		<div class="control-group <?php echo form_error('image_align') ? 'error' : '' ?>">
			<label for="image_align"><?php echo lang('us_image_align') ?></label>
			<div class="controls">
				<select name="image_align">
				<?php foreach ($alignments as $id => $label) :?>
							<option value="<?php echo $id; ?>" <?php echo (isset($article) ? ($id == $article->image_align) ? 'selected="selected"' : '' : ''); ?>><?php echo $label ?></option>
				<?php endforeach; ?>
				</select>
				<?php if (form_error('image_align')) echo '<span class="help-inline">'. form_error('image_align') .'</span>'; ?>
			</div>
		</div>


	</fieldset>
		<fieldset>
		<legend>Tags and Author</legend>

		<!-- TAGS -->
		<div class="control-group <?php echo form_error('tags') ? 'error' : '' ?>">
			<label for="tags"><?php echo lang('us_tags') ?></label>
			<div class="controls">
				<input class="span8" type="text" id="tags" name="tags" value="<?php echo isset($article) ? $article->tags : set_value('tags') ?>" />
				<?php if (form_error('tags')) echo '<span class="help-inline">'. form_error('tags') .'</span>'; ?>
			</div>
		</div>

		<?php
/*
		<?php  if ( has_permission('Site.News.Manage') ) :?>
		<div class="control-group <?php echo form_error('author') ? 'error' : '' ?>">
			<label for="author"><?php echo lang('us_author') ?></label>
			<div class="controls">
			<?php if (isset($users) && is_array($users) && count($users)) : ?>
				<select class="span6" name="author" id="author">
				<?php foreach ($users as $user) :?>
					<option value="<?php echo (int)$user->id; ?>" <?php echo (isset($user) ? ((int)$user->id == $article->author) ? 'selected="selected"' : '' : ''); ?>><?php echo $user->username ?></option>
				<?php endforeach; ?>
				</select>
			<?php endif; ?>
		<?php else :
			echo $article->author_name;
			?>
			</div>
		</div>
<?php 		endif; ?>
*/
?>
	</fieldset>

	<?php  if ( has_permission('Site.News.Manage') ) :?>
	<fieldset>
		<legend><?php echo lang('us_additional'); ?></legend>

		<div class="control-group <?php echo form_error('category_id') ? 'error' : '' ?>">
			<label for="category_id"><?php echo lang('us_category') ?></label>
			<div class="controls">
			<?php if (is_array($categories) && count($categories)) : ?>

						<select class="span6" id="category_id" name="category_id">
				<?php foreach ($categories as $category) :?>

							<option value="<?php echo (int)$category->id; ?>" <?php echo (isset($article) ? ((int)$category->id == $article->category_id) ? 'selected="selected"' : '' : ''); ?>><?php echo $category->category ?></option>

				<?php endforeach; ?>

						</select>

				<?php endif; ?>

				<?php if (form_error('category_id')) echo '<span class="help-inline">'. form_error('category_id') .'</span>'; ?>
			</div>
		</div>


		<div class="control-group <?php echo form_error('status_id') ? 'error' : '' ?>">
			<label for="status_id"><?php echo lang('us_status') ?></label>
			<div class="controls">
			<?php if (is_array($statuses) && count($statuses)) : ?>

						<select class="span6" id="status_id" name="status_id">
				<?php foreach ($statuses as $status) :?>

							<option value="<?php echo (int)$status->id; ?>" <?php echo (isset($article) ? ((int)$status->id == $article->status_id) ? 'selected="selected"' : '' : ''); ?>><?php echo $status->status ?></option>

				<?php endforeach; ?>

						</select>

				<?php endif; ?>

				<?php if (form_error('status_id')) echo '<span class="help-inline">'. form_error('status_id') .'</span>'; ?>
			</div>
		</div>


		<!-- DATE PUBLISHED -->
		<div class="control-group <?php echo form_error('date_published') ? 'error' : '' ?>">
			<label for="date_published"><?php echo lang('us_publish_date') ?></label>
			<div class="controls">
				<input class="span3" type="text" name="date_published" id="date_published" value="<?php echo isset($article) ? date('m/d/Y',$article->date_published) : set_value(date('m/d/Y',time() )) ?>" />
				<?php if (form_error('date_published')) echo '<span class="help-inline">'. form_error('date_published') .'</span>'; ?>
			</div>
		</div>

		<?php if (isset($article) && isset($article->id)) { ?>

		<div class="control-group">
			<label for="date_published"><?php echo lang('us_created') ?></label>
			<div class="controls">
				<span><?php echo (isset($article) ? date('m/d/Y h:i:s A',$article->created_on) : 'Unknown'); ?> by <?php echo (isset($article) ? $this->current_user->username /*($article->created_by)*/ : 'Unknown'); ?></span>
			</div>
		</div>

		<div class="control-group">
			<label for="date_published"><?php echo lang('us_modified') ?></label>
			<div class="controls">
			<span><?php echo (isset($article) ? date('m/d/Y h:i:s A',$article->modified_on) : 'Unknown'); ?> by <?php echo (isset($article) ? $this->current_user->username /*($article->modified_by)*/ : 'Unknown'); ?></span>
			</div>
		</div>

		<?php } ?>

	<?php endif; ?>


		<div class="form-actions">
			<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_save') ?>" /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/content/news', lang('bf_action_cancel'), 'class="btn btn-danger"'); ?>
		</div>

			</fieldset>

	<?php if (isset($article) && has_permission('Site.News.Manage')) : ?>
	<div class="box delete rounded">
		<a class="button" id="delete-me" href="<?php echo site_url(SITE_AREA .'/content/news/delete/'. $article->id); ?>" onclick="return confirm('<?php echo lang('us_delete_article_confirm'); ?>')"><?php echo lang('us_delete_article'); ?></a>

		<?php echo lang('us_delete_article_note'); ?>
	</div>
	<?php endif; ?>

<?php echo form_close(); ?>

<script type="text/javascript">
head.ready(function(){
    $("#date, #date_published").datepicker();
});

</script>
