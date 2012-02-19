<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class News extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('news_model');
		$this->lang->load('news');

	}

	//--------------------------------------------------------------------
	
	public function index()
	{
		// Load our current settings
		Template::set(read_config('news'));

		$articles = $this->get_articles();
		Template::render();
	}
	
	//--------------------------------------------------------------------
	
	public function get_articles($offset=0,$limit=-1) {
		
		$articles = $this->news_model->find_all();
		$output = '';
		
		if ($limit != -1 && $offset == 0) {
			$this->db->limit($limit);
		} else if ($limit != -1 && $offset > 0) {
			$this->db->limit($offset,$limit);
		}
		$this->db->order_by('date','desc');
		$articles = $this->news_model->find_all();
		if (is_array($articles) && count($articles)) {
			foreach ($articles as $article) {
				$article->author_name = $this->auth->user_name($article->author);
				$output .= $this->load->view('news/index',$article,true);
			}
		} else {
			$output = 'No Articles not found.';
			$this->activity_model->log_activity($this->auth->user_id(), 'Get Articles: failed. No article were found.', 'news');
		}
		return $output;
	}
	
	//--------------------------------------------------------------------
	
	public function get_article($article_id = false) {
		
		if ($article_id === false) {
			return false;
		}
		$article = false;
		if ($article = $this->find($article_id) !== false) {
			return $this->load->view('news/index',$article,true);
		} else {
			return 'Article not found.';
			$this->activity_model->log_activity($this->auth->user_id(), 'Get Article: '. $article_id .' failed. no article found.', 'news');
		}
	}
	
}

// End User Admin class