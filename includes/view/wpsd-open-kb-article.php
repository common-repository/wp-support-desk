<?php
global $wpdb;
$kb_category_id         = sanitize_text_field( wpsd_post('kb_category_id') );
$get_current_category   = get_term( $kb_category_id, 'wpsd_kb_categories' );
$kb_id                  = sanitize_text_field( wpsd_post('kb_id') );
$get_post               = get_post( $kb_id );
setup_postdata($kb_id);
?>
<div class="wpsd-knowbase-details">
    <h2 class="wpsd-msg-title wpsd-mtb10"><?php echo $get_post->post_title; ?></h2>
    <div class="wpsd-kb-content wpsd-kb-content-<?php the_ID(); ?>">
        <?php echo nl2br(get_the_content()); ?>
    </div>
    <div class="wpsd-kb-vote-wrap">
        <h3><?php _e('Was this article helpful?', 'wp-support-desk'); ?></h3>
        <div class="wpsd-kb-vote-btn-wrap">
            <?php
            $user_id                = get_current_user_id();
            $get_all_voted_user_ids = get_post_meta($get_post->ID, 'wpsd_kb_article_voted');

            if ( ! in_array($user_id, $get_all_voted_user_ids)){ ?>
                <a href="javascript:;" data-value="yes" data-article-id="<?php echo $get_post->ID; ?>" class="wpsd-vote-btn wpsd-vote-up-btn">
                    <i class="fa fa-thumbs-o-up"></i> <?php _e('Yes', 'wp-support-desk'); ?> 
                </a>
                <a href="javascript:;" data-value="no" data-article-id="<?php echo $get_post->ID; ?>" class="wpsd-vote-btn wpsd-vote-down-btn"> 
                    <i class="fa fa-thumbs-o-down"></i> <?php _e('No', 'wp-support-desk'); ?> 
                </a>
                <?php
            } else {
                $answer     = get_post_meta($get_post->ID, "wpsd_kb_article_voted_user_{$user_id}", true);
                echo __( 'Your answer was : ','wp-support-desk' )."<strong>{$answer}</strong>";
            }

            $get_yes_vote   = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE post_id = {$kb_id} AND meta_key LIKE 'wpsd_kb_article_voted_user%' AND meta_value = 'yes' " );
            $get_no_vote    = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE post_id = {$kb_id} AND meta_key LIKE 'wpsd_kb_article_voted_user%' AND meta_value = 'no' " );
            $total_vote     = ( $get_yes_vote + $get_no_vote );
            echo "<h3>".sprintf(__('Yes / %d, No / %d', 'wp-support-desk'), $get_yes_vote, $get_no_vote )."</h3>";
            ?>
        </div>
    </div>
</div> <!-- wpsd-knowbase-details -->