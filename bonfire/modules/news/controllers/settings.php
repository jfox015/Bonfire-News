<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Site.Settings.View');
		$this->auth->restrict('Site.News.Manage');

		$this->lang->load('news');
	}

	//--------------------------------------------------------------------

	public function index()
	{
		if ($this->input->post('submit'))
		{
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
		Template::set('toolbar_title', lang('mw_setting_title'));
		Template::set_view('settings/index');
		Template::render();
	}


	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	private function save_settings()
	{
		$this->form_validation->set_rules('allow_attachments', lang('nw_settings_attachAllow'), 'number|xss_clean');
		$this->form_validation->set_rules('upload_dir_path', lang('nw_upload_dir_path'), 'required|xss_clean');
		$this->form_validation->set_rules('upload_dir_url', lang('nw_upload_dir_url'), 'number|xss_clean');
		$this->form_validation->set_rules('max_img_size', lang('nw_max_img_size'), 'number|xss_clean');
		$this->form_validation->set_rules('max_img_width', lang('nw_max_img_width'), 'number|xss_clean');
		$this->form_validation->set_rules('max_img_height', lang('nw_max_img_height'), 'number|xss_clean');
		$this->form_validation->set_rules('max_img_disp_width', lang('nw_max_img_disp_width'), 'number|xss_clean');
		$this->form_validation->set_rules('max_img_disp_height', lang('nw_max_img_disp_height'), 'number|xss_clean');

		if ($this->form_validation->run() === false)
		{
			return false;
		}

		$data = array(
			array('name' => 'news.allow_attachments', 'value' => ($this->input->post('allow_attachments')) ? 1 : -1),
			array('name' => 'news.upload_dir_path', 'value' => $this->input->post('upload_dir_path')),
			array('name' => 'news.upload_dir_url', 'value' => $this->input->post('upload_dir_url')),
			array('name' => 'news.max_img_size', 'value' => $this->input->post('max_img_size')),
			array('name' => 'news.max_img_width', 'value' => $this->input->post('max_img_width')),
			array('name' => 'news.max_img_height', 'value' => $this->input->post('max_img_height')),
			array('name' => 'news.max_img_disp_width', 'value' => $this->input->post('max_img_disp_width')),
			array('name' => 'news.max_img_disp_height', 'value' => $this->input->post('max_img_disp_height')),
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
