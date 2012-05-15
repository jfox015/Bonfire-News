<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Additonal_news_options extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
	
		$default_settings = "
			INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES
			 ('news.default_article_count', 'news', '5'),
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

    }
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
        $prefix = $this->db->dbprefix;

        // remove the keys
		$this->db->query("DELETE FROM {$prefix}settings WHERE (name = 'news.sharing_enabled'
			OR name ='news.default_article_count'
			OR name ='news.share_facebook'
			OR name ='news.share_twitter'
			OR name ='news.share_stumbleupon'
			OR name ='news.share_delicious'
			OR name ='news.share_email'
			OR name ='news.share_fblike'
			OR name ='news.share_plusone'
		)");

        $this->dbforge->drop_column("news_articles","image_alttag");
        $this->dbforge->drop_column("news_articles","image_title");

    }
	
	//--------------------------------------------------------------------
	
}