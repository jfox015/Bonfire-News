<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<div class="admin-box">

    <h3>General Settings</h3>

    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

    <fieldset>
        <legend></legend>
		    <!-- Allow Attachments -->
        <div class="control-group <?php echo form_error('allow_attachments') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('nw_settings_attachAllow') ?></label>
            <div class="controls">
                <?php
                $use_selection = ((isset($settings['news.allow_attachments']) && $settings['news.allow_attachments'] == 1) || !isset($settings['news.allow_attachments'])) ? true : false;
                echo form_checkbox('allow_attachments',1, $use_selection,'id="allow_attachments"');
                ?>
                <?php if (form_error('allow_attachments')) echo '<span class="help-inline">'. form_error('allow_attachments') .'</span>'; ?>
            </div>
        </div>

		    <!-- Upload Path -->
        <div class="control-group <?php echo form_error('upload_dir_path') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('nw_upload_dir_path') ?></label>
            <div class="controls">
                <input type="text" id="upload_dir_path" name="upload_dir_path" value="<?php echo (isset($settings['news.upload_dir_path'])) ? $settings['news.upload_dir_path']: set_value('news.upload_dir_path'); ?>" /><br />
                <span class="subcaption"><?php echo lang('nw_upload_dir_path_note'); ?></span>
                <?php if (form_error('upload_dir_path')) echo '<span class="help-inline">'. form_error('upload_dir_path') .'</span>'; ?>
            </div>
        </div>

		    <!-- Upload URL -->
        <div class="control-group <?php echo form_error('upload_dir_url') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('nw_upload_dir_url') ?></label>
            <div class="controls">
                <input type="text" id="upload_dir_url" name="upload_dir_url" value="<?php echo (isset($settings['news.upload_dir_url'])) ? $settings['news.upload_dir_url']: set_value('news.upload_dir_url'); ?>" /><br />
                <span class="subcaption"><?php echo lang('nw_upload_dir_url_note'); ?></span>
                <?php if (form_error('upload_dir_url')) echo '<span class="help-inline">'. form_error('upload_dir_url') .'</span>'; ?>
            </div>
        </div>

		    <!-- Max Image Dimensions-->
        <div class="control-group <?php echo form_error('upload_dir_url') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('nw_image_dimensions') ?></label>
            <div class="controls">
                <?php echo lang('nw_width'); ?>: <input type="text" style="width: 3em;" id="max_img_width" name="max_img_width" value="<?php echo (isset($settings['news.max_img_width'])) ? $settings['news.max_img_width']: set_value('news.max_img_width'); ?>" />
                <?php if (form_error('max_img_width')) echo '<span class="help-inline">'. form_error('max_img_width') .'</span>'; ?>
                <?php echo lang('nw_height'); ?>: <input type="text" style="width: 3em;" id="max_img_height" name="max_img_height" value="<?php echo (isset($settings['news.max_img_height'])) ? $settings['news.max_img_height']: set_value('news.max_img_height'); ?>" />
                <?php if (form_error('max_img_height')) echo '<span class="help-inline">'. form_error('max_img_height') .'</span>'; ?>
            </div>
        </div>

		    <!-- Max File Size -->
        <div class="control-group <?php echo form_error('upload_dir_url') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('nw_max_img_size') ?></label>
            <div class="controls">
                <input type="text" class="tiny" id="max_img_size" name="max_img_size" value="<?php echo (isset($settings['news.max_img_size'])) ? $settings['news.max_img_size']: set_value('news.max_img_size'); ?>" /> <span><?php echo lang('nw_max_img_size_note'); ?></span>
            </div>
        </div>

		    <!-- Max Rendered Dimensions in Articles-->
        <div class="control-group <?php echo form_error('upload_dir_url') ? 'error' : '' ?>">
            <label class="control-label"><?php echo lang('nw_resize_images') ?></label>
            <div class="controls">
                <?php echo lang('nw_width'); ?>: <input type="text" style="width: 3em;" id="max_img_disp_width" name="max_img_disp_width" value="<?php echo (isset($settings['news.max_img_disp_width'])) ? $settings['news.max_img_disp_width']: set_value('news.max_img_disp_width'); ?>" />
                <?php if (form_error('max_img_disp_width')) echo '<span class="help-inline">'. form_error('max_img_disp_width') .'</span>'; ?>
                <?php echo lang('nw_height'); ?>: <input type="text" style="width: 2em;" id="max_img_disp_height" name="max_img_disp_height" value="<?php echo (isset($settings['news.max_img_disp_height'])) ? $settings['news.max_img_disp_height']: set_value('news.max_img_disp_height'); ?>" />
                <?php if (form_error('max_img_disp_height')) echo '<span class="help-inline">'. form_error('max_img_disp_height') .'</span>'; ?>
                <br /><span class="subcaption"><?php echo lang('nw_resize_images_note'); ?></span>

            </div>
        </div>

    </fieldset>


	<div class="form-actions">
		<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_save') .' '. lang('bf_context_settings') ?>" />
	</div>

<?php echo form_close(); ?>

