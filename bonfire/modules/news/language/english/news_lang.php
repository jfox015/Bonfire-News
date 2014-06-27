<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$lang['us_article_management']		= 'Article Manager';
$lang['article_unauthorized']		= 'Unauthorized. Sorry you do not have the appropriate permission to this area.';
$lang['us_empty_id']				= 'No Article Id was received.';
$lang['us_empty_article_list']		= 'Article Id list was empty.';
$lang['us_create_news']				= 'Create News Article';
$lang['us_create_news_note']		= '<h3>Create A New Article</h3><p>Create new articles to display news about the site and content.</p>';
$lang['us_delete_article_note']		= '<h3>Delete this Article</h3><p>Deleting this article will remove it completely from the site.</p>';
$lang['us_news_options_note']		= '<h3>News Options</h3><p>Manage attachment upload directories and news content options.</p>';
$lang['us_purge_del_note']			= '<h3>Purge Deleted Accounts</h3><p>Purging deleted accounts is a permanent action. There is no going back, so please make sure..</p>';
$lang['us_edit_news']				= 'Edit News Article';
$lang['us_delete_article']			= 'Delete this Article';
$lang['us_delete_article_confirm']	= 'Are you sure you want to delete this article?';
$lang['us_purge_del_articles']		= 'Purge Articles';

// STATUSES
$lang['us_upload_dir_unspecified']	= 'Attachment Upload Directory not specified.';
$lang['us_upload_dir_unwritable']	= 'Attachment Upload Directory "%s" is not write-able.';
$lang['us_article_created']			= 'Article successfully created.';
$lang['us_article_create_fail']		= 'There was a problem creating the article: "%s"';
$lang['us_article_updated']			= 'The article was successfully updated.';
$lang['us_article_update_fail']		= 'The article could not be updated. Error: "%s"';
$lang['us_article_restore']			= 'Article successfully restored.';
$lang['us_article_restore_fail']	= 'Unable to restore article: "%s"';
$lang['us_article_deleted']			= 'The article was successfully deleted.';
$lang['us_article_delete_fail']		= 'Article could not be deleted. Error: "%s"';
$lang['us_log_article_create']		= 'Created Article: "%s"';
$lang['us_log_article_update']		= 'Edited Article: "%s"';
$lang['us_log_article_update_fail']	= 'Could not edit article: "%s"';
$lang['us_log_article_delete']		= 'Deleted article: "%s"';
$lang['us_log_article_purge']		= 'Articles Purged.';
$lang['us_log_article_delete_fail']	= 'Could not delete article: "%s"';
$lang['us_log_file_save_fail']		= 'There was a problem saving the file attachment: "%s"';
$lang['us_log_file_detatch']		= 'Attachment file: "%s" was successfully deleted.';
$lang['us_log_file_detatch_fail']	= 'Problem deleting attachment file: "%s"';
$lang['us_log_file_remove']			= 'Attachment removed.';
$lang['us_log_file_remove_fail']	= 'Attachment removal failed.';

/* New index */
$lang['us_article_id']				= 'Article ID';
$lang['us_articles']				= 'Articles';
$lang['us_article']					= 'Article';
$lang['us_no_articles_found']		= 'No article content was found.';

$lang['nw_setting_title']			= 'News Settings';
$lang['us_news_options']			= 'News Options';
$lang['nw_default_article_count']	= 'Default Article Count';
$lang['nw_article_count_note']	    = 'How many articles should show on the default news home page';

$lang['nw_public_submissions']		= 'Allow Public News Submissions';
$lang['nw_public_submitters']		= 'Must be member/signed in to submit news';
$lang['nw_public_moderation']		= 'Queue public submissions for moderation';
$lang['nw_moderation_note']			= 'If not checked, publicly submitted news articles are set to published upon submission';

$lang['nw_comments_enabled']		= 'Enable Comments';
$lang['nw_comments_enabled_note']	= 'Comments can be moderated from the <a href="[COMMENTS_URL]">Comments content page</a>';
$lang['nw_get_comments_module']		= 'Enable user comments and feedback with the <a href="https://github.com/jfox015/Bonfire-Comments" target="_blank">Bonfire Comments</a> module.';

