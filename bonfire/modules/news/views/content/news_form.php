<?php if (validation_errors()) : ?>
	<div class="alert alert-block alert-error fade in">
		<a class="close" data-dismiss="alert">&times;</a>
		<?php echo validation_errors(); ?>
	</div>

<?php endif; ?>

<div class="admin-box">

    <h3>Article Details</h3>

    <?php echo form_open_multipart($this->uri->uri_string(), 'class="form-horizontal"'); ?>

    <fieldset>
        <legend>General Information</legend>

		<!-- Title -->
        <div class="control-group <?= iif ( form_error('title'), 'error'); ?>">
			<label class="control-label"><?= lang('us_title') ?></label>
            <div class="controls">
                <input type="text" class="span8" name="title" id="title" value="<?php echo isset($article) ? $article->title : set_value('title') ?>" />
				<?php if (form_error('title')) echo '<span class="help-inline">'. form_error('title') .'</span>'; ?>
            </div>
        </div>

		<!-- Date -->
        <div class="control-group <?= iif ( form_error('date'), 'error'); ?>">
             <label class="control-label" for="date"><?= lang('us_date') ?></label>
            <div class="controls">
                <input type="text" name="date" id="date" value="<?php echo (isset($article) && isset($article->date) && !empty($article->date)) ? date('m/d/Y',$article->date) : ($this->input->post('date') ? set_value(date('m/d/Y','date')) : '') ?>" />
				<?php if (form_error('date')) echo '<span class="help-inline">'. form_error('date') .'</span>'; ?>
            </div>
        </div>

		<!-- Body -->
        <div class="control-group <?= iif ( form_error('body'), 'error'); ?>">
			<label class="control-label" ><?= lang('us_body') ?></label>
            <div class="controls">
                <?php echo form_textarea( array( 'class' => 'editor', 'name' => 'body', 'id' => 'body', 'rows' => '8', 'cols' => '80', 'value' => isset($article) ? $article->body : set_value('body') ) )?>
				<?php if (form_error('body')) echo '<span class="help-inline">'. form_error('body') .'</span>'; ?>
            </div>
        </div>



	<?php if (isset($settings['news.allow_attachments']) && $settings['news.allow_attachments'] == 1) : ?>
    </fieldset>
    <fieldset>
	    <legend>Image Attachments</legend>

		<!-- // ATTACHMENTS -->

		<!-- Image Upload -->
		<div class="control-group <?= iif ( form_error('attachment'), 'error'); ?>">
			<label class="control-label"><?= lang('us_image_path') ?></label>
            <div class="controls">
                <input type="file" id="attachment" name="attachment" />
	            <span class="help-inline"> <?php if (form_error('attachment')) echo form_error('attachment'); ?></span>
			</div>
        </div>


		<!-- Current Image -->
		<?php if(isset($article) && isset($article->attachment) && !empty($article->attachment)) :
			$attachment = unserialize($article->attachment);
		?>

		<!-- Current Image Display -->
		<div class="control-group">
			<label class="control-label">Current Attachment</label>
			<div class="controls">
				<ul class="thumbnails">
					<li class="span3">
						<div class="thumbnail">
						<a class="lightbox" href="<?= base_url() . $settings['news.upload_dir_url'] . $attachment['file_name'] ?>" target="_blank" >
							<img src="<?= base_url() . $settings['news.upload_dir_url'] . $attachment['file_name'] ?>" />
						</a>
						<h5><?= $attachment['file_name'].' ('.$attachment['file_size'].'kB '.$attachment['file_type'].')'; ?></h5>
						<p><?= anchor(SITE_AREA.'/content/news/remove_attachment/'.$article->id,'Remove', 'class="btn btn-small btn-danger"'); ?></p>
						</div>
					</li>
				</ul>
			</div>
		</div>

		<?php endif; ?>

	<!-- IMAGE CAPTION -->
	<div class="control-group <?= iif ( form_error('image_caption'), 'error'); ?>">
		<label class="control-label"><?= lang('us_image_caption') ?></label>
        <div class="controls">
            <input type="text" class="span8" name="image_caption" id="image_caption" value="<?php echo isset($article) ? $article->image_caption : set_value('image_caption') ?>" />
			<?php if (form_error('image_caption')) echo '<span class="help-inline">'. form_error('image_caption') .'</span>'; ?>
        </div>
    </div>

	<!-- IMAGE title -->
	<div class="control-group <?= iif ( form_error('image_title'), 'error'); ?>">
		<label class="control-label"><?= lang('us_image_title') ?></label>
        <div class="controls">
            <input type="text" class="span8" name="image_title" id="image_title" value="<?php echo isset($article->image_title) ? $article->image_title : set_value('image_title') ?>" />
			<?php if (form_error('image_title')) echo '<span class="help-inline">'. form_error('image_title') .'</span>'; ?>
        </div>
    </div>

	<!-- IMAGE alttag -->
	<div class="control-group <?= iif ( form_error('image_alttag'), 'error'); ?>">
		<label class="control-label"><?= lang('us_image_alttag') ?></label>
        <div class="controls">
            <input type="text" class="span8" name="image_alttag" id="image_alttag" value="<?php echo isset($article->image_alttag) ? $article->image_alttag : set_value('image_alttag') ?>" />
			<?php if (form_error('image_alttag')) echo '<span class="help-inline">'. form_error('image_alttag') .'</span>'; ?>
        </div>
    </div>

		<!-- IMAGE ALIGNMENT -->
		<div class="control-group <?= iif ( form_error('image_align'), 'error'); ?>">
			<label class="control-label"><?= lang('us_image_align') ?></label>
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

	<?php endif; ?>

    </fieldset>
    <fieldset>
	    <legend>Author and Randomness</legend>


	<!-- TAGS -->
	<div class="control-group <?= iif ( form_error('tags'), 'error'); ?>">
		<label class="control-label"><?= lang('us_tags') ?></label>
		<div class="controls">
			<input type="text" id="tags" name="tags" value="<?php echo isset($article) ? $article->tags : set_value('tags') ?>" />
			<?php if (form_error('tags')) echo '<span class="help-inline">'. form_error('tags') .'</span>'; ?>
		</div>
	</div>
		
		<!-- AUTHOR -->
	<div class="control-group <?= iif ( form_error('author'), 'error'); ?>">
		<label class="control-label"><?= lang('us_author') ?></label>
		<div class="controls">
			<?php if ( has_permission('Site.News.Manage') ) : ?>
