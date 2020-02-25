<?php
/**
 * 首页模板
 * @author Seaton Jiang <seaton@vtrois.com>
 * @license MIT License
 * @version 2020.02.15
 */

get_header(); ?>
<div class="k-main  <?php echo kratos_option('top_select', 'banner'); ?> <?php if(kratos_option('global_bg')){echo "inner-wrapper";};?>" style="transform: none;">
    <div class="container <?php if (kratos_option('g_nav_layout') != 'gamma'){echo "container-new";};?>">
        <div class="row <?php if (kratos_option('g_nav_layout') != 'gamma'){echo "row-new";};?>">
            <div class="col-lg-8 board">
                <?php if(is_search()){ ?>
                    <div class="article-panel">
                        <div class="search-title"><?php _e('搜索内容：', 'kratos');the_search_query(); ?></div>
                    </div>
                <?php }
                if ( have_posts() ) {
					while ( have_posts() ){
						the_post();
						get_template_part('/pages/page-content', get_post_format());
					}
				}else{ ?>
                    <div class="article-panel">
                        <div class="nothing">
                            <img src="<?php 
                            if(!kratos_option('g_nothing')){
                                $img = get_template_directory_uri() . '/assets/img/nothing.svg';
                            } else {
                                $img = kratos_option('g_nothing', get_template_directory_uri() . '/assets/img/nothing.svg');
                            }
                            echo $img; ?>">
                            <div class="sorry"><?php _e('很抱歉，没有找到任何内容', 'kratos'); ?></div>
                        </div>
                    </div>
                <?php } 
                pagelist();
                wp_reset_query(); ?>
            </div><!-- .board -->
            <div class="col-lg-4 sidebar d-none d-lg-block">
                <?php dynamic_sidebar('sidebar_tool'); ?>
            </div><!-- .sidebar -->
        </div>
    </div>
</div><!-- .k-main -->
<?php get_footer(); ?>