$lang['nw_settings_attachAllow']	= 'Allow Attachments';
$lang['nw_upload_dir_path']			= 'Upload directory path';
$lang['nw_upload_dir_path_note']	= 'Full server path from server root to attachmnent upload directory';
$lang['nw_upload_dir_url']			= 'Upload directory URL';
$lang['nw_upload_dir_url_note']		= 'Web accessible URL to asset upload directory. Include the trailing slash!';
$lang['nw_image_dimensions']		= 'Max image dimensions:';
$lang['nw_resize_images']			= 'Max image dimensions in articles:';
$lang['nw_resize_images_note']		= 'For any images exceeding these dimensions, a thumbnail will be created and displayed in the article instead.';
$lang['nw_max_img_size']			= 'Max Image File Size';
$lang['nw_max_img_size_note']		= 'in killobytes (kB)';
$lang['nw_max_img_width']			= 'Max Image Width';
$lang['nw_max_img_disp_width']		= 'Max Image Display Width';
$lang['nw_max_img_height']			= 'Max Image Height';
$lang['nw_max_img_disp_height']		= 'Max Image Display Height';
$lang['nw_width']					= 'Width';
$lang['nw_height']					= 'Height';
// Social Sharing Settings
$lang['nw_social_sharing']			= 'Social Sharing';
$lang['nw_sharing_enabled']			= 'Sharing Enabled';
$lang['nw_sharing_note']			= 'Select which services you wish to enable sharing for. Uncheck &quot;Sharing Enabled&quot; to disable all services.';
$lang['nw_select_services']			= 'Select Services';
$lang['nw_share_facebook']			= 'Facebook';
$lang['nw_share_twitter']			= 'Twitter';
$lang['nw_share_stumbleupon']		= 'StumbleUpon';
$lang['nw_share_delicious']			= 'Delicious';
$lang['nw_share_email']				= 'Email';
$lang['nw_share_fblike']			= 'Facebook Like';
$lang['nw_share_plusone']			= 'Google +1';

$lang['us_draft_articles']			= 'Draft articles.';
$lang['us_action_draft']			= 'Draft';
$lang['us_action_review']			= 'In Review';
$lang['us_action_publish']			= 'Publish';
$lang['us_action_archive']			= 'Archive';
$lang['us_action_archived']			= 'Archived';
$lang['us_status_change']			= 'Change Status';
$lang['us_deleted_articles']		= 'Deleted';

$lang['us_published_articles']		= 'Published articles.';
$lang['no_articles']				= 'No articles were found.';
$lang['us_no_deleted']				= 'There are not any deleted articles in the database.';
$lang['us_no_drafts']				= 'There are not any drafts in the database.';
$lang['us_no_published']			= 'There are not any published articles in the database.';

$lang['us_article_details']			= 'Article Details';
$lang['us_title']					= 'Title';
$lang['us_date']					= 'Article Date';
$lang['us_body']					= 'Article Body';
$lang['us_image_path']				= 'Attach Image';
$lang['us_current_attachment']		= 'Current Attachment';
$lang['us_image_align']				= 'Image Alignment';
$lang['us_image_caption']			= 'Caption';
$lang['us_image_align_left']		= 'Left (Default)';
$lang['us_image_align_right']		= 'Right';
$lang['us_tags']					= 'Tags';

$lang['us_general_settings']		= 'General Settings';
$lang['us_general_info']			= 'General Info';
$lang['us_comments']				= 'Comments';
$lang['us_additional']				= 'Additional Information';
$lang['us_img_attachments']			= 'Image Attachments';
$lang['nw_random']				    = 'Random Details';

$lang['us_author']					= 'Author';
$lang['us_category']				= 'Category';
$lang['us_status']					= 'Status';
$lang['us_publish_date']			= 'Publish Date';
$lang['us_created']					= 'Created';
$lang['us_modified']				= 'Last Updated:';
$lang['us_action_publish']			= 'Publish';
$lang['us_action_review']			= 'Review';
$lang['us_select_user']				= 'Select User';

$lang['us_image_title'] 			= 'Image Title';
$lang['us_image_alttag'] 			= 'Image Alt Tag';

$lang['us_recent_news'] 			= 'Recent News';
$lang['us_no_articles'] 			= 'No articles were found.';
$lang['nw_article_comments'] 		= 'Comments';


