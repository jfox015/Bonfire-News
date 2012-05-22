<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Default_news_data extends Migration {

    public function up()
	{
		$prefix = $this->db->dbprefix;

        $comments = $this->db->table_exists('comments_threads');
        $comments_thread_id = 0;
        if ($comments) {
            $this->db->query("INSERT INTO {$prefix}comments_threads VALUES(0,".time().",0,'news')");
            $comments_thread_id = $this->db->insert_id();
            $this->db->query("INSERT INTO {$prefix}comments VALUES(0, {$comments_thread_id},'This is a default comment. Do with it as you will.',".time().",1,".time().",'', 0,1)");
        }
        $this->db->query("INSERT INTO {$prefix}news_articles VALUES(0, 1, 'Test News Article',".(time()-100000).",'<b>This is a test</b><br />Testing how this all works out.</b>','',-1,'','news,article,first',".time().",1,".time().",1,1,1,".strtotime('2012-02-14').",0,'','',{$comments_thread_id})");
        if ($comments) {
            $this->db->query("INSERT INTO {$prefix}comments_threads VALUES(0,".time().",0,'news')");
            $comments_thread_id = $this->db->insert_id();
            $this->db->query("INSERT INTO {$prefix}comments VALUES(0, {$comments_thread_id},'This is a default comment. Do with it as you will.',".time().",1,".time().",'', 0,1)");
        }
        $this->db->query("INSERT INTO {$prefix}news_articles VALUES(0, 1, 'A sample news article with title',".time().",'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse est dolor, pellentesque a aliquet commodo, vestibulum quis enim. Ut aliquet rutrum purus, in vestibulum augue mattis eget. Aliquam iaculis lacinia neque, nec ultrices lorem aliquet eu. Suspendisse potenti. Nullam elementum feugiat blandit. Nullam ultricies leo libero, venenatis molestie diam. Proin mollis libero vitae nunc mattis rutrum.','',-1,'','lipsum, news, title, content, fresh',".time().",1,".time().",1,1,1,".strtotime('2012-04-01').",0,'','',{$comments_thread_id})");
    }
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
        $prefix = $this->db->dbprefix;

    }
	
	//--------------------------------------------------------------------
	
}