<?php if (validation_errors()) : ?>
<div class="alert alert-error alert-block fade in">
		<a class="close" data-dismiss="alert">&times;</a>
			<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<div class="admin-box">
	<h3><?php echo isset($toolbar_title) ? $toolbar_title : lang('bf_required_note');  ?></h3>
		<?php echo form_open_multipart($this->uri->uri_string(), 'class="form-horizontal ajax-form"'); ?>

		<fieldset>
		<legend><?php echo lang('mw_setting_title'); ?></legend>

		<!-- Allow Attachments -->
		<div class="control-group <?php echo form_error('allow_attachments') ? 'error' : '' ?>" >
			<label for="allow_attachments"><?php echo lang('nw_settings_attachAllow') ?></label>
			<div class="controls">
			<label class="checkbox" for="allow_attachments">
						<?php
						$use_selection = ((isset($settings['news.allow_attachments']) && $settings['news.allow_attachments'] == 1) || !isset($settings['news.allow_attachments'])) ? true : false;
						echo form_checkbox('allow_attachments',1, $use_selection,'id="allow_attachments"');
						?>
				<?php if (form_error('allow_attachments')) echo '<span class="help-inline">'. form_error('allow_attachments') .'</span>'; ?>
			</label>
			</div>
		</div>

		<!-- Upload Path -->
		<div class="control-group <?php echo form_error('upload_dir_path') ? 'error' : '' ?>" >
			<label for="upload_dir_path"><?php echo lang('nw_upload_dir_path') ?></label>
			<div class="controls">
				<input class="span8" type="text" id="upload_dir_path" name="upload_dir_path" value="<?php echo (isset($settings['news.upload_dir_path'])) ? $settings['news.upload_dir_path']: set_value('news.upload_dir_path'); ?>" /><br />
				<?php
						if (form_error('upload_dir_path'))
								echo '<span class="help-inline">'. form_error('upload_dir_path') .'</span>';
						else
								echo '<span class="help-inline">'. lang('nw_upload_dir_path_note') .'</span>';
				?>
			</div>
		</div>

		<!-- Upload URL -->
		<div class="control-group <?php echo form_error('upload_dir_url') ? 'error' : '' ?>" >
			<label for="upload_dir_url"><?php echo lang('nw_upload_dir_url') ?></label>
			<div class="controls">
				<input class="span8" type="text" id="upload_dir_url" name="upload_dir_url" value="<?php echo (isset($settings['news.upload_dir_url'])) ? $settings['news.upload_dir_url']: set_value('news.upload_dir_url'); ?>" /><br />
				<?php
						if (form_error('upload_dir_url'))
								echo '<span class="help-inline">'. form_error('upload_dir_url') .'</span>';
						else
								echo '<span class="help-inline">'. lang('nw_upload_dir_url_note') .'</span>';
				?>
			</div>
		</div>

		</fieldset>
		<fieldset>
		<legend><?php echo lang('nw_image_dimensions'); ?></legend>

		<!-- Max Image Dimensions-->
		<div class="control-group <?php echo form_error('max_img_width') ? 'error' : '' ?>" >
			<label for="max_img_width"><?php echo lang('nw_width') ?></label>
			<div class="controls">
				<input type="text" class="span2" id="max_img_width" name="max_img_width" value="<?php echo (isset($settings['news.max_img_width'])) ? $settings['news.max_img_width']: set_value('news.max_img_width'); ?>" />
				<?php if (form_error('max_img_width')) echo '<span class="help-inline">'. form_error('max_img_width') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('max_img_height') ? 'error' : '' ?>" >
			<label for="max_img_height"><?php echo lang('nw_height') ?></label>
			<div class="controls">
				<input type="text" class="span2" id="max_img_height" name="max_img_height" value="<?php echo (isset($settings['news.max_img_height'])) ? $settings['news.max_img_height']: set_value('news.max_img_height'); ?>" />
				<?php if (form_error('max_img_height')) echo '<span class="help-inline">'. form_error('max_img_height') .'</span>'; ?>
			</div>
		</div>

		<!-- Max File Size -->
		<div class="control-group <?php echo form_error('max_img_size') ? 'error' : '' ?>" >
			<label for="max_img_size"><?php echo lang('nw_max_img_size') ?></label>
			<div class="controls">
				<input type="text" class="span2" id="max_img_size" name="max_img_size" value="<?php echo (isset($settings['news.max_img_size'])) ? $settings['news.max_img_size']: set_value('news.max_img_size'); ?>" />
				<?php if (form_error('max_img_size')) echo '<span class="help-inline">'. form_error('max_img_size') .'</span>'; ?>
			</div>
		</div>

		</fieldset>
		<fieldset>
		<legend><?php echo lang('nw_resize_images'); ?></legend>

		<!-- Max Rendered Dimensions in Articles-->
		<div class="control-group <?php echo form_error('max_img_disp_width') ? 'error' : '' ?>" >
			<label for="max_img_disp_width"><?php echo lang('nw_width') ?></label>
			<div class="controls">
				<input type="text" class="span2" id="max_img_disp_width" name="max_img_disp_width" value="<?php echo (isset($settings['news.max_img_disp_width'])) ? $settings['news.max_img_disp_width']: set_value('news.max_img_disp_width'); ?>" />
				<?php if (form_error('max_img_disp_width')) echo '<span class="help-inline">'. form_error('max_img_disp_width') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('max_img_disp_height') ? 'error' : '' ?>" >
			<label for="max_img_disp_height"><?php echo lang('nw_height') ?></label>
			<div class="controls">
				<input type="text" class="span2" id="max_img_disp_height" name="max_img_disp_height" value="<?php echo (isset($settings['news.max_img_disp_height'])) ? $settings['news.max_img_disp_height']: set_value('news.max_img_disp_height'); ?>" />
				<?php if (form_error('max_img_disp_height')) echo '<span class="help-inline">'. form_error('max_img_disp_height') .'</span>'; ?>
			</div>
		</div>

		<div class="control-group <?php echo form_error('max_img_disp_width') ? 'error' : '' ?>" >
			<label for="max_img_disp_width"><?php echo lang('nw_width') ?></label>
			<div class="controls">
				<input type="text" class="span2" id="max_img_disp_width" name="max_img_disp_width" value="<?php echo (isset($settings['news.max_img_disp_width'])) ? $settings['news.max_img_disp_width']: set_value('news.max_img_disp_width'); ?>" />
				<?php echo '<span class="help-inline">'. lang('nw_resize_images_note') .'</span>'; ?>
			</div>
		</div>

		<div class="form-actions">
			<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_save') ?>" /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/content/index', lang('bf_action_cancel'), 'class="btn btn-danger"'); ?>
		</div>

<?php echo form_close(); ?>

		</fieldset>
</div>
