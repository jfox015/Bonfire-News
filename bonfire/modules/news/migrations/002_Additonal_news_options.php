<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Additonal_news_options extends Migration {

    private $permission_array = array(
        'News.Content.View' => 'To view the News Content menu.',
        'News.Settings.View' => 'To view the News Settings menu.',
        'News.Settings.Manage' => 'Manage News Settings.',
    );
    public function up()
	{
		$prefix = $this->db->dbprefix;
	
		$default_settings = "
			INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES
			 ('news.default_article_count', 'news', '5'),
			 ('news.public_submissions', 'news', '1'),
			 ('news.public_submitters', 'news', '1'),
			 ('news.public_moderation', 'news', '1'),
			 ('news.comments_enabled', 'news', '1'),
			 ('news.sharing_enabled', 'news', '1'),
			 ('news.share_facebook', 'news', '1'),
			 ('news.share_twitter', 'news', '1'),
			 ('news.share_stumbleupon', 'news', '1'),
			 ('news.share_delicious', 'news', '1'),
			 ('news.share_email', 'news', '1'),
			 ('news.share_fblike', 'news', '1'),
			 ('news.share_plusone', 'news', '1');
		";
        $this->db->query($default_settings);

        $this->dbforge->add_column('news_articles', array(
                'image_alttag'	=> array(
                'type'	=> 'VARCHAR',
                'constraint'	=> 255,
                'default'		=> ''
            )
        ));
        $this->dbforge->add_column('news_articles', array(
                'image_title' => array(
                'type'	=> 'VARCHAR',
                'constraint'	=> 255,
                'default' => ''
            )
        ));
		$this->dbforge->add_column('news_articles', array(
                'comments_thread_id' => array(
                'type'	=> 'int',
                'constraint'	=> 11,
                'default' => '0'
            )
        ));
        foreach ($this->permission_array as $name => $description)
        {
            $this->db->query("INSERT INTO {$prefix}permissions(name, description) VALUES('".$name."', '".$description."')");
            // give current role (or administrators if fresh install) full right to manage permissions
            $this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1,".$this->db->insert_id().")");
        }

    }
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
        $prefix = $this->db->dbprefix;

        // remove the keys
		$this->db->query("DELETE FROM {$prefix}settings WHERE (name = 'news.sharing_enabled'
			OR name ='news.default_article_count'
			OR name ='news.public_submissions'
			OR name ='news.public_submitters'
			OR name ='news.public_moderation'
			OR name ='news.comments_enabled'
			OR name ='news.share_facebook'
			OR name ='news.share_twitter'
			OR name ='news.share_stumbleupon'
			OR name ='news.share_delicious'
			OR name ='news.share_email'
			OR name ='news.share_fblike'
			OR name ='news.share_plusone'
		)");

        $this->dbforge->drop_column("news_articles","comments_thread_id");
        $this->dbforge->drop_column("news_articles","image_alttag");
        $this->dbforge->drop_column("news_articles","image_title");

        foreach ($this->permission_array as $name => $description)
        {
            $query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = '".$name."'");
            foreach ($query->result_array() as $row)
            {
                $permission_id = $row['permission_id'];
                $this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
            }
            //delete the role
            $this->db->query("DELETE FROM {$prefix}permissions WHERE (name = '".$name."')");
        }

    }
	
	//--------------------------------------------------------------------
	
}