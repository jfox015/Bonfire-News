<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<div class="admin-box">

    <h3>Article Details</h3>

    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

    <fieldset>
        <legend>General Information</legend>
			<!-- Title -->
        <div class="control-group <?php echo form_error('title') ? 'error' : '' ?>">
            <label><?php echo lang('us_title') ?></label>
            <div class="controls">
                <input type="text" name="title" id="title" value="<?php echo isset($article) ? $article->title : set_value('title') ?>" />
				<?php if (form_error('title')) echo '<span class="help-inline">'. form_error('title') .'</span>'; ?>
            </div>
        </div>

			<!-- Date -->
        <div class="control-group <?php echo form_error('date') ? 'error' : '' ?>">
            <label><?php echo lang('us_date') ?></label>
            <div class="controls">
                <input type="text" name="date" id="date" value="<?php echo (isset($article) && isset($article->date) && !empty($article->date)) ? date('m/d/Y',$article->date) : (isset($this->input->post('date')) ? set_value(date('m/d/Y','date')) : '') ?>" />
				<?php if (form_error('date')) echo '<span class="help-inline">'. form_error('date') .'</span>'; ?>
            </div>
        </div>

			<!-- Body -->
        <div class="control-group <?php echo form_error('body') ? 'error' : '' ?>">
            <label><?php echo lang('us_body') ?></label>
            <div class="controls">
                <?php echo form_textarea( array( 'name' => 'body', 'id' => 'body', 'rows' => '5', 'cols' => '80', 'value' => isset($article) ? $article->body : set_value('body') ) )?>
				<?php if (form_error('body')) echo '<span class="help-inline">'. form_error('body') .'</span>'; ?>
            </div>
        </div>
		
	
		<?php 
		// ATTACHMENTS
		if (isset($settings['news.allow_attachments']) && $settings['news.allow_attachments'] == 1) 
		{
		?>
			<!-- Image Upload -->
		<div class="control-group <?php echo form_error('attachment') ? 'error' : '' ?>">
            <label><?php echo lang('us_image_path') ?></label>
            <div class="controls">
                <input type="file" id="attachment" name="attachment" />
				<span class="help-inline"><?php if (form_error('attachment')) 
				{
					echo form_error('attachment'); 
				}
				else
				{ 
					if(isset($article) && isset($article->attachment) && !empty($article->attachment)) : 
						$attachment = unserialize($article->attachment);
						echo 'Current Attachment: '.$attachment['file_name']." (".$attachment['file_size']."kB ".$attachment['file_type'].") | ".anchor(SITE_AREA.'/content/news/remove_attachment/'.$article->id,'Remove');
					endif; 
				} // END if
				?>				
				</span>
			</div>
        </div>

			<!-- IMAGE CAPTION -->
		<div class="control-group <?php echo form_error('image_caption') ? 'error' : '' ?>">
            <label><?php echo lang('us_image_caption') ?></label>
            <div class="controls">
                <input type="text" name="image_caption" id="image_caption" value="<?php echo isset($article) ? $article->image_caption : set_value('image_caption') ?>" />
				<?php if (form_error('image_caption')) echo '<span class="help-inline">'. form_error('image_caption') .'</span>'; ?>
            </div>
        </div>

		<!-- IMAGE ALIGNMENT -->
		<div class="control-group <?php echo form_error('image_align') ? 'error' : '' ?>">
            <label><?php echo lang('us_image_align') ?></label>
            <div class="controls">
                <?php 
				$alignments = array(-1=>'',1=>lang('us_image_align_left'),2=>lang('us_image_align_right')); ?>
				<select name="image_align">
				<?php foreach ($alignments as $id => $label) :?>
					<option value="<?php echo $id; ?>" <?php echo (isset($article) ? ($id == $article->image_align) ? 'selected="selected"' : '' : ''); ?>><?php echo $label ?></option>
				<?php endforeach; ?>
				</select>
				<?php if (form_error('image_align')) echo '<span class="help-inline">'. form_error('image_align') .'</span>'; ?>
            </div>
        </div>

	<?php
	} // END if
	?>
		<!-- TAGS -->
	<div class="control-group <?php echo form_error('tags') ? 'error' : '' ?>">
		<label><?php echo lang('us_tags') ?></label>
		<div class="controls">
			<input type="text" id="tags" name="tags" value="<?php echo isset($article) ? $article->tags : set_value('tags') ?>" />
			<?php if (form_error('tags')) echo '<span class="help-inline">'. form_error('tags') .'</span>'; ?>
		</div>
	</div>
		
		<!-- AUTHOR -->
	<div class="control-group <?php echo form_error('author') ? 'error' : '' ?>">
		<label><?php echo lang('us_author') ?></label>
		<div class="controls">
			<?php  if ( has_permission('Site.News.Manage') ) :?>
				<?php if (isset($users) && is_array($users) && count($users)) : ?>
					<select name="author" id="author">
					<?php foreach ($users as $user) :?>
						<option value="<?php echo (int)$user->id; ?>" <?php echo (isset($user) ? ((int)$user->id == $article->author) ? 'selected="selected"' : '' : ''); ?>><?php echo $user->username ?></option>
					<?php endforeach; ?>
					</select>
				<?php endif; ?>
				<?php if (form_error('author')) echo '<span class="help-inline">'. form_error('author') .'</span>'; ?>
			<?php else : 
				echo $article->author_name;
			endif; ?>
		</div>
	</div>
	
	<?php  if ( has_permission('Site.News.Manage') ) :?>
	<fieldset>
		<legend><?php echo lang('us_additional'); ?></legend>

		<div class="control-group <?php echo form_error('category_id') ? 'error' : '' ?>">
			<label><?php echo lang('us_category') ?></label>
			<div class="controls">
				<?php if (is_array($categories) && count($categories)) : ?>
					<select name="category_id">
					<?php foreach ($categories as $category) :?>
						<option value="<?php echo (int)$category->id; ?>" <?php echo (isset($article) ? ((int)$category->id == $article->category_id) ? 'selected="selected"' : '' : ''); ?>><?php echo $category->category ?></option>
					<?php endforeach; ?>
					</select>
				<?php endif; ?>
				<?php if (form_error('category_id')) echo '<span class="help-inline">'. form_error('category_id') .'</span>'; ?>
			</div>
		</div>
		
		
		<div class="control-group <?php echo form_error('status_id') ? 'error' : '' ?>">
			<label><?php echo lang('us_status') ?></label>
			<div class="controls">
				<?php if (is_array($statuses) && count($statuses)) : ?>
					<select name="status_id">
					<?php foreach ($statuses as $status) :?>
						<option value="<?php echo (int)$status->id; ?>" <?php echo (isset($article) ? ((int)$status->id == $article->status_id) ? 'selected="selected"' : '' : ''); ?>><?php echo $status->status ?></option>
					<?php endforeach; ?>
					</select>
				<?php endif; ?>
					<?php if (form_error('status_id')) echo '<span class="help-inline">'. form_error('status_id') .'</span>'; ?>
			</div>
		</div>
		
		<div class="control-group <?php echo form_error('date_published') ? 'error' : '' ?>">
			<label><?php echo lang('us_publish_date') ?></label>
			<div class="controls">
				<input type="text" name="date_published" id="date_published" value="<?php echo (isset($article) && isset($article->date_published) && !empty($article->date_published)) ? date('m/d/Y',$article->date_published) : (isset($this->input->post('date_published')) ? set_value(date('m/d/Y','date_published')) : '') ?>" />
				<?php if (form_error('date_published')) echo '<span class="help-inline">'. form_error('date_published') .'</span>'; ?>
			</div>
		</div>

		<?php if (isset($article) && isset($article->id)) { ?>
		<div class="control-group <?php echo form_error('') ? 'error' : '' ?>">
			<label><?php echo lang('us_created') ?></label>
			<div class="controls">
				<?php echo (isset($article) ? date('m/d/Y h:i:s A',$article->created_on) : 'Unknown'); ?> by <?php echo (isset($article) ? $this->auth->username($article->created_by) : 'Unknown'); ?></div>
			</div>
		</div>
		
		<div class="control-group <?php echo form_error('') ? 'error' : '' ?>">
			<label><?php echo lang('us_modified') ?></label>
			<div class="controls">
				<span><?php echo (isset($article) ? date('m/d/Y h:i:s A',$article->modified_on) : 'Unknown'); ?> by <?php echo (isset($article) ? $this->auth->username($article->modified_by) : 'Unknown'); ?></span>
			</div>
		</div>
		<?php } ?>
		
	</fieldset>
	<?php endif; ?>
	
	<div class="form-actions">
        <input type="submit" name="submit" class="btn primary" value="<?php echo lang('bf_action_save') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/settings', lang('bf_action_cancel')); ?>
    </div>
	
<?php echo form_close(); ?>

<script type="text/javascript"> 
head.ready(function(){
    $("#date").datepicker();
    $("#date_published").datepicker();
});
</script>