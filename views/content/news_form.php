<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<p class="small"><?php echo lang('bf_required_note'); ?></p>

<?php echo form_open_multipart($this->uri->uri_string(), 'class="constrained ajax-form"'); ?>

	<div>
		<label class="required"  for="title"><?php echo lang('us_title'); ?></label>
		<input type="text" name="title" id="title" value="<?php echo isset($article) ? $article->title : set_value('title') ?>" />
	</div>

	<div>
		<label class="required"  for="date"><?php echo lang('us_date'); ?></label>
		<input type="text" name="date" id="date" value="<?php echo isset($article) ? date('m/d/Y',$article->date) : set_value('date') ?>" />
	</div>
	
	<div>
		<label class="required"><?php echo lang('us_body'); ?></label>
		<?php echo form_textarea( array( 'name' => 'body', 'id' => 'body', 'rows' => '5', 'cols' => '80', 'value' => set_value('$article->body') ) )?>
	</div>
		<!-- ATTACH IMAGE -->
	<div>
		<label for="attachment"><?php echo lang('us_image_path'); ?></label>
		<input type="file" id="attachment" name="attachment" /><br />
		<?php if(isset($article) && isset($article->attachment) && !empty($article->attachment)) : 
			$attachment = unserialize($article->attachment);
			?>
			<span class="subcaption">Current Attachment: <?php echo $attachment['file_name']." (".$attachment['file_size']."kB ".$attachment['file_type'].") | ".anchor('content/news/remove_attachment/'.$article->id,'Remove'); ?> </span>
		<?php endif; ?>
	</div>
		<!-- IMAGE CAPTION -->
	<div>
		<label for="image_caption"><?php echo lang('us_image_caption'); ?></label>
		<input type="text" name="image_caption" id="image_caption" value="<?php echo isset($article) ? $article->image_caption : set_value('image_caption') ?>" />
	</div>
		<!-- IMAGE ALIGNMENT -->
	<div>
		<label><?php echo lang('us_image_align'); ?></label>
		<?php 
		$alignments = array(-1=>'',1=>lang('us_image_align_left'),2=>lang('us_image_align_right')); ?>
		<select name="image_align">
		<?php foreach ($alignments as $id => $label) :?>
			<option value="<?php echo $id; ?>" <?php echo (isset($article) ? ($id == $article->image_align) ? 'selected="selected"' : '' : ''); ?>><?php echo $label ?></option>
		<?php endforeach; ?>
		</select>
	</div>
	
	<div>
		<label for="tags"><?php echo lang('us_tags'); ?></label>
		<input type="text" id="tags" name="tags" value="<?php echo isset($article) ? $article->tags : set_value('tags') ?>" />
	</div>
	

	<fieldset>
		<legend><?php echo lang('us_additional'); ?></legend>
		
		<div>
			<label><?php echo lang('us_author'); ?></label>
			<?php  if ( has_permission('Site.News.Manage') ) :?>
			
			<?php else : 

			endif; ?>
		</div>
		
		<div>
			<label><?php echo lang('us_category'); ?></label>
			<?php if (is_array($categories) && count($categories)) : ?>
				<select name="category_id">
				<?php foreach ($categories as $category) :?>
					<option value="<?php echo (int)$category->id; ?>" <?php echo (isset($article) ? ((int)$category->id == $article->category_id) ? 'selected="selected"' : '' : ''); ?>><?php echo $category->category ?></option>
				<?php endforeach; ?>
				</select>
            <?php endif; ?>
		</div>
		<div>
			<label><?php echo lang('us_status'); ?></label>
			<?php if (is_array($statuses) && count($statuses)) : ?>
				<select name="status_id">
				<?php foreach ($statuses as $status) :?>
					<option value="<?php echo (int)$status->id; ?>" <?php echo (isset($article) ? ((int)$status->id == $article->status_id) ? 'selected="selected"' : '' : ''); ?>><?php echo $status->status ?></option>
				<?php endforeach; ?>
				</select>
            <?php endif; ?>
		</div>
		<div>
			<label for="date_published"><?php echo lang('us_publish_date') ?></label>
			<input type="text" name="date_published" id="date_published" value="<?php echo isset($article) ? date('m/d/Y',$article->date_published) : set_value('date_published') ?>" />
		</div>
		<div>
			<label><?php echo lang('us_created'); ?></label>
			<span><?php echo (isset($article) ? date('m/d/Y h:i:s A',$article->created_on) : 'Unknown'); ?> by <?php echo (isset($article) ? $this->auth->username($article->created_by) : 'Unknown'); ?></span>
		</div>
		<div>
			<label><?php echo lang('us_modified'); ?></label>
			<span><?php echo (isset($article) ? date('m/d/Y h:i:s A',$article->modified_on) : 'Unknown'); ?> by <?php echo (isset($article) ? $this->auth->username($article->modified_by) : 'Unknown'); ?></span>
		</div>

	</fieldset>
	
	<div class="submits">
		<input type="submit" name="submit" value="<?php echo lang('bf_action_save') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/content/news', lang('bf_action_cancel')); ?>
	</div>

	<?php if (isset($article) && has_permission('Site.News.Manage') && $article->id != $this->auth->user_id()) : ?>
	<div class="box delete rounded">
		<a class="button" id="delete-me" href="<?php echo site_url(SITE_AREA .'/content/news/delete/'. $article->id); ?>" onclick="return confirm('<?php echo lang('us_delete_account_confirm'); ?>')"><?php echo lang('us_delete_account'); ?></a>
		
		<?php echo lang('us_delete_account_note'); ?>
	</div>
	<?php endif; ?>

<?php echo form_close(); ?>

<script type="text/javascript"> 
head.ready(function(){
    $(document).ready(function() {
		$("#date").datepicker();
		$("#date_published").datepicker();

		var xinha_plugins =['Linker'];
		var xinha_editors =['body'];

		function xinha_init()
		{
		  if(!Xinha.loadPlugins(xinha_plugins, xinha_init)) return;
		  var xinha_config = new Xinha.Config();
		  xinha_editors = Xinha.makeEditors(xinha_editors, xinha_config, xinha_plugins);
		  Xinha.startEditors(xinha_editors);
		}
		xinha_init();
	}
});
</script>