<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Install_news extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
	
		// News Articles
		$this->dbforge->add_field('`id` int(11) NOT NULL AUTO_INCREMENT');
		$this->dbforge->add_field("`author` int(11) NOT NULL DEFAULT '-1'");
		$this->dbforge->add_field('`title` varchar(255) NOT NULL');
		$this->dbforge->add_field("`date` int(11) DEFAULT NULL");
		$this->dbforge->add_field('`body` longtext');
		$this->dbforge->add_field('`attachment` varchar(1000) NOT NULL');
		$this->dbforge->add_field('`image_align` varchar(255) NOT NULL');
		$this->dbforge->add_field('`image_caption` varchar(255) NOT NULL');
		$this->dbforge->add_field('`tags` varchar(255) NOT NULL');
		
		$this->dbforge->add_field("`created_on` int(11) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`created_by` int(11) NOT NULL DEFAULT '-1'");
		$this->dbforge->add_field("`modified_on` int(11) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`modified_by` int(11) NOT NULL DEFAULT '-1'");
 
		$this->dbforge->add_field("`status_id` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`category_id` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_field("`date_published` int(11) DEFAULT NULL");
		$this->dbforge->add_field("`deleted` tinyint(1) NOT NULL DEFAULT '0'");

		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('news_articles');
        $this->db->query("INSERT INTO {$prefix}news_articles VALUES(1, 1, 'Test News Article',".(time()-100000).",'<b>This is a test</b><br />Testing how this all works out.</b>','',-1,'','news,article,first',".time().",1,".time().",1,1,1,".strtotime('2012-02-14').",0)");
        $this->db->query("INSERT INTO {$prefix}news_articles VALUES(2, 1, 'A sample news article with title',".time().",'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse est dolor, pellentesque a aliquet commodo, vestibulum quis enim. Ut aliquet rutrum purus, in vestibulum augue mattis eget. Aliquam iaculis lacinia neque, nec ultrices lorem aliquet eu. Suspendisse potenti. Nullam elementum feugiat blandit. Nullam ultricies leo libero, venenatis molestie diam. Proin mollis libero vitae nunc mattis rutrum.','',-1,'','lipsum, news, title, content, fresh',".time().",1,".time().",1,1,1,".strtotime('2012-04-01').",0)");
	
		// Categories
		$this->dbforge->add_field('`id` int(11) NOT NULL AUTO_INCREMENT');
		$this->dbforge->add_field("`category` varchar(50) NOT NULL");
		$this->dbforge->add_field("`default` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('news_categories');
			
		$this->db->query("INSERT INTO {$prefix}news_categories VALUES(-1, 'Unknown', 0)");
		$this->db->query("INSERT INTO {$prefix}news_categories VALUES(1, 'Default', 1)");
		
		// Status
		$this->dbforge->add_field('`id` int(11) NOT NULL AUTO_INCREMENT');
		$this->dbforge->add_field("`status` varchar(50) NOT NULL");
		$this->dbforge->add_field("`default` tinyint(1) NOT NULL DEFAULT '0'");
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('news_status');
			
		$this->db->query("INSERT INTO {$prefix}news_status VALUES(-1, 'Unknown', 0)");
		$this->db->query("INSERT INTO {$prefix}news_status VALUES(1, 'Draft', 1)");
		$this->db->query("INSERT INTO {$prefix}news_status VALUES(2, 'In Review', 0)");
		$this->db->query("INSERT INTO {$prefix}news_status VALUES(3, 'Published', 0)");
		$this->db->query("INSERT INTO {$prefix}news_status VALUES(4, 'Archived', 0)");
		
		$data = array(
			'name'        => 'Site.News.Manage',
			'description' => 'Manage News Content' 
		);
		$this->db->insert("{$prefix}permissions", $data);
		
		$permission_id = $this->db->insert_id();
		
		// change the roles which don't have any specific login_destination set
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1, ".$permission_id.")");
		
		$data = array(
			'name'        => 'Site.News.Add',
			'description' => 'Add News Content' 
		);
		$this->db->insert("{$prefix}permissions", $data);
		
		$permission_id = $this->db->insert_id();
		
		// change the roles which don't have any specific login_destination set
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1, ".$permission_id.")");

        $data = array(
            'name'        => 'Site.News.Delete',
            'description' => 'Add News Content'
        );
        $this->db->insert("{$prefix}permissions", $data);

        $permission_id = $this->db->insert_id();

        // change the roles which don't have any specific login_destination set
        $this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1, ".$permission_id.")");

        $default_settings = "
			INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES
			 ('news.allow_attachments', 'news', '1'),
			 ('news.upload_dir_path', 'news', ''),
			 ('news.upload_dir_url', 'news', ''),
			 ('news.max_img_size', 'news', '125000'),
			 ('news.max_img_width', 'news', '1024'),
			 ('news.max_img_disp_width', 'news', '200'),
			 ('news.max_img_height', 'news', '768'),
			 ('news.max_img_disp_height', 'news', '200');
		";
        $this->db->query($default_settings);
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
        $prefix = $this->db->dbprefix;

        $this->dbforge->drop_table('news_articles');
		$this->dbforge->drop_table('news_categories');
		$this->dbforge->drop_table('news_status');
        $this->db->query("DELETE FROM {$prefix}settings WHERE (module = 'news')");
		
		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Site.News.Manage'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the role
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Site.News.Manage')");
		
		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Site.News.Delete'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the role
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Site.News.Delete')");
		
		
		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Site.News.Add'");
        foreach ($query->result_array() as $row)
        {
            $permission_id = $row['permission_id'];
            $this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
        }
        //delete the role
        $this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Site.News.Add')");
	}
	
	//--------------------------------------------------------------------
	
}