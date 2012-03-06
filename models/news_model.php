<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Class: News_model
	
	The central way to access and perform CRUD on news articles.
*/
class News_model extends BF_Model {

	protected $table		= 'news_articles';
	protected $soft_deletes	= true;
	protected $date_format	= 'int';
	protected $set_modified = true;
	protected $set_created 	= true;

	public function __construct() 
	{
		parent::__construct();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: insert()
		
		Creates a new news article in the database. 
		
		Required parameters sent in the $data array:
			- title
			- body
			
		If no _category_id_ is passed in the $data array, it
		will assign the default category. If no _status_id_ is passed in the $data array, it
		will assign the default status.
		
		Parameters:
			$data	- An array of news article information.
		
		Returns:
			$id	- The ID of the new news article.
	*/
	public function insert($data=array(), $auth = NULL) 
	{
		if (!$this->_function_check(false, $data))
		{
			return false;
		}
		
		if (!isset($data['title']) || empty($data['title']))
		{
			$this->error = 'No Title present.';
			return false;
		}
		
		if (!isset($data['body']) || empty($data['body']))
		{
			$this->error = 'No Body given.';
			return false;
		}
		$data['date'] = strtotime($data['date']);

        $data['attachment'] = (isset($data['attachment']) && !empty($data['attachment']) ? $data['attachment'] : '');
        
		$data['image_align'] = (isset($data['image_align']) && !empty($data['image_align']) ? $data['image_align'] : '');
		
		$data['image_caption'] = (isset($data['image_caption']) && !empty($data['image_caption']) ? $data['image_caption'] : '');

        $data['date_published'] = (isset($data['date_published']) && !empty($data['date_published']) ? strtotime($data['date_published']) : $data['date']);

		$data['author'] = $data['created_by'] = $data['modified_by'] = (isset($data['author']) && !empty($data['author'])) ? $data['author'] : (($auth != NULL) ? $auth->user_id() : 1);
		
		// What's the default category?
		$data['category_id'] = (!isset($data['category_id']))? 1 : $data['category_id'];
		
		// What's the default status?
		$data['status_id'] = (!isset($data['status_id']))? 1 : $data['status_id'];
		
		$id = parent::insert($data);
		
		Events::trigger('after_create_news', $id);
		
		return $id;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: update()
		
		Updates an existing news article.alt combo if both password and pass_confirm are passed in.
			- store the country code
			
		Parameters:
			$id		- An INT with the news article's ID.
			$data	- An array of key/value pairs to update for the news article.
			
		Returns: 
			true/false
	*/
	public function update($id=null, $data=array()) 
	{
		if ($id)
		{
			$trigger_data = array('article_id'=>$id, 'data'=>$data);
			Events::trigger('before_news_update', $trigger_data);
		}
		
		$return = parent::update($id, $data);
		
		if ($return)
		{
			$trigger_data = array('user_id'=>$id, 'data'=>$data);
			Events::trigger('after_news_update', $trigger_data);
		}
		
		return $return;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: find()
		
		Finds an individual news article record. Also returns role information for 
		the news article.
		
		Parameters:
			$id	- An INT with the news article's ID.
			
		Returns:
			An object with the news article's information.
	*/
	public function find($id=null) 
	{
		if (empty($this->selects))
		{
			$this->select($this->table .'.*, category');
		}
	
		$this->db->join('news_categories', 'news_categories.id = news_articles.category_id', 'left');
	
		return parent::find($id);
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: find_all()
		
		Returns all news article records, and their associated category information. 
		
		Parameters:
			$show_deleted	- If false, will only return non-deleted news articles. If true, will
				return both deleted and non-deleted news articles.
				
		Returns:
			An array of objects with each news article's information.
	*/
	public function find_all($show_deleted=false) 
	{
		if (empty($this->selects))
		{
			$this->select($this->table .'.*, category');
		}
	
		if ($show_deleted === false)
		{
			$this->db->where('news_articles.deleted', 0);
		}
		
		$this->db->join('news_categories', 'news_categories.id = news_articles.category_id', 'left');
		$this->db->join('news_status', 'news_status.id = news_articles.status_id', 'left');

		return parent::find_all();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: find_by()
		
		Locates a single news article based on a field/value match, with their category information.
		
		Parameters:
			$field	- A string with the field to match.
			$value	- A string with the value to search for.
			
		Returns:
			An object with the user's info, or false on failure.
	*/
	public function find_by($field=null, $value=null) 
	{
		$this->db->join('news_categories', 'news_categories.id = news_articles.category_id', 'left');
        $this->db->join('news_status', 'news_status.id = news_articles.status_id', 'left');

        if (empty($this->selects))
		{
			$this->select($this->table .'.*, category');
		}
		
		return parent::find_by($field, $value);
	}

    //--------------------------------------------------------------------
    public function get_articles( $published = true, $limit = -1, $offset = 0) {

        if ($limit != -1 && $offset == 0) {
            $this->db->limit($limit);
        } else if ($limit != -1 && $offset > 0) {
            $this->db->limit($offset,$limit);
        }
        $this->db->order_by('date', 'desc');
        if ($published === true) {
            $this->db->where('status_id',3);
        }
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
             $articles = $query->result();
        }
        //$articles = $this->news_model->find_all_by('status_id',3);
        //print ($this->db->last_query()."<br />");
        return $articles;
    }

    //--------------------------------------------------------------------
    public function get_article($article_id = false, $published = true) {

        $article = false;
        if ($article_id === false) {
            $this->errors = "No article ID was received.";
            return false;
        }
        $this->db->where('id',$article_id);
        if ($published === true) {
            $this->db->where('status_id',3);
        }
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            $article = $query->row();
        }
        $query->free_result();
        //$articles = $this->news_model->find_all_by('status_id',3);
        //print ($this->db->last_query()."<br />");
        return $article;
    }

    //--------------------------------------------------------------------
	
	/*
		Method: count_by_categories()
		
		Returns the number of news articles that belong to each role.
		
		Returns:
			An array of objects representing the number in each role.
	*/
	public function count_by_categories() 
	{
		$prefix = $this->db->dbprefix;
	
		$sql = "SELECT category, COUNT(1) as count
				FROM {$prefix}news_articles, {$prefix}news_categories
				WHERE {$prefix}news_articles.category_id = {$prefix}news_categories.id
				GROUP BY {$prefix}news_articles.category_id";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows())
		{
			return $query->result();
		}

		return false;
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: count_all()
		
		Counts all news articles in the system. 
		
		Parameters:
			$get_deleted	- If false, will only return active news_articles. If true, 
				will return both deleted and active news_articles.
				
		Returns: 
			An INT with the number of news_articles found.
	*/
	public function count_all($get_deleted = false) 
	{	
		if ($get_deleted)
		{
			// Get only the deleted users
			$this->db->where('news_articles.deleted !=', 0);
		}
		else 
		{
			$this->db->where('news_articles.deleted', 0);
		}
		
		return $this->db->count_all_results('news_articles');
	}
	
	//--------------------------------------------------------------------

    public function get_default_category() {

        $query = $this->db->select('id')
                          ->where('default',1)
                          ->get('news_categories');
        if ($query->num_rows() > 0) { return $query->row()->id; } else { return 1; };
    }
	//--------------------------------------------------------------------

    public function get_news_categories() {

        $query = $this->db->select('id, category')
            ->get('news_categories');
        if ($query->num_rows() > 0) { return $query->result(); } else { return null; };
    }

    //--------------------------------------------------------------------

    public function get_default_status() {

        $query = $this->db->select('id')
            ->where('default',1)
            ->get('news_status');
        if ($query->num_rows() > 0) { return $query->row()->id; } else { return 1; };
    }
	
	//--------------------------------------------------------------------

    public function get_news_statuses() {

        $query = $this->db->select('id, status')
            ->get('news_status');
        if ($query->num_rows() > 0) { return $query->result(); } else { return null; };
    }
	
	//--------------------------------------------------------------------

    /*
		Method: count_all()
		
		Counts all news articles in the system. 
		
		Parameters:
			$get_deleted	- If false, will only return active news_articles. If true, 
				will return both deleted and active news_articles.
				
		Returns: 
			An INT with the number of news_articles found.
	*/
    public function count_all_by_field($field = false, $value = false, $get_deleted = false) 
	{	
		
		$this->db->where($field,$value);
		if ($get_deleted)
		{
			// Get only the deleted users
			$this->db->where('news_articles.deleted !=', 0);
		}
		else 
		{
			$this->db->where('news_articles.deleted', 0);
		}
		
		return $this->db->count_all_results('news_articles');
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: delete()
		
		Performs a standard delete, but also allows for purging of a record.
		
		Parameters:
			$id		- An INT with the record ID to delete.
			$purge	- If false, will perform a soft-delete. If true, will permenantly
				delete the record.
				
		Returns:
			true/false
	*/
	public function delete($id=0, $purge=false) 
	{
		if ($purge === true)
		{
			// temporarily set the soft_deletes to true.
			$this->soft_deletes = false;
		}
		
		return parent::delete($id);
	}
	
	//--------------------------------------------------------------------
	
}

// End User_model class