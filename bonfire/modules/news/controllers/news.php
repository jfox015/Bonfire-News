<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class News extends Front_Controller {

	private $_settings;
	
	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('news_model');
		$this->lang->load('news');

		$this->_settings = $this->settings_model->select('name,value')->find_all_by('module', 'news');

	}

	//--------------------------------------------------------------------
	
	public function index()
	{
		$articles = $this->get_articles();
		Template::set('articles', $articles);
		Template::set('settings', $this->_settings);
		Template::set('single', false);
		Template::render();
	}

    //--------------------------------------------------------------------

    public function get_article_list($limit=-1, $offset=0)
		{

			Assets::add_module_css('news','assets/css/news.css');

			$this->load->model('activities/Activity_model', 'activity_model', true);

			if ($limit != -1 && $offset == 0)
			{
				$this->db->limit($limit);
			} else if ($limit != -1 && $offset > 0) {
				$this->db->limit($limit, $offset);
			}

			$this->db->order_by('date', 'desc');
			$articles = $this->news_model->find_all_by('status_id',3);

			if (!is_array($articles) || !count($articles))
			{
				$this->activity_model->log_activity($this->current_user->id, 'Get Articles: failed. No article were found.', 'news');
			}

			return $articles;
    }

	//--------------------------------------------------------------------
	
	public function get_articles($limit=-1, $offset=0)
	{

		Assets::add_module_css('news','news.css');

		$this->load->model('activities/Activity_model', 'activity_model', true);

		$output = '';
		$articles = $this->news_model->get_articles(true,$limit,$offset);

		if (is_array($articles) && count($articles))
		{
			$settings = $this->_settings;
			$this->load->helper('author');

			foreach ($articles as $article)
			{
				$article->asset_url = $settings['news.upload_dir_url'];
				$article->author_name = find_author_name($article->author);
				$output .= $this->load->view('news/article',array('article'=>$article,'settings'=>$settings,'single'=>false),true);
			}
		} else {
				$output = 'No Articles found.';
				$this->activity_model->log_activity($this->current_user->id, 'Get Articles: failed. No article were found.', 'news');
		}
		return $output;
	}
	
	//--------------------------------------------------------------------
	
	public function article($article_id = false)
	{
		
		if ($article_id === false)
		{
			return false;
		}

		$settings = $this->_settings;
		Assets::add_module_css('news','news.css');

		if ( ($article = $this->news_model->get_article($article_id)) !== false)
		{
			$this->load->helper('author');
			$article->author_name = find_author_name($article->author);
			$article->asset_url = $settings['news.upload_dir_url'];
			Template::set('article',$article);
			if ( isset ($settings['news.sharing_enabled']) && $settings['news.sharing_enabled'] == 1) {
                Template::set('settings',$settings);
                Template::set('single',true);
                Template::set('scripts',$this->load->view('news/news_articles_js',null,true));
			}
		} else {
			$this->activity_model->log_activity($this->current_user->id, 'Get Article: '. $article_id .' failed. no article found.', 'news');
		}

		Template::set_view('news/article');
		Template::render();
	}
	
}

// End User Admin class