<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('News.Settings.View');

		$this->lang->load('news');
	}

	//--------------------------------------------------------------------

	public function index()
	{
		if ($this->input->post('submit'))
		{
            $this->auth->restrict('News.Settings.Manage');
            if ($this->save_settings())
			{
				Template::set_message('Your settings were successfully saved.', 'success');
				redirect(SITE_AREA .'/settings/news');
			} else
			{
				Template::set_message('There was an error saving your settings.', 'error');
			}
		}
		// Read our current settings
		$settings = $this->settings_model->select('name,value')->find_all_by('module', 'news');
		Template::set('settings', $settings);
		Template::set('toolbar_title', lang('nw_setting_title'));
		Template::set_view('settings/index');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	private function save_settings()
	{
		$this->form_validation->set_rules('default_article_count', lang('nw_default_article_count'), 'required|numeric|max_length[3]|xss_clean');
		$this->form_validation->set_rules('public_submissions', lang('nw_public_submissions'), 'numeric|xss_clean');
		$this->form_validation->set_rules('public_submitters', lang('nw_public_submitters'), 'numeric|xss_clean');
		$this->form_validation->set_rules('public_moderation', lang('nw_public_moderation'), 'numeric|xss_clean');
		
		$this->form_validation->set_rules('comments_enabled', lang('nw_comments_enabled'), 'numeric|xss_clean');
		
		$this->form_validation->set_rules('allow_attachments', lang('nw_settings_attachAllow'), 'numeric|xss_clean');
		$this->form_validation->set_rules('upload_dir_path', lang('nw_upload_dir_path'), 'required|strip_tags|xss_clean');
		$this->form_validation->set_rules('upload_dir_url', lang('nw_upload_dir_url'), 'required|strip_tags|xss_clean');
		$this->form_validation->set_rules('max_img_size', lang('nw_max_img_size'), 'numeric|xss_clean');
		$this->form_validation->set_rules('max_img_width', lang('nw_max_img_width'), 'numeric|xss_clean');
		$this->form_validation->set_rules('max_img_height', lang('nw_max_img_height'), 'numeric|xss_clean');
		$this->form_validation->set_rules('max_img_disp_width', lang('nw_max_img_disp_width'), 'numeric|xss_clean');
		$this->form_validation->set_rules('max_img_disp_height', lang('nw_max_img_disp_height'), 'numeric|xss_clean');
		
		$this->form_validation->set_rules('sharing_enabled', lang('nw_sharing_enabled'), 'numeric|strip_tags|max_length[1]|xss_clean');
		$this->form_validation->set_rules('share_facebook', lang('nw_share_facebook'), 'numeric|strip_tags|max_length[1]|xss_clean');
		$this->form_validation->set_rules('share_twitter', lang('nw_share_twitter'), 'numeric|strip_tags|max_length[1]|xss_clean');
		$this->form_validation->set_rules('share_stumbleupon', lang('nw_share_stumbleupon'), 'numeric|strip_tags|max_length[1]|xss_clean');
		$this->form_validation->set_rules('share_delicious', lang('nw_share_delicious'), 'numeric|strip_tags|max_length[1]|xss_clean');
		$this->form_validation->set_rules('share_email', lang('nw_share_email'), 'numeric|strip_tags|max_length[1]|xss_clean');
		$this->form_validation->set_rules('share_fblike', lang('nw_share_fblike'), 'numeric|strip_tags|max_length[1]|xss_clean');
		$this->form_validation->set_rules('share_plusone', lang('nw_share_plusone'), 'numeric|strip_tags|max_length[1]|xss_clean');

		if ($this->form_validation->run() === false)
		{
			return false;
		}

		$data = array(
			array('name' => 'news.default_article_count', 'value' => $this->input->post('default_article_count')),
			array('name' => 'news.public_submissions', 'value' => ($this->input->post('public_submissions')) ? 1 : 0),
			array('name' => 'news.public_submitters', 'value' => $this->input->post('public_submitters')),
			array('name' => 'news.public_moderation', 'value' => ($this->input->post('public_moderation')) ? 1 : 0),
			
			array('name' => 'news.comments_enabled', 'value' => ($this->input->post('comments_enabled')) ? 1 : 0),
			
			array('name' => 'news.allow_attachments', 'value' => ($this->input->post('allow_attachments')) ? 1 : 0),
			array('name' => 'news.upload_dir_path', 'value' => $this->input->post('upload_dir_path')),
			array('name' => 'news.upload_dir_url', 'value' => $this->input->post('upload_dir_url')),
			array('name' => 'news.max_img_size', 'value' => $this->input->post('max_img_size')),
			array('name' => 'news.max_img_width', 'value' => $this->input->post('max_img_width')),
			array('name' => 'news.max_img_height', 'value' => $this->input->post('max_img_height')),
			array('name' => 'news.max_img_disp_width', 'value' => $this->input->post('max_img_disp_width')),
			array('name' => 'news.max_img_disp_height', 'value' => $this->input->post('max_img_disp_height')),
			
			array('name' => 'news.sharing_enabled', 'value' => ($this->input->post('sharing_enabled')) ? 1 : 0),
			array('name' => 'news.share_facebook', 'value' => ($this->input->post('share_facebook')) ? 1 : 0),
			array('name' => 'news.share_twitter', 'value' => ($this->input->post('share_twitter')) ? 1 : 0),
			array('name' => 'news.share_stumbleupon', 'value' => ($this->input->post('share_stumbleupon')) ? 1 : 0),
			array('name' => 'news.share_delicious', 'value' => ($this->input->post('share_delicious')) ? 1 : 0),
			array('name' => 'news.share_email', 'value' => ($this->input->post('share_email')) ? 1 : 0),
			array('name' => 'news.share_fblike', 'value' => ($this->input->post('share_fblike')) ? 1 : 0),
			array('name' => 'news.share_plusone', 'value' => ($this->input->post('share_plusone')) ? 1 : 0),
			
		);

		// Log the activity
		$this->load->model('activities/Activity_model', 'activity_model');

		$this->activity_model->log_activity($this->auth->user_id(), lang('bf_act_settings_saved').': ' . $this->input->ip_address(), 'news');
		// $this->activity_model->log_activity($this->current_user->id, lang('bf_act_settings_saved').': ' . $this->input->ip_address(), 'news');

		// save the settings to the DB
		$updated = $this->settings_model->update_batch($data, 'name');

		return $updated;
	}

	//--------------------------------------------------------------------
}
