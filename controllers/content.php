<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

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
	}
	
	//--------------------------------------------------------------------

	public function _remap($method) 
	{ 
		if (method_exists($this, $method))
		{
			$this->$method();
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
		$this->pager['uri_segment']	= 4;
		
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
	
	public function create() 
	{
		$this->auth->restrict('Site.News.Add');
	
		if ($this->input->post('submit'))
		{
			if ($id = $this->save_article())
			{
				$article = $this->news_model->find($id);
				$log_name = $this->settings_lib->item('auth.use_own_names') ? $this->auth->user_name() : ($this->settings_lib->item('auth.use_usernames') ? $this->auth->user_name() : $this->auth->email());
				$this->activity_model->log_activity($this->auth->user_id(), lang('us_log_create').' '.$log_name, 'users');
				
				Template::set_message('Article successfully created.', 'success');
				Template::redirect(SITE_AREA .'/content/news');
			}
			else 
			{
				Template::set_message('There was a problem creating the user: '. $this->news_model->error);
			}
		}
		Template::set('categories', $this->news_model->get_news_categories());
        Template::set('statuses', $this->news_model->get_news_statuses());
        
		// if a date field hasn't been included already then add in the jquery ui files
		Assets::add_js(Template::theme_url('js/editors/xinha_conf.js'));
		Assets::add_js(Template::theme_url('js/editors/xinha/XinhaCore.js'));;

		Template::set('toolbar_title', lang('us_create_news'));
		Template::set_view('content/news_form');
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function edit() 
	{
		$this->auth->restrict('Site.News.Manage');
		
		$article_id = $this->uri->segment(5);
		if (empty($article_id))
		{
			Template::set_message(lang('us_empty_id'), 'error');
			redirect(SITE_AREA .'/content/news');			
		}
		
		if ($this->input->post('submit'))
		{
			if ($this->save_article('update', $article_id))
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
			$articles = $this->news_model->where('news_articles.deleted', 1)
									  ->find_all(true);
		
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

	//--------------------------------------------------------------------
	
	private function save_article($type='insert', $id=0) 
	{
		$db_prefix = $this->db->dbprefix;
		
		if ($type == 'insert')
		{
			$this->form_validation->set_rules('title', 'Title', 'required|trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('body', 'Body', 'required|trim|xss_clean');
			$this->form_validation->set_rules('date', 'Article Date', 'required|trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('image_path', 'Image Attachment Path', 'trim|strip_tags|max_length[255]|xss_clean');
			$this->form_validation->set_rules('tags', 'Tags', 'trim|strip_tags|max_length[255]|xss_clean');
		} 
		else 
		{
			$this->form_validation->set_rules('title', 'Title', 'trim|max_length[255]|xss_clean');
			$this->form_validation->set_rules('body', 'Body', 'trim|xss_clean');
			$this->form_validation->set_rules('date', 'Article Date', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('image_path', 'Image Attachment Path', 'trim|strip_tags|max_length[255]|xss_clean');
			$this->form_validation->set_rules('tags', 'Tags', 'trim|strip_tags|max_length[255]|xss_clean');
		}
		
		$this->form_validation->set_rules('author', 'Author', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('date_published', 'Publish Date', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('category_id', 'Category', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('status_id', 'Status', 'trim|strip_tags|max_length[20]|xss_clean');

		if ($this->form_validation->run() === false)
		{
			return false;
		}
		
		if ($type == 'insert')
		{
			return $this->news_model->insert($_POST);
		}
		else	// Update
		{	
			return $this->news_model->update($id, $_POST);
		}
	}
	
	//--------------------------------------------------------------------
	
	
}

// End User Admin class