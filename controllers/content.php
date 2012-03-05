<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2012 Jeff Fox

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

class Content extends Admin_Controller {

	//--------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Site.Content.View');
		$this->auth->restrict('Site.News.Manage');

		$this->load->model('news/news_model');

		$this->lang->load('news');

		$this->load->library('pagination');
	}

	//--------------------------------------------------------------------

	// Remap any calls to your controller and checks if a method exists, if it does then it calls the method otherwise it shows a 404 page.
	public function _remap($method)
	{
		if (method_exists($this, $method))
		{
			$this->$method();
		}
		else
		{
			show_404();
		}
	}

	//--------------------------------------------------------------------

	public function index()
	{
		$offset = $this->uri->segment(4);

		Assets::add_js($this->load->view('content/news_js', null, true), 'inline');

		$total_articles = $this->news_model->count_all();

		$this->pager['base_url'] = site_url(SITE_AREA .'/content/news/index');
		$this->pager['total_rows'] = $total_articles;
		$this->pager['per_page'] = $this->limit;
		$this->pager['uri_segment'] = 4;

		$this->pagination->initialize($this->pager);

		// Was a filter set?
		if ($this->input->post('filter_submit') && $this->input->post('filter_by_category_id'))
		{
			$category_id = $this->input->post('filter_by_category_id');

			$this->db->where('category_id', $category_id);
			Template::set('filter', $category_id);
		}

		$this->db->order_by('date', 'desc');

		Template::set('articles', $this->news_model->limit($this->limit, $offset)->find_all());
		Template::set('total_articles', $total_articles);
		Template::set('draft_articles', $this->news_model->count_all_by_field('status_id',1,false));
		Template::set('published_articles', $this->news_model->count_all_by_field('status_id',3,false));
		Template::set('deleted_articles', $this->news_model->count_all(true));
		Template::set('category', $this->news_model->get_default_category());
		Template::set('categories', $this->news_model->get_news_categories());
		Template::set('status', $this->news_model->get_default_status());
		Template::set('article_count', $this->news_model->count_all());

		$this->load->helper('ui/ui');

		Template::set('toolbar_title', lang('article_management'));
		Template::render();
	}

	//--------------------------------------------------------------------

	public function news_options()
	{
		if ($this->input->post('submit'))
		{
			$this->form_validation->set_rules('allow_attachments', lang('nw_settings_attachAllow'), 'number|xss_clean');
			$this->form_validation->set_rules('upload_dir_path', lang('nw_upload_dir_path'), 'required|xss_clean');
			$this->form_validation->set_rules('upload_dir_url', lang('nw_upload_dir_url'), 'number|xss_clean');
			$this->form_validation->set_rules('max_img_size', lang('nw_max_img_size'), 'number|xss_clean');
			$this->form_validation->set_rules('max_img_width', lang('nw_max_img_width'), 'number|xss_clean');
			$this->form_validation->set_rules('max_img_height', lang('nw_max_img_height'), 'number|xss_clean');
			$this->form_validation->set_rules('max_img_disp_width', lang('nw_max_img_disp_width'), 'number|xss_clean');
			$this->form_validation->set_rules('max_img_disp_height', lang('nw_max_img_disp_height'), 'number|xss_clean');

			if ($this->form_validation->run() !== FALSE)
			{
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

				// Destroy the saved update message in case they changed update preferences.
				if ($this->cache->get('update_message'))
				{
					if (!is_writeable(FCPATH.APPPATH.'cache/'))
					{
						$this->cache->delete('update_message');
					}
				}
				// Log the activity
				$this->activity_model->log_activity($this->auth->user_id(), lang('bf_act_settings_saved').': ' . $this->input->ip_address(), 'news');

				// save the settings to the DB
				if ($this->settings_model->update_batch($data, 'name'))
				{
					// Success, so reload the page, so they can see their settings
					Template::set_message('News settings successfully saved.', 'success');
					redirect(SITE_AREA .'/content/news');
				}
				else
				{
					Template::set_message('There was an error saving the news settings.', 'error');
				}
			}
		}

		$settings = $this->settings_model->select('name,value')->find_all_by('module', 'news');
		Template::set('settings', $settings);
		Assets::add_css(Template::theme_url('css/jquery.ui.datepicker.css'),'screen');
		Template::set('toolbar_title', lang('sim_setting_title'));
		Template::set_view('news/content/options_form');
		Template::render();
	}

	//--------------------------------------------------------------------

	public function create()
	{
		$settings = $this->settings_model->select('name,value')->find_all_by('module', 'news');
		$this->auth->restrict('Site.News.Add');

		if ($this->input->post('submit'))
		{
			$uploadData = array();
			$upload = true;
			if (isset($_FILES['attachment']) && !empty($_FILES['attachment']))
			{
				$uploadData = $this->handle_upload($settings['news.upload_dir_path']);
				if (isset($uploadData['error']) && !empty($uploadData['error']))
				{
					$upload = false;
				}
			}
			if ($upload)
			{
				if ($id = $this->save_article($uploadData))
				{
					$article = $this->news_model->find($id);
					$log_name = $this->settings_lib->item('auth.use_own_names') ? $this->auth->user_name() : ($this->settings_lib->item('auth.use_usernames') ? $this->auth->user_name() : $this->auth->email());
					$this->activity_model->log_activity($this->auth->user_id(), lang('us_log_create').' '.$log_name, 'users');

					Template::set_message('Article successfully created.', 'success');
					Template::redirect(SITE_AREA .'/content/news');
				}
				else
				{
					Template::set_message('There was a problem creating the article: '. $this->news_model->error);
				}
			}
			else
			{
				Template::set_message('There was a problem saving the file attachment: '. $uploadData['error']);
			}
		}
		Template::set('categories', $this->news_model->get_news_categories());
		Template::set('statuses', $this->news_model->get_news_statuses());
		Template::set('settings', $settings);

		// if a date field hasn't been included already then add in the jquery ui files
		Assets::add_js(Template::theme_url('js/editors/nicEdit.js'));

		Template::set('toolbar_title', lang('us_create_news'));
		Template::set_view('content/news_form');
		Template::render();
	}

	//--------------------------------------------------------------------

	public function edit()
	{
		$settings = $this->settings_lib->find_all_by('module','news');
		$this->auth->restrict('Site.News.Manage');

		$article_id = $this->uri->segment(5);
		if (empty($article_id))
		{
			Template::set_message(lang('us_empty_id'), 'error');
			redirect(SITE_AREA .'/content/news');
		}

		if ($this->input->post('submit'))
		{
			$uploadData = array();
			$upload = true;
			if (isset($_FILES['attachment']) && !empty($_FILES['attachment']))
			{
				$uploadData = $this->handle_upload($settings['news.upload_dir_path']);
				if (isset($uploadData['error']) && !empty($uploadData['error']))
				{
					$upload = false;
				}
			}
			if ($upload)
			{
				if ($this->save_article($uploadData, 'update', $article_id))
				{
					$article = $this->news_model->find($article_id);
					$log_name = $this->settings_lib->item('auth.use_own_names') ? $this->auth->user_name() : ($this->settings_lib->item('auth.use_usernames') ? $this->auth->user_name() : $this->auth->email());
					$this->activity_model->log_activity($this->auth->user_id(), lang('us_log_edit') .': '.$log_name, 'users');

					Template::set_message('Article successfully updated.', 'success');
				}
				else
				{
					Template::set_message('There was a problem updating the article: '. $this->news_model->error);
				}
			}
			else
			{
				Template::set_message('There was a problem saving the file attachment: '. $uploadData['error']);
			}
		}

		$article = $this->news_model->find($article_id);
		if (isset($article) && has_permission('Site.News.Manage'))
		{
			Template::set('article', $article);
			Template::set('categories', $this->news_model->get_news_categories());
			Template::set('statuses', $this->news_model->get_news_statuses());
			Template::set_view('content/news_form');
		}
		else
		{
			Template::set_message(sprintf(lang('us_unauthorized')), 'error');
			redirect(SITE_AREA .'/content/news');
		}

		Template::set('toolbar_title', lang('us_edit_news'));
		Template::render();
	}

	//--------------------------------------------------------------------

	public function delete()
	{
		$id = $this->uri->segment(5);

		if (!empty($id))
		{
			$this->auth->restrict('Site.News.Manage');

			$user = $this->news_model->find($id);
			if (isset($user) && has_permission('Permissions.'.$user->role_name.'.Manage') && $user->id != $this->auth->user_id())
			{
				if ($this->news_model->delete($id))
				{
					$user = $this->news_model->find($id);
					$log_name = $this->settings_lib->item('auth.use_own_names') ? $this->auth->user_name() : ($this->settings_lib->item('auth.use_usernames') ? $user->username : $user->email);
					$this->activity_model->log_activity($this->auth->user_id(), lang('us_log_delete') . ': '.$log_name, 'users');
					Template::set_message('The article was successfully deleted.', 'success');
				}
				else
				{
					Template::set_message('Article could not deletes: '. $this->news_model->error, 'success');
				}
			}
			else
			{
				if ($user->id == $this->auth->user_id())
				{
					Template::set_message(lang('us_self_delete'), 'error');
				}
				else
				{
					Template::set_message(sprintf(lang('us_unauthorized'),$user->role_name), 'error');
				}
			}
		}
		else
		{
			Template::set_message(lang('us_empty_id'), 'error');
		}

		redirect(SITE_AREA .'/content/news');
	}

	//--------------------------------------------------------------------

	public function deleted()
	{
		$this->db->where('news_articles.deleted !=', 0);
		Template::set('articles', $this->news_model->find_all(true));
		Template::render();
	}

	//--------------------------------------------------------------------

	public function drafts()
	{
		// $this->db->where('news_articles.deleted', 0);
		Template::set('articles', $this->news_model->find_all_by('status_id',1));
		Template::render();
	}

	//--------------------------------------------------------------------

	public function published()
	{
		//$this->db->where('news_articles.deleted', 0);
		Template::set('articles', $this->news_model->find_all_by('status_id',3));
		Template::render();
	}

	//--------------------------------------------------------------------

	public function purge()
	{
		$article_id = $this->uri->segment(5);

		// Handle a single-user purge
		if (!empty($article_id) && is_numeric($article_id))
		{
			$this->news_model->delete($article_id, true);
		}
		// Handle purging all deleted articles...
		else
		{
			// Find all deleted accounts
			$articles = $this->news_model->where('news_articles.deleted', 1)->find_all(true);

			if (is_array($articles))
			{
				foreach ($articles as $article)
				{
					$this->news_model->delete($article->id, true);
				}
			}
		}

		Template::set_message('Articles Purged.', 'success');

		Template::redirect(SITE_AREA .'/content/news');
	}

	//--------------------------------------------------------------------

	public function set_status()
	{
		$id = $this->uri->segment(5);
		$status = $this->uri->segment(6);

		if ($this->news_model->update($id, array('news_articles.status_id'=>$status)))
		{
			Template::set_message('Article status updated successfully.', 'success');
		}
		else
		{
			Template::set_message('Unable to change article status: '. $this->news_model->error, 'error');
		}

		Template::redirect(SITE_AREA .'/content/news');
	}

	//--------------------------------------------------------------------

	public function restore()
	{
		$id = $this->uri->segment(5);

		if ($this->news_model->update($id, array('news_articles.deleted'=>0)))
		{
			Template::set_message('Article successfully restored.', 'success');
		}
		else
		{
			Template::set_message('Unable to restore article: '. $this->news_model->error, 'error');
		}

		Template::redirect(SITE_AREA .'/content/news');
	}

	//--------------------------------------------------------------------

	public function remove_attachment()
	{
		$id = $this->uri->segment(5);
		$settings = $this->settings_lib->find_all_by('module','news');
		$success = false;

		// Handle a single-user purge
		if (!empty($article_id) && is_numeric($article_id))
		{
			$article = $this->news_model->find($article_id);
			if (isset($article) && isset($article->attachment))
			{
				$attachment = unserialize($article->attachment);

				if (file_exists($settings['news.upload_dir_path'].PATH_SEPERATOR.$attachment['file_name']))
				{
					unlink($settings['news.upload_dir_path'].PATH_SEPERATOR.$attachment['file_name']);
				}
				if (isset($attachment['image_thumb']) && file_exists($settings['news.upload_dir_path'].PATH_SEPERATOR.$attachment['image_thumb']))
				{
					unlink($settings['news.upload_dir_path'].PATH_SEPERATOR.$attachment['image_thumb']);
				}
				$data = array('attachment'=>'');
				$success = $this->news_model->update($article_id, $data);
			}
		}
		if (!$success)
		{
			Template::set_message("Attachment removal failed.", 'error');
		}
		else
		{
			Template::set_message("Attachment removed.", 'success');
		}
		$this->edit();

	}

	//--------------------------------------------------------------------

	/*--------------------------------------------------------------------
	/	PRIVATE FUNCTIONS
	/-------------------------------------------------------------------*/
	private function handle_upload($path = '')
	{
		$settings = $this->settings_lib->find_all_by('module','news');

		$config['upload_path']		= (empty($path) ? $settings['news.upload_dir_path'] : $path);
		$config['allowed_types']	= 'gif|jpg|png';
		$config['max_size']			= intval($settings['news.max_img_size']);
		$config['max_width']		= intval($settings['news.max_img_width']);
		$config['max_height']		= intval($settings['news.max_img_height']);

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('attachment'))
		{
			return array('error'=>$this->upload->display_errors());
		}
		else
		{
			$data = $this->upload->data();
			$max_width		= intval($settings['news.max_img_disp_width']);
			$max_height 	= intval($settings['news.max_img_disp_height']);
			$img_width 		= intval($data['image_width']);
			$img_height 	= intval($data['image_height']);
			if ($img_width > $max_width || $img_height > $max_height)
			{
				$config['image_library'] 	= 'gd2';
				$config['quality']			= '75%';
				$config['source_image']		= $data['full_path'];
				$config['new_image']		= $data['file_path'];
				$config['create_thumb']		= TRUE;
				$config['thumb_marker']		= "_th";
				$config['maintain_ratio']	= TRUE;
				$config['master_dim']		= 'auto';
				$config['height']			= $max_height;
				$config['width']			= $max_width;

				$this->load->library('image_lib', $config);

				if ( ! $this->image_lib->resize())
				{
					return array('error'=>$this->image_lib->display_errors());
				}
				$data['image_thumb'] = $data['raw_name']."_th".$data['file_ext'];
			}
			return array('data'=>$data);
		}
	}

	//--------------------------------------------------------------------

	private function save_article($uploadData = false, $type='insert', $id=0)
	{
		$db_prefix = $this->db->dbprefix;

		if ($type == 'insert')
		{
			$this->form_validation->set_rules('title', 'Title', 'required|trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('body', 'Body', 'required|trim|xss_clean');
			$this->form_validation->set_rules('date', 'Article Date', 'required|trim|strip_tags|xss_clean');
			}
		else
		{
			$this->form_validation->set_rules('title', 'Title', 'trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('body', 'Body', 'trim|xss_clean');
			$this->form_validation->set_rules('date', 'Article Date', 'trim|strip_tags|xss_clean');
		}

		$this->form_validation->set_rules('image_caption', 'Caption', 'trim|strip_tags|max_length[255]|xss_clean');
		$this->form_validation->set_rules('tags', 'Tags', 'trim|strip_tags|max_length[255]|xss_clean');
		$this->form_validation->set_rules('attachment', 'Attachment Path', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('image_align', 'Image Alignment', 'number|xss_clean');
		$this->form_validation->set_rules('author', 'Author', 'number|xss_clean');
		$this->form_validation->set_rules('date_published', 'Publish Date', 'number|xss_clean');
		$this->form_validation->set_rules('category_id', 'Category', 'number|xss_clean');
		$this->form_validation->set_rules('status_id', 'Status', 'number|xss_clean');

		if ($this->form_validation->run() === false)
		{
			return false;
		}
		if (!function_exists('textDateToInt'))
		{
			$this->load->helper('date');
		}
		$dates = textDateToInt(array('date'=>'','date_published'=>''),$this->input);

		$data = array(
					'title'=>$this->input->post('title'),
					'body'=>$this->input->post('body'),
					'date'=>$dates['date'],
					'tags'=>$this->input->post('tags'),
					'author'=>$this->input->post('author'),
					'image_align'=>$this->input->post('image_align'),
					'date_published'=>$dates['date_published'],
					'category_id'=>(($this->input->post('category_id'))?$this->input->post('category_id'):1),
					'status_id'=>(($this->input->post('status_id'))?$this->input->post('status_id'):1)
				);

		if ($uploadData !== false)
		{
			$data = $data + array('attachment'=>serialize($uploadData['data']));
		}
		if ($type == 'insert')
		{
			return $this->news_model->insert($data);
		}
		else	// Update
		{
			return $this->news_model->update($id, $data);
		}
	}

	//--------------------------------------------------------------------
}

// End User Admin class