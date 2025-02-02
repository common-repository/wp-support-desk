<?php
$kb_category_id = sanitize_text_field(wpsd_post('kb_category_id'));
$get_current_category = get_term($kb_category_id, 'wpsd_kb_categories');
?>





<div class="wpsd-title-wrapper wpsd-bd-bottom">
    <h2 class="wpsd-title wpsd-left"><?php echo $get_current_category->name; ?></h2>
</div> <!-- //title wrapper -->
<div class="wpsd-clearfix"></div>


<?php

$wpsd_kb_args = array(
    'post_type' => 'wpsd_knowledgebase',
    'tax_query' => array(
        array(
            'taxonomy' => 'wpsd_kb_categories',
            'field'    => 'term_id',
            'terms'    => $kb_category_id
        ),
    ),
);
$wpsd_kb_query = new WP_Query( $wpsd_kb_args );

//wpsd_see_raw_data($wpsd_kb_query);
if($wpsd_kb_query->have_posts()){
    $kb_article = '<ul class="wpsd-common-list">';
    while($wpsd_kb_query->have_posts()){ $wpsd_kb_query->the_post();
        $kb_article .= '<li>';

        $kb_id = get_the_ID();
        $kb_article .= "<h3 class='wpsd-msg-title wpsd-mtb10'><a href='javascript:;' class='wpsd-open-kb wpsd-kb-{$kb_id}' data-kb-id='{$kb_id}' data-kb-category-id='{$kb_category_id}'";
        $kb_article .= "<span class='glyphicon glyphicon-file'></span>".get_the_title();
        $kb_article .= "</a></h3>";
        $kb_article .= "<p class='wpsd-msg-info'>".wp_trim_words( get_the_content(), 15, '...' )."</p>";
        $kb_article .= '</li>';
    }
    $wpsd_kb_query->reset_postdata();
    $kb_article .= '</ul>';

    echo $kb_article;
}
?>
