<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<p class="small"><?php echo lang('bf_required_note'); ?></p>

<?php echo form_open($this->uri->uri_string(), 'class="constrained ajax-form"'); ?>

<fieldset>
<legend><?php echo lang('mw_setting_title'); ?></legend>
</fieldset>
		<!-- Allow Attachments -->
	<div>
		<label><?php echo lang('nw_settings_attachAllow'); ?></label>
		<?php
		$use_selection = ((isset($settings['news.allow_attachments']) && $settings['news.allow_attachments'] == 1) || !isset($settings['news.allow_attachments'])) ? true : false;
		echo form_checkbox('allow_attachments',1, $use_selection,'id="allow_attachments"');
		?>
	</div>
		<!-- Upload Path -->
	<div>
		<label for="upload_dir_path"><?php echo lang('nw_upload_dir_path'); ?></label>
		<input type="text" id="upload_dir_path" name="upload_dir_path" value="<?php echo (isset($settings['news.upload_dir_path'])) ? $settings['news.upload_dir_path']: set_value('news.upload_dir_path'); ?>" /><br />
		<span class="subcaption"><?php echo lang('nw_upload_dir_path_note'); ?></span>
	</div>
		<!-- Upload URL -->
	<div>
		<label for="upload_dir_url"><?php echo lang('nw_upload_dir_url'); ?></label>
		<input type="text" id="upload_dir_url" name="upload_dir_url" value="<?php echo (isset($settings['news.upload_dir_url'])) ? $settings['news.upload_dir_url']: set_value('news.upload_dir_url'); ?>" /><br />
		<span class="subcaption"><?php echo lang('nw_upload_dir_url_note'); ?></span>
	</div>
	
	<div class="submits">
		<input type="submit" name="submit" value="<?php echo lang('bf_action_save') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/settings', lang('bf_action_cancel')); ?>
	</div>
	
<?php echo form_close(); ?>

