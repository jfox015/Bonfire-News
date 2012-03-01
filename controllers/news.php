<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class News extends Front_Controller {

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

    public function get_article_list($limit=-1, $offset=0) {

        Assets::add_module_css('news','assets/css/news.css');

        $this->load->model('activities/Activity_model', 'activity_model', true);
        $this->load->library('users/auth');
        $output = '';

        if ($limit != -1 && $offset == 0) {
            $this->db->limit($limit);
        } else if ($limit != -1 && $offset > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('date', "asc");
        $articles = $this->news_model->find_all_by('status_id',3);
        echo($this->db->last_query()."<br />");
        if (!is_array($articles) || !count($articles)) {
            $output = 'No Articles  found.';
            $this->activity_model->log_activity($this->auth->user_id(), 'Get Articles: failed. No article were found.', 'news');
        }
        return $articles;
    }

	//--------------------------------------------------------------------
	
	public function get_articles($limit=-1, $offset=0) {
		
		Assets::add_module_css('news','news.css');

        $this->load->model('activities/Activity_model', 'activity_model', true);
        $this->load->library('users/auth');
		//$articles = $this->news_model->find_all();
		$output = '';
        $articles = $this->news_model->get_articles(true,$limit,$offset);
		if (is_array($articles) && count($articles)) {
            $settings = $this->settings_lib->find_all_by('module','news');
            foreach ($articles as $article) {
				$article->asset_url = $settings['news.upload_dir_url'];
				$article->author_name = $this->auth->username($article->author);
				$output .= $this->load->view('news/index',array('article'=>$article),true);
			}
		} else {
			$output = 'No Articles not found.';
			$this->activity_model->log_activity($this->auth->user_id(), 'Get Articles: failed. No article were found.', 'news');
		}
		return $output;
	}
	
	//--------------------------------------------------------------------
	
	public function article($article_id = false) {
		
		Assets::add_module_css('news','news.css');
		
		if ($article_id === false) {
			return false;
		}
		$article = false;
		if (($article = $this->news_model->get_article($article_id)) !== false) {
            $this->load->library('users/auth');
            $article->author_name = $this->auth->username($article->author);
            $settings = $this->settings_lib->find_all_by('module','news');
            $article->asset_url = $settings['news.upload_dir_url'];
            Template::set('article',$article);
		} else {
            Template::set('error',"Article not found");
			$this->activity_model->log_activity($this->auth->user_id(), 'Get Article: '. $article_id .' failed. no article found.', 'news');
		}
        Template::set_view('news/index');
        Template::render();
	}
	
}

// End User Admin class