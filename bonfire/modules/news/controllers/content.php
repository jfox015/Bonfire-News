<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2012-14 Jeff Fox

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

	/**
	 * @var array  Holds Settings from Database.
	 */
	private  $_settings;

	/**
	 * @var string Holds the Realpath for uploading Attachments
	 */
	private  $_news_dir;

	//--------------------------------------------------------------------

	/**
	 * Checks Auth Permissions and setups all java-scripts and other stuff
	 *
	 * @property CI_Pagination $pagination
	 * @property MY_Model      $my_model
	 * @property news_model    $news_model
	 * @property author_model  $author_model
	 *
	 * @return   void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('News.Content.View');

		$this->load->model( array('news/news_model', 'news/author_model' ));

		$this->lang->load('news');

		$this->load->helper('author');

		$this->load->library('pagination');

		$this->_settings = $this->settings_model->select('name,value')->find_all_by('module', 'news');

		Assets::add_css( array(
			Template::theme_url('js/editors/markitup/skins/markitup/style.css'),
			Template::theme_url('js/editors/markitup/sets/default/style.css'),
			css_path() . 'chosen.css',
			css_path() . 'bootstrap-datepicker.css'

		));

		Assets::add_js( array(
			Template::theme_url('js/editors/markitup/jquery.markitup.js'),
			Template::theme_url('js/editors/markitup/sets/default/set.js'),
			js_path() . 'chosen.jquery.min.js',
			js_path() . 'bootstrap-datepicker.js'
		));

		if ($this->_settings['news.allow_attachments'] == 1 && (!isset($this->_settings['news.upload_dir_path']) || empty($this->_settings['news.upload_dir_path'])))
		{
			Template::set_message(lang('us_upload_dir_unspecified'), 'error');
			log_message('error', lang('us_upload_dir_unspecified'));
		}
		else
		{
			$the_path = $this->_settings['news.upload_dir_path'];
			$this->_news_dir = realpath( $the_path );

			if ( !is_dir( $this->_news_dir ) && ! is_writeable( $this->_news_dir ) )
			{
				$err = sprintf(lang('us_upload_dir_unwritable'),$this->_news_dir);
				Template::set_message($err, 'error');
				log_message('error', $err);
			}
		}
		Template::set_block('sub_nav', 'content/_sub_nav');
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
		
		$categories = $this->news_model->get_news_categories();
		Template::set('categories', $categories);
		
		$statuses = $this->news_model->get_news_statuses();
		Template::set('statuses', $statuses);

		$this->load->model('users/user_model');
		$users = $this->user_model->find_all();
		Template::set('users', $users);

		$offset = $this->uri->segment(5);

		// Do we have any actions?
		if ($action = $this->input->post('submit'))
		{
			$checked = $this->input->post('checked');

			switch(strtolower($action))
			{
				case 'publish':
					$this->change_status($checked, 3);
					break;
				case 'review':
					$this->change_status($checked, 2);
					break;
				case 'archive':
					$this->change_status($checked, 4);
					break;
				case 'reject':
					$this->change_status($checked, 5);
					break;
				case 'delete':
					$this->delete($checked);
					break;
			}
		}

		$where = array();

		// Filters
		$filter = $this->input->get('filter');
		switch($filter)
		{
			case 'draft':
				$where['news_articles.status_id'] = 1;
				break;
			case 'review':
				$where['news_articles.status_id'] = 2;
				break;
			case 'deleted':
				$where['news_articles.deleted'] = 1;
				break;
			case 'archived':
				$where['news_articles.status_id'] = 4;
				break;
			case 'rejected':
				$where['news_articles.status_id'] = 5;
				break;
			case 'category':
				$category_id = (int)$this->input->get('category_id');
				$where['news_articles.category_id'] = $category_id;

				foreach ($categories as $category)
				{
					if ($category->id == $category_id)
					{
						Template::set('filter_category', $category->category);
						break;
					}
				}
				break;
			case 'user':
				$user_id = (int)$this->input->get('user_id');
				$where['news_articles.author'] = $user_id;

				foreach ($users as $user)
				{
					if ($user->id == $user_id)
					{
						Template::set('filter_author', $user->display_name);
						break;
					}
				}
				break;
			default:
				$where['news_articles.deleted'] = 0;
				$this->user_model->where('news_articles.deleted', 0);
				$where['news_articles.status_id'] = 3;
				$this->user_model->where('news_articles.status_id', 3);
				break;
		}

		$this->load->helper('ui/ui');

		$this->news_model->limit($this->limit, $offset)->where($where);
		$this->news_model->select('news_articles.id, title, author, date, date_published, category_id, status_id');

		Template::set('articles', $this->news_model->find_all());

		// Pagination
		$this->load->library('pagination');

		$this->news_model->where($where);
		$total_articles = $this->news_model->count_all();

		$this->pager['base_url'] = site_url(SITE_AREA .'/content/news/index');
		$this->pager['total_rows'] = $total_articles;
		$this->pager['per_page'] = $this->limit;
		$this->pager['uri_segment']	= 5;

		$this->pagination->initialize($this->pager);

		Template::set('current_url', current_url());
		Template::set('filter', $filter);
		Template::set('users', $this->author_model->get_users_select() );
		
		Template::set('toolbar_title', lang('us_article_management'));
		Template::render();
	}

	//--------------------------------------------------------------------

	public function create()
	{
		$settings = $this->_settings;
        $this->auth->restrict('News.Content.Add');

        if ($this->input->post('submit'))
		{
			$uploadData = array();
			$upload = true;
            if (isset($_FILES['attachment']) && is_array($_FILES['attachment']) && $_FILES['attachment']['error'] != 4)
            {
				$uploadData = $this->handle_upload( );
				if (isset($uploadData['error']) && !empty($uploadData['error']))
				{
					$upload = false;
				}
			}
			if ((count($uploadData) > 0 && $upload) || (count($uploadData) == 0 && $upload))
			{
				if ($id = $this->save_article($uploadData))
				{
					$article = $this->news_model->find($id);

                    $this->load->model('activities/activity_model');
                    $this->activity_model->log_activity($this->current_user->id, sprintf(lang('us_log_article_create'), $article->id), 'news');

                    Template::set_message(lang('us_article_created'), 'success');
				}
				else
				{
					Template::set_message(sprintf(lang('us_article_create_fail'),$this->news_model->error));
				}
			}
			else
			{
				Template::set_message(sprintf(lang('us_log_file_save_fail'), $uploadData['error']));
			}
		}

		Template::set('categories', $this->news_model->get_news_categories_select());
		Template::set('statuses', $this->news_model->get_news_statuses_select() );
		Template::set('users', $this->author_model->get_users_select() );
		Template::set('settings', $settings);

		Template::set('toolbar_title', lang('us_create_news'));
		Template::set_view('content/news_form');
		Template::render();
	}

	//--------------------------------------------------------------------

	public function edit()
	{
		$settings = $this->_settings;
		$this->auth->restrict('News.Content.Manage');

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
            if (isset($_FILES['attachment']) && is_array($_FILES['attachment']) && $_FILES['attachment']['error'] != 4)
            {
				$uploadData = $this->handle_upload( );
				if (isset($uploadData['error']) && !empty($uploadData['error']))
				{
					$upload = false;
				}
			}
			if ((count($uploadData) && $upload) || (count($uploadData) == 0 && $upload)) {

				if ($this->save_article($uploadData, 'update', $article_id))
				{
					$article = $this->news_model->find($article_id);
					$this->load->model('activities/activity_model');

                    $this->activity_model->log_activity($this->current_user->id, sprintf(lang('us_log_article_update'),$article->id), 'news');
                    Template::set_message(lang('us_article_updated'), 'success');
				}
				else
				{
					Template::set_message(sprintf(lang('us_article_update_fail'), $this->news_model->error));
					$this->activity_model->log_activity($this->current_user->id, sprintf(lang('us_log_article_update_fail'),$article->id), 'news');
				}
			}
			else
			{
				Template::set_message(sprintf(lang('us_log_file_save_fail'),$uploadData['error']));
			}
		}

		$article = $this->news_model->find($article_id);
		if (isset($article) && has_permission('News.Content.Manage'))
		{
			Template::set('article', $article);

			Template::set('current_user', $this->current_user);
			Template::set('categories', $this->news_model->get_news_categories_select());
			Template::set('statuses', $this->news_model->get_news_statuses_select() );
			Template::set('users', $this->author_model->get_users_select() );
			Template::set('settings', $settings);
			Template::set_view('content/news_form');
		}
		else
		{
			Template::set_message(lang('article_unauthorized'), 'error');
			redirect(SITE_AREA .'/content/news');
		}

		Template::set('settings', $settings );
		Template::set('toolbar_title', lang('us_edit_news'));
		Template::render();
	}

	//--------------------------------------------------------------------

	public function delete($checked = false)
	{
		if (isset($checked) && is_array($checked) && count($checked))
		{
			$this->auth->restrict('News.Content.Manage');
			$success = true;
			$deletedCount = 0;
			$errorCount = 0;
			$this->load->model('activities/activity_model');
						
			foreach($checked as $article_id){
				if ($success)
				{
					$article = $this->news_model->find($article_id);
					if (isset($article))
					{
						if ($this->news_model->delete($article_id))
						{
							$article = $this->news_model->find($article_id);
							$this->activity_model->log_activity($this->current_user->id, sprintf(lang('us_log_article_delete'),$id), 'news');
							$deletedCount++;
						}
						else
						{
							$success = false;
							$errorCount;
							$this->activity_model->log_activity($this->current_user->id, sprintf(lang('us_log_article_delete_fail'),$article_id), 'news');
						}  
					}
				}
				else
				{
					break;
				}
			}
			if($success){
				Template::set_message(sprintf(lang('us_articles_deleted'),$deletedCount) , 'success');
			}
			else
			{
				Template::set_message(sprintf(lang('us_article_delete_fail'),$this->news_model->error), 'error');
			}
		}
		else
		{
			Template::set_message(lang('us_empty_article_list'), 'error');
		}

		redirect(SITE_AREA .'/content/news');
	}

	//--------------------------------------------------------------------

	public function purge()
	{
        $this->auth->restrict('News.Content.Manage');
        $article_id = $this->uri->segment(5);
		// Handle a single-article purge
		if (!empty($article_id) && is_numeric($article_id))
		{
			$this->news_model->delete($article_id, true);
		}
		// Handle purging all deleted articles...
		else
		{
			// Find all deleted articles
			$articles = $this->news_model->where('news_articles.deleted', 1)->find_all(true);
			if (is_array($articles))
			{
				foreach ($articles as $article)
				{
					// DELETE ATTACHMENTS IF THEY EXIST
					if (isset($article->attachment) && !empty($article->attachment))
					{
						$this->delete_attachments ( $article->attachment );
					}
					$this->news_model->delete($article->id, true);
				}
			}
		}

		Template::set_message(lang('us_log_article_purge'), 'success');

		Template::redirect(SITE_AREA .'/content/news');
	}

	//--------------------------------------------------------------------

	public function restore()
	{
		$id = $this->uri->segment(5);

		if ($this->news_model->update($id, array('news_articles.deleted'=>0)))
		{
			Template::set_message(lang('us_article_restore'), 'success');
		}
		else
		{
			Template::set_message(sprintf(lang('us_article_restore_fail'), $this->news_model->error), 'error');
		}

		Template::redirect(SITE_AREA .'/content/news');
	}

	//--------------------------------------------------------------------

	public function remove_attachment()
	{
		$id = $this->uri->segment(5);
		$settings = $this->_settings;

		$success = false;

		// Handle a single-user purge
		if (!empty($article_id) && is_numeric($article_id))
		{
			$article = $this->news_model->find($article_id);
			if (isset($article) && isset($article->attachment))
			{
				$this->delete_attachments ( $article->attachment );
				$data = array('attachment'=>'');
				$success = $this->news_model->update($article_id, $data);
			}
		}
		if (!$success)
		{
			Template::set_message(lang('us_log_file_remove'), 'error');
		}
		else
		{
			Template::set_message(lang('us_log_file_remove_fail'), 'success');
		}
		$this->edit();

	}

	//--------------------------------------------------------------------

	/*--------------------------------------------------------------------
	/	PRIVATE FUNCTIONS
	/-------------------------------------------------------------------*/
	private function change_status($checked = false, $status_id = 1)
	{
		if ($checked === false)
		{
			return;
		}
        $this->auth->restrict('News.Content.Manage');
        foreach ($checked as $article_id)
		{
			$this->news_model->update($article_id,array('status_id'=>$status_id));
		}
	}
	
	//--------------------------------------------------------------------

	private function handle_upload($path = '')
	{
		$settings  = $this->_settings;
		$file_path = $this->_news_dir;

		$config['upload_path']		= $file_path;
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

	public function save_article($uploadData = false, $type='insert', $id=0)
	{
		if ($type == 'insert')
		{
			$this->form_validation->set_rules('title', 'lang:us_title', 'required|trim|max_length[255]|strip_tags|xss_clean');
			$this->form_validation->set_rules('body', 'lang:us_body', 'required|trim|xss_clean');
			$this->form_validation->set_rules('date', 'lang:us_date', 'required|trim|strip_tags|xss_clean');
		} else {
			$this->form_validation->set_rules('title', 'lang:us_title', 'trim|max_length[255]|strip_tags|xss_clean');
			$this->form_validation->set_rules('body', 'lang:us_body', 'trim|xss_clean');
			$this->form_validation->set_rules('date', 'lang:us_date', 'trim|strip_tags|xss_clean');
		}

		$this->form_validation->set_rules('image_caption', 'lang:us_image_caption', 'trim|strip_tags|max_length[255]|xss_clean');
		$this->form_validation->set_rules('image_title', 'lang:us_image_title', 'trim|strip_tags|max_length[255]|xss_clean');
		$this->form_validation->set_rules('image_alttag', 'lang:us_image_alttag', 'trim|strip_tags|max_length[255]|xss_clean');
        $this->form_validation->set_rules('image_align', 'lang:us_image_align', 'numeric|xss_clean');
        $this->form_validation->set_rules('tags', 'lang:us_tags', 'trim|strip_tags|max_length[255]|xss_clean');
		$this->form_validation->set_rules('author', 'lang:us_author', 'numeric|xss_clean');
		$this->form_validation->set_rules('date_published', 'lang:us_publish_date', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('category_id', 'lang:us_category', 'numeric|xss_clean');
		$this->form_validation->set_rules('status_id', 'lang:us_status', 'numeric|xss_clean');

		if ($this->form_validation->run() === false)
		{
			return false;
		}

		$date            = $this->format_dates ( $this->input->post('date') );
		$date_published  = $this->format_dates ( $this->input->post('date_published') );

		$data = array(
					'title'=>$this->input->post('title'),
					'body'=>$this->input->post('body'),
					'date'=>$date,
					'tags'=>$this->input->post('tags'),
					'author'=>$this->input->post('author'),
					'image_align'=>$this->input->post('image_align'),
					'image_alttag'=>$this->input->post('image_alttag'),
					'image_title'=>$this->input->post('image_title'),
					'image_caption'=>$this->input->post('image_caption'),
					'date_published'=>$date_published,
					'category_id'=>(($this->input->post('category_id'))?$this->input->post('category_id'):1),
					'status_id'=>(($this->input->post('status_id'))?$this->input->post('status_id'):1)
				);


		if ($uploadData !== false && is_array($uploadData) && count($uploadData) > 0)
		{
			$data = $data + array('attachment'=>serialize($uploadData['data']));
		}
		if ($type == 'insert')
		{
			$thread_id = 0;
			if (in_array('comments',module_list(true))) 
			{
				if(!isset($this->comments_model)) 
				{
					$this->load->model('comments/comments_model');
				}
				$thread_id = $this->comments_model->new_comments_thread('news');
			}
			$data = $data + array('comments_thread_id'=>$thread_id,'created_by'=>$this->current_user->id);
			return $this->news_model->insert($data);
		}
		else	// Update
		{
			return $this->news_model->update($id, $data);
		}
	}

	//--------------------------------------------------------------------

	/**
	 * My Format Date function that really does nothing but turns a string into a int.
	 *
	 * @TODO: Actually rewrite this to do what it's supposed to do.
	 * @param string $date  The date to be converted
	 * @param bool $text    Unused atm
	 * @return int          Returns strtotime'd date.
	 */
	private function format_dates ( $date = '', $text = true )
	{
		if ( $date == '' )
		{
			return time();
		}

		return strtotime($date);

	}

	//--------------------------------------------------------------------

	/**
	 * Deletes Attachments or dies trying to. ( Chuck Norris would just chop them off I'm sure )
	 *
	 * @param $attachment Serialized data for attachment
	 */
	private function delete_attachments( $attachment )
	{
		$attachment = unserialize( $attachment );
		$file_dir = $this->_news_dir;

		if (file_exists( $file_dir . DIRECTORY_SEPARATOR . $attachment['file_name']) )
		{
			$deleted = unlink( $file_dir . DIRECTORY_SEPARATOR .$attachment['file_name']);
			if ( $deleted === false )
			{
				$err = sprintf(lang('us_log_file_detatch_fail'), $attachment['file_name']);
				Template::set_message($err, 'error');
				log_message('error', $err);
			}
			unset ( $deleted );
		}

		if ( isset($attachment['image_thumb']) && file_exists( $file_dir .DIRECTORY_SEPARATOR .$attachment['image_thumb']))
		{
			$deleted = unlink($file_dir . DIRECTORY_SEPARATOR  . $attachment['image_thumb'] );
			if ( $deleted === false )
			{
				$err = sprintf(lang('us_log_file_detatch_fail'), $attachment['image_thumb']);
				Template::set_message($err, 'error');
				log_message('error', $err);
			}

		}
	}

	//--------------------------------------------------------------------
}

// End of News Content Controller
// End of file modules/news/controllers/content.php