<?php
			if (isset($users) && is_array($users) && count($users)) :

				$selection = ( isset ($article) && !empty( $article->author ) ) ? (int) $article->author : $current_user->username;
				echo form_dropdown('author', $users, $selection , 'class="chzn-select" id="author"');
			endif;
/*
				<?php if (isset($users) && is_array($users) && count($users)) : ?>
					<select name="author" id="author" class="chzn-select">
					<?php foreach ($users as $user) :?>
						<option value="<?php echo (int)$user->id; ?>" <?php echo ((isset($article) && isset($article->author) && isset($user)) ? ((int)$user->id == $article->author) ? 'selected="selected"' : '' : ''); ?>><?php echo $user->username ?></option>
					<?php endforeach; ?>
					</select>
				<?php endif; ?>
*/
?>
				<?php if (form_error('author')) echo '<span class="help-inline">'. form_error('author') .'</span>'; ?>
			<?php else : 
				echo $article->author_name;
			endif; ?>
		</div>
	</div>
	</fieldset>
	<?php  if ( has_permission('Site.News.Manage') ) :?>
	<fieldset>
		<legend><?php echo lang('us_additional'); ?></legend>

		<div class="control-group <?= iif ( form_error('category_id'), 'error' ) ?>">
			 <label class="control-label"><?= lang('us_category') ?></label>
			<div class="controls">
				<?php
				if (is_array($categories) && count($categories)) :

					$selection = ( isset ($article) && !empty($article->category_id ) ) ? (int) $article->category_id : 0;
					echo form_dropdown('category_id', $categories, $selection , 'class="chzn-select" id="category_id"');
				endif;

