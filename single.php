<?php
/**
 * 文章内容
 * @author Seaton Jiang <seaton@vtrois.com>
 * @license MIT License
 * @version 2020.02.15
 */

get_header(); ?>
<div class="k-main  <?php echo kratos_option('top_select', 'banner'); ?> <?php if(kratos_option('global_bg')){echo "inner-wrapper";};?>" style="transform: none;">
    <div class="container <?php if(kratos_option('g_nav_layout')!= 'gamma'){echo "container-new";};?>">
        <div class="row <?php if(kratos_option('g_nav_layout')!= 'gamma'){echo "row-new";};?>">
            <?php if(kratos_option('g_nav_layout')!= 'gamma'){?>
                <div class="auxiliary d-none d-md-block">
                    <div class="panel-box weibo">
                        <a href="http://service.weibo.com/share/share.php?url=<?php echo get_permalink()?>&title=<?php echo $post->post_title ?>&pic=<?php echo share_thumbnail_url()?>" class="plain weibo" onclick="share('weibo');" rel="nofollow" target="_blank">
                            <i class="kicon i-sina" title="分享到微博"></i>
                        </a>
                    </div>
                    <div class="panel-box twitter">
                        <a href="https://twitter.com/intent/tweet?text=[标题]<?php echo $post->post_title ?>[表述]<?php echo get_the_excerpt()?>&url=<?php echo get_permalink()?>" class="plain twitter style-plain" onclick="share('twitter');" rel="nofollow" target="_blank">
                            <i class="kicon i-twitter" title="分享到twitter"></i>
                        </a>
                    </div>
                    <div class="panel-box weixin">
                        <a href="javascript:;" class="plain qrcode-box style-plain" rel="nofollow">
                            <i class="kicon i-wechat" title="分享到微信"></i>
                            <div class="qrcode-plane">
                                <div class="qrcode" data-url="<?php the_permalink() ?>"></div>
                                <p>微信扫一扫</p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php }?>
            <div class="col-lg-8 details">
            <?php if (have_posts()) : the_post(); update_post_caches($posts); ?>
                    <div class="article">
                        <div class="breadcrumb-box">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a class="text-dark" href="<?php echo home_url(); ?>"> <?php _e('首页' , 'kratos'); ?></a>
                                </li>
                                <li class="breadcrumb-item">
                                <?php
                                    $category = get_the_category();
                                    echo '<a class="text-dark" href="'.get_category_link($category[0]->cat_ID).'">' . $category[0]->cat_name . '</a>';
                                ?>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"> <?php _e('正文' , 'kratos'); ?></li>
                            </ol>
                        </div><!-- .breadcrumb-box -->
                        <div class="header">
                            <h1 class="title"><?php the_title(); ?></h1>
                            <?php if (kratos_option('g_nav_layout') != 'alpha') { ?>
                                <div class="meta">
                                    <span><?php echo get_the_date('Y年m月d日'); ?></span>
                                    <span ><?php echo get_post_views(); _e('点热度' , 'kratos'); ?></span>
                                    <span><?php if (get_post_meta($post->ID, 'love', true)) { echo get_post_meta($post->ID, 'love', true); } else {echo '0'; } _e('人点赞', 'kratos'); ?></span>
                                    <span><?php comments_number('0', '1', '%'); _e('条评论', 'kratos'); ?></span>
                                    <?php if (current_user_can('edit_posts')){ echo '<span>'; edit_post_link(__('编辑文章', 'kratos')); echo '</span>'; }; ?>
                                </div>
                            <?php } else{ ?>
                                <div class="entry-meta">
								    <span class="glyphicon glyphicon-calendar kicon i-calendar" aria-hidden="true"></span>
								    <span><?php echo get_the_date('Y年m月d日'); ?></span>
								    <span class="glyphicon glyphicon-eye-open kicon i-hot" aria-hidden="true"></span>
								    <span><?php echo get_post_views(); _e('点热度', 'kratos'); ?></span>
								    <span class="d-none d-xl-inline-block glyphicon glyphicon-comment kicon i-comments" aria-hidden="true"></span>
								    <span class="d-none d-xl-inline-block"><?php comments_number('0', '1', '%'); _e('条评论', 'kratos'); ?></span>
								    <span class="glyphicon glyphicon-user kicon i-good" aria-hidden="true"></span>
								    <span><?php if (get_post_meta($post->ID, 'love', true)) {echo get_post_meta($post->ID, 'love', true);} else {echo '0';} _e('人点赞', 'kratos'); ?></span>
							    </div>
                            <?php } ?>
                        </div><!-- .header -->
                        <div class="content">
                            <?php
                            if(kratos_option('s_singletop',false)){
                                if(kratos_option('s_singletop_links')){
                                    echo '<a href="'. kratos_option('s_singletop_links') .'" target="_blank" rel="noreferrer">';
                                }
                                echo '<img src="'.kratos_option('s_singletop_url').'">';
                                if(kratos_option('s_singletop_links')){
                                    echo '</a>';
                                }
                            }
                            the_content();
                            if(kratos_option('s_singledown',false)){
                                if(kratos_option('s_singledown_links')){
                                    echo '<a href="'. kratos_option('s_singledown_links') .'" target="_blank" rel="noreferrer">';
                                }
                                echo '<img src="'.kratos_option('s_singledown_url').'">';
                                if(kratos_option('s_singledown_links')){
                                    echo '</a>';
                                }
                            }
                            ?>
                        </div><!-- .content -->
                        <div class="copyright">
                            <span class="text-center">
                                <?php 
                                    $cc_array = array(
                                        'one' => __('知识共享署名 4.0 国际许可协议', 'kratos'),
                                        'two' => __('知识共享署名-非商业性使用 4.0 国际许可协议', 'kratos'),
                                        'three' => __('知识共享署名-禁止演绎 4.0 国际许可协议', 'kratos'),
                                        'four' => __('知识共享署名-非商业性使用-禁止演绎 4.0 国际许可协议', 'kratos'),
                                        'five' => __('知识共享署名-相同方式共享 4.0 国际许可协议', 'kratos'),
                                        'six' => __('知识共享署名-非商业性使用-相同方式共享 4.0 国际许可协议', 'kratos'),
                                    );
                                    $select_cc = $cc_array[kratos_option('g_cc', 'one')];
                                    printf( __( '本作品采用 %s 进行许可','kratos' ) , $select_cc );?>
                            </span>
                        </div><!-- .copyright -->
                        <div class="footer clearfix">
                            <div class="tags float-left">
                                <span><?php _e('标签：' , 'kratos'); ?></span>
                                <?php if ( get_the_tags() ) { the_tags('', ' ', ''); } else{ echo '<a>' . __( '暂无' , 'kratos') . '</a>';  }?>
                            </div>
                            <div class="tool float-right d-none d-lg-block">
                                <div data-toggle="tooltip" data-html="true" data-original-title="<?php _e('最后更新：','kratos'); the_modified_date( 'Y-m-d H:i' ) ?>">
                                    <span><?php _e('最后更新：','kratos'); ?><?php the_modified_date('Y年m月d日'); ?></span>
                                </div>
                            </div>
                        </div><!-- .footer -->
                    </div><!-- .article -->
                <?php endif; ?>
                <?php require get_template_directory() . '/pages/page-toolbar.php'; ?>
                <?php if (kratos_option('g_nav_layout') != 'alpha') { ?>
                    <nav class="navigation post-navigation clearfix" role="navigation">
                        <?php
                        $prev_post = get_previous_post(TRUE);
                        if(!empty($prev_post)){
                            echo '<div class="nav-previous clearfix"><a title="'.$prev_post->post_title .'" href="'.get_permalink($prev_post->ID).'">'. __('< 上一篇','kratos') .'</a></div>';
                        }
                        $next_post = get_next_post(TRUE);
                        if(!empty($next_post)){
                            echo '<div class="nav-next"><a title="'. $next_post->post_title .'" href="'. get_permalink($next_post->ID) .'">'. __('下一篇 >','kratos') .'</a></div>';
                        }?>
                    </nav>
                <?php } else{ ?>
                    <nav class="content-navigation mt-3 text-center clearfix">
                        <?php $prev_post = get_previous_post(TRUE); ?>
			                <?php if(!empty($prev_post)){?>
			                    <a href="<?php echo get_permalink($prev_post->ID);?>" class="text-dark" rel="prev">
			                        <span class="nav-span previous d-inline-block">
				                        <span class="post-nav d-block">&lt; 上一篇</span>
				                        <span class="d-none d-md-block"><?php echo $prev_post->post_title;?></span>
			                        </span>
                                </a>
			                <?php }else{ ?>
			                    <a href="javascript:void(0)" class="text-dark" rel="prev">
			                        <span class="nav-span previous d-inline-block">
				                        <span class="post-nav d-block">没有了</span>
				                        <span class="d-none d-md-block">已经是最后的文章</span>
			                        </span>
			                    </a>
		                    <?php } ?>
	                    <?php $next_post = get_next_post(TRUE); ?>
			                <?php if(!empty($next_post)){?>
			                    <a href="<?php echo get_permalink($next_post->ID); ?>" class="text-dark" rel="next">
			                        <span class="nav-span d-inline-block">
				                        <span class="post-nav d-block">下一篇 &gt;</span>
				                        <span class="d-none d-md-block"><?php echo $next_post->post_title;?></span>
			                        </span>
                                </a>
			                <?php }else{ ?>
			                    <a href="javascript:void(0)" class="text-dark" rel="next">
			                        <span class="nav-span d-inline-block">
				                        <span class="post-nav d-block">没有了</span>
				                        <span class="d-none d-md-block">已经是最后的文章</span>
			                        </span>
			                    </a>
		                    <?php } ?>
                    </nav>
                <?php } ?>
                <?php comments_template(); ?>
            </div><!-- .details -->
            <div class="col-lg-4 sidebar d-none d-lg-block">
                <?php dynamic_sidebar('sidebar_tool'); ?>
            </div><!-- .sidebar -->
        </div>
    </div>
</div><!-- .k-main -->
<?php get_footer(); ?>