<!-- Begin Article -->
<div class="container-fluid">

	<div class="row-fluid rowbg content">
		<div class="span12">
        <?php if (isset($article)) : ?>
            <div class="page-header" style="padding-top: 25px;">
                <h1><?php echo $article->title; ?>
                    <div id="article_date" class="news_date"><?php echo(date('m/d/Y',intval($article->date))); ?></div>
                    <?php if (isset($article->author_name) && !empty($article->author_name)) : ?>
                        <div id="author" class="author">by <?php echo(anchor('users/profile/'.$article->author,((isset($article->author_name))? $article->author_name: 'Unnamed Author'))); ?></div>
                    <?php endif; ?>
                </h1>
            </div>
            <p>
            <div id="article">
                <div id="body" class="news_body well well-large">
                <?php
                if (isset($article->attachment) && !empty($article->attachment)) :
                    $attachment = unserialize($article->attachment);
                        // IMAGE ALIGNMENT CHECK
                        $alignClass = '';
                        if ( isset ( $article->image_align ) && $article->image_align != -1 ) :

                            switch ($article->image_align) :
                                case 1:
                                    $alignClass = ' alignLeft';
                                    break;
                                case 2:
                                    $alignClass = ' alignRight';
                                    break;
                            endswitch;

                        endif;
                        ?>
                    <div id="image" class="news_image<?php echo($alignClass); ?>">
                    <img src="<?php echo base_url(); if (isset($attachment['image_thumb']) && !empty($attachment['image_thumb'])) { echo($article->asset_url.$attachment['image_thumb']); } else { echo($article->asset_url.$attachment['file_name']); } ?>" alt="" title="" />
                    <?php
                    // IMAGE CAPTION CHECK
                    if (isset($article->image_caption) && !empty($article->image_caption)) : ?>
                    <br /><span class="caption"><?php echo($article->image_caption); ?></span>
                    <?php endif; ?>
                    </div>
                <?php
                endif;  ?>

                <?php echo $article->body ?>
                <?php if (!isset($single) || isset ($single) && $single === false) :
                    echo anchor(site_url('/news/article/'.$article->id),'Read more...');
                endif;  ?>
            </div>
            </p>
            <?php if ( isset ( $article->tags ) ) : ?>
            <p><div id="tags" class="tags">Tags: <?php echo $article->tags ?></div></p>
            <?php endif;

            if ( (isset ($single) && $single === true) && isset ($settings['news.sharing_enabled']) && $settings['news.sharing_enabled'] == 1) :
                echo $this->load->view('news/partials/_social',array('settings'=>$settings,'scripts'=>$scripts),true);
            endif;
        else :?>
            <p><?php echo lang('us_no_articles_found'); ?></p>
        <?php
        endif; ?>
        </div>
    </div>
</div>
<!-- End Article -->