/*
				<?php if (is_array($categories) && count($categories)) : ?>
					<select name="category_id" id="category_id" class="chzn-select">
					<?php foreach ($categories as $category) :?>
						<option value="<?php echo (int)$category->id; ?>" <?php echo (isset($article) ? ((int)$category->id == $article->category_id) ? 'selected="selected"' : '' : ''); ?>><?php echo $category->category ?></option>
					<?php endforeach; ?>
					</select>
				<?php endif; ?>
*/?>
				<?php if (form_error('category_id')) echo '<span class="help-inline">'. form_error('category_id') .'</span>'; ?>
			</div>
		</div>
		
		
		<div class="control-group <?= iif ( form_error('status_id'), 'error'); ?>">
			 <label class="control-label"><?= lang('us_status') ?></label>
			<div class="controls">
				<?php
				if (is_array($statuses) && count($statuses)) :

					$selection = ( isset ($article) && !empty($article->status_id ) ) ? (int) $article->status_id : 0;
					echo form_dropdown('status_id', $statuses, $selection , 'class="chzn-select" id="status_id"');
				endif;
				?>
				<?php
				/*
				<?php if (is_array($statuses) && count($statuses)) : ?>
					<select name="status_id" id="status_id" class="chzn-select">
					<?php foreach ($statuses as $status) :?>
						<option value="<?php echo (int)$status->id; ?>" <?php echo (isset($article) ? ((int)$status->id == $article->status_id) ? 'selected="selected"' : '' : ''); ?>><?php echo $status->status ?></option>
					<?php endforeach; ?>
					</select>
				<?php endif; ?>
*/
				?>

					<?php if (form_error('status_id')) echo '<span class="help-inline">'. form_error('status_id') .'</span>'; ?>
			</div>
		</div>
		
		<div class="control-group <?= iif ( form_error('date_published'), 'error'); ?>">
			 <label class="control-label"><?= lang('us_publish_date') ?></label>
			<div class="controls">
				<input type="text" name="date_published" id="date_published" value="<?php echo (isset($article) && isset($article->date_published) && !empty($article->date_published)) ? date('m/d/Y',$article->date_published) : ($this->input->post('date_published') ? set_value(date('m/d/Y','date_published')) : '') ?>" />
				<?php if (form_error('date_published')) echo '<span class="help-inline">'. form_error('date_published') .'</span>'; ?>
			</div>
		</div>

		<?php if (isset($article) && isset($article->id)) : ?>
		<div class="control-group">
			 <label class="control-label"><?= lang('us_created') ?></label>
			<div class="controls">
				<span class="inline-help">
					<?php echo (isset($article) ? date('m/d/Y h:i:s A',$article->created_on) : 'Unknown'); ?> by <?php echo (isset($article) ? find_author_name($article->created_by) : 'Unknown'); ?>
				</span>
			</div>
			</div>
		
		<div class="control-group">
			 <label class="control-label"><?= lang('us_modified') ?></label>
			<div class="controls">
				<span class="inline-help"><?php echo (isset($article) ? date('m/d/Y h:i:s A',$article->modified_on) : 'Unknown'); ?> by <?php echo (isset($article) ? find_author_name($article->modified_by) : 'Unknown'); ?></span>
			</div>
		</div>
		<?php endif; ?>
		

	<?php endif; ?>

	<div class="form-actions">
		<input type="submit" name="submit" class="btn btn-primary btn-large" value="<?php echo lang('bf_action_save') .' '. lang('us_article') ?>" />
	</div>

</fieldset>
<?php echo form_close(); ?>

<?php

	$inline = <<<EOL

	$(".editor").markItUp( mySettings );
	$(".chzn-select").chosen();
	$("#date, #date_published").datepicker();

EOL;

	Assets::add_js( $inline, 'inline' );
	unset ( $inline );

?>
