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
             <label class="control-label"><?php echo lang('us_title') ?></label>
            <div class="controls">
                <input type="text" name="title" id="title" value="<?php echo isset($article) ? $article->title : set_value('title') ?>" />
				<?php if (form_error('title')) echo '<span class="help-inline">'. form_error('title') .'</span>'; ?>
            </div>
        </div>

			<!-- Date -->
        <div class="control-group <?php echo form_error('date') ? 'error' : '' ?>">
             <label class="control-label"><?php echo lang('us_date') ?></label>
            <div class="controls">
                <input type="text" name="date" id="date" value="<?php echo (isset($article) && isset($article->date) && !empty($article->date)) ? date('m/d/Y',$article->date) : ($this->input->post('date') ? set_value(date('m/d/Y','date')) : '') ?>" />
				<?php if (form_error('date')) echo '<span class="help-inline">'. form_error('date') .'</span>'; ?>
            </div>
        </div>

			<!-- Body -->
        <div class="control-group <?php echo form_error('body') ? 'error' : '' ?>">
             <label class="control-label"><?php echo lang('us_body') ?></label>
            <div class="controls">
                <?php echo form_textarea( array( 'name' => 'body', 'id' => 'body', 'rows' => '5', 'cols' => '80', 'value' => isset($article) ? $article->body : set_value('body') ) )?>
				<?php if (form_error('body')) echo '<span class="help-inline">'. form_error('body') .'</span>'; ?>
            </div>
        </div>
		
	</fieldset>
	<fieldset>
		<legend>Image Information</legend>
		<?php 
		// ATTACHMENTS
		if (isset($settings['news.allow_attachments']) && $settings['news.allow_attachments'] == 1) 
		{
		?>
			<!-- Image Upload -->
		<div class="control-group <?php echo form_error('attachment') ? 'error' : '' ?>">
             <label class="control-label"><?php echo lang('us_image_path') ?></label>
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
             <label class="control-label"><?php echo lang('us_image_caption') ?></label>
            <div class="controls">
                <input type="text" name="image_caption" id="image_caption" value="<?php echo isset($article) ? $article->image_caption : set_value('image_caption') ?>" />
				<?php if (form_error('image_caption')) echo '<span class="help-inline">'. form_error('image_caption') .'</span>'; ?>
            </div>
        </div>

		<!-- IMAGE ALIGNMENT -->
		<div class="control-group <?php echo form_error('image_align') ? 'error' : '' ?>">
             <label class="control-label"><?php echo lang('us_image_align') ?></label>
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
	</fieldset>
	<fieldset>
		<legend>Tags and Author</legend>
			<!-- TAGS -->
		<div class="control-group <?php echo form_error('tags') ? 'error' : '' ?>">
			<label class="control-label"><?php echo lang('us_tags') ?></label>
			<div class="controls">
				<input type="text" id="tags" name="tags" value="<?php echo isset($article) ? $article->tags : set_value('tags') ?>" />
				<?php if (form_error('tags')) echo '<span class="help-inline">'. form_error('tags') .'</span>'; ?>
			</div>
		</div>
			
			<!-- AUTHOR -->
		<div class="control-group <?php echo form_error('author') ? 'error' : '' ?>">
			 <label class="control-label"><?php echo lang('us_author') ?></label>
			<div class="controls">
				<?php  
				$selection = ( isset ($article) && !empty( $article->author ) ) ? (int) $article->author : $current_user->id;
				if ( has_permission('Site.News.Manage') ) :
					if (isset($users) && is_array($users) && count($users)) :
						echo form_dropdown('author', $users, $selection , 'class="span6" id="author"');
					endif;
					if (form_error('author')) echo '<span class="help-inline">'. form_error('author') .'</span>'; ?>
				<?php else : 
					echo find_author_name($selection);
				endif; ?>
			</div>
		</div>
	</fieldset>
	<?php  if ( has_permission('Site.News.Manage') ) :?>
	<fieldset>
		<legend><?php echo lang('us_additional'); ?></legend>

		<div class="control-group <?php echo form_error('category_id') ? 'error' : '' ?>">
			 <label class="control-label"><?php echo lang('us_category') ?></label>
			<div class="controls">
				<?php 
				if (is_array($categories) && count($categories)) :
					$selection = ( isset ($article) && !empty($article->category_id ) ) ? (int) $article->category_id : 0;
					echo form_dropdown('category_id', $categories, $selection , 'class="span6" id="category_id"');
				endif;if (form_error('category_id')) echo '<span class="help-inline">'. form_error('category_id') .'</span>'; ?>
			</div>
		</div>
		
		<div class="control-group <?php echo form_error('status_id') ? 'error' : '' ?>">
			 <label class="control-label"><?php echo lang('us_status') ?></label>
			<div class="controls">
				<?php if (is_array($statuses) && count($statuses)) :
					$selection = ( isset ($article) && !empty($article->status_id ) ) ? (int) $article->status_id : 0;
					echo form_dropdown('status_id', $statuses, $selection , 'class="span6" id="status_id"');
				endif;
				if (form_error('status_id')) echo '<span class="help-inline">'. form_error('status_id') .'</span>'; ?>
			</div>
		</div>
		
		<div class="control-group <?php echo form_error('date_published') ? 'error' : '' ?>">
			 <label class="control-label"><?php echo lang('us_publish_date') ?></label>
			<div class="controls">
				<input type="text" name="date_published" id="date_published" value="<?php echo (isset($article) && isset($article->date_published) && !empty($article->date_published)) ? date('m/d/Y',$article->date_published) : ($this->input->post('date_published') ? set_value(date('m/d/Y','date_published')) : '') ?>" />
				<?php if (form_error('date_published')) echo '<span class="help-inline">'. form_error('date_published') .'</span>'; ?>
			</div>
		</div>

		<?php if (isset($article) && isset($article->id)) : ?>
		<div class="control-group <?php echo form_error('') ? 'error' : '' ?>">
			 <label class="control-label"><?php echo lang('us_created') ?></label>
			<div class="controls">
				<?php echo (isset($article) && isset($article->created_on) ? date('m/d/Y h:i:s A',$article->created_on) : 'Unknown'); ?> by <?php echo (isset($article) ? $this->auth->username($article->created_by) : 'Unknown'); ?></div>
			</div>
		</div>
		
		<div class="control-group <?php echo form_error('') ? 'error' : '' ?>">
			 <label class="control-label"><?php echo lang('us_modified') ?></label>
			<div class="controls">
				<span><?php echo (isset($article) && isset($article->modified_on) ? date('m/d/Y h:i:s A',$article->modified_on) : 'Unknown'); ?> by <?php echo (isset($article) ? $this->auth->username($article->modified_by) : 'Unknown'); ?></span>
			</div>
		</div>
		<?php endif; ?>
		
	</fieldset>
	<?php endif; ?>

	<div class="form-actions">
		<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_save') .' '. lang('us_article') ?>" />
	</div>
	
<?php echo form_close(); ?>

<script type="text/javascript"> 
head.ready(function(){
    $("#date").datepicker();
    $("#date_published").datepicker();
});
</script>