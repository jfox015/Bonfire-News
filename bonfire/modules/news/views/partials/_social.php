<p>
<div id="sharing_bar">
    <?php
    // Facebook
    if ( isset ($settings['news.share_facebook']) && $settings['news.share_facebook'] == 1) : ?>
        <span class='st_facebook_hcount' displayText='<?php echo lang('nw_share_fblike'); ?>'></span>
        <?php
    endif;
    // Twitter
    if ( isset ($settings['news.share_twitter']) && $settings['news.share_twitter'] == 1) : ?>
        <span class='st_twitter_hcount' displayText='<?php echo lang('nw_share_twitter'); ?>'></span>
        <?php
    endif;
    // StumbleUpon
    if ( isset ($settings['news.share_stumbleupon']) && $settings['news.share_stumbleupon'] == 1) : ?>
        <span class='st_stumbleupon_hcount' displayText='<?php echo lang('nw_share_stumbleupon'); ?>'></span>
        <?php
    endif;
    // Delicious
    if ( isset ($settings['news.share_delicious']) && $settings['news.share_delicious'] == 1) : ?>
        <span class='st_delicious_hcount' displayText='<?php echo lang('nw_share_delicious'); ?>'></span>
        <?php
    endif;
    // Email
    if ( isset ($settings['news.share_email']) && $settings['news.share_email'] == 1) : ?>
        <span class='st_email_hcount' displayText='<?php echo lang('nw_share_email'); ?>'></span>
        <?php
    endif;
    // Facebook Like
    if ( isset ($settings['news.share_fblike']) && $settings['news.share_fblike'] == 1) : ?>
        <span class='st_fblike_hcount' displayText='<?php echo lang('nw_share_fblike'); ?>'></span>
        <?php
    endif;
    // Google +1
    if ( isset ($settings['news.share_plusone']) && $settings['news.share_plusone'] == 1) : ?>
        <span class='st_plusone_hcount' displayText='<?php echo lang('nw_share_plusone'); ?>'></span>
        <?php
    endif;
    if (isset($scripts) && !empty($scripts)) :
        echo $scripts;
    endif; ?>
</div>
</p>
            