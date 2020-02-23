<?php
// 请在第三行开始编写代码
/**
 * Html Compress
 *
 * Sed to compress the page size and improve loading speed.
 *
 * @author Vtrois <seaton@vtrois.com>
 * @license GPL-3.0
 * @since 1.0
 */
if (kratos_option('global_html')) {
    add_action('get_header', 'Kratos_compress_html');
    add_filter( "the_content", "Kratos_unCompress");
  }
function Kratos_compress_html(){
  function Kratos_compress_html_main ($buffer){
    $initial=strlen($buffer);
    $buffer=explode("<!--Kratos-Compress-html-->", $buffer);
    $count=count ($buffer);
    for ($i = 0; $i <= $count; $i++){
        if (stristr($buffer[$i], '<!--Kratos-Compress-html-no-compression-->')) {
            $buffer[$i]=(str_replace("<!--Kratos-Compress-html-no-compression-->", " ", $buffer[$i]));
        } else {
            $buffer[$i]=(str_replace("\t", " ", $buffer[$i]));
            $buffer[$i]=(str_replace("\n\n", "\n", $buffer[$i]));
            $buffer[$i]=(str_replace("\n", "", $buffer[$i]));
            $buffer[$i]=(str_replace("\r", "", $buffer[$i]));
            while (stristr($buffer[$i], '  ')) {
                $buffer[$i]=(str_replace("  ", " ", $buffer[$i]));
            }
        }
        $buffer_out.=$buffer[$i];
    }
    $final=strlen($buffer_out);   
    $savings=($initial-$final)/$initial*100;   
    $savings=round($savings, 2);   
    $buffer_out.="\n<!-- Initial: $initial bytes; Final: $final bytes; Reduce：$savings% :D -->";   
    return $buffer_out;
  }
  ob_start("Kratos_compress_html_main");
}
//wordpress上传文件重命名
function git_upload_filter($file) {
  $time = date("YmdHis");
  $file['name'] = $time . "" . mt_rand(1, 100) . "." . pathinfo($file['name'], PATHINFO_EXTENSION);
  return $file;
}
add_filter('wp_handle_upload_prefilter', 'git_upload_filter');
//WordPress文章关键词自动内链
function tag_sort($a, $b){
  if ( $a->name == $b->name ) return 0;
  return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
}
function tag_link($content){
  $match_num_from = 1;    //一个标签少于几次不链接
  $match_num_to = 4;  //一个标签最多链接几次
  $posttags = get_the_tags();
  if ($posttags) {
      usort($posttags, "tag_sort");
      foreach($posttags as $tag) {
          $link = get_tag_link($tag->term_id);
          $keyword = $tag->name;
          //链接代码
          $cleankeyword = stripslashes($keyword);
          $url = "<a href=\"$link\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('更多关于 %s 的文章'))."\"";
          $url .= ' target="_blank"';
          $url .= ">".addcslashes($cleankeyword, '$')."</a>";
          $limit = rand($match_num_from,$match_num_to);
          //不链接代码
          $content = preg_replace( '|(<a[^>]+>)(.*)<pre.*?>('.$ex_word.')(.*)<\/pre>(</a[^>]*>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
          $content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
          $cleankeyword = preg_quote($cleankeyword,'\'');
          $regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
          $content = preg_replace($regEx,$url,$content,$limit);
          $content = str_replace( '%&&&&&%', stripslashes($ex_word), $content);
      }
  }
  return $content;
}
add_filter('the_content','tag_link',1);
//给外部链接加上跳转
add_filter('the_content','the_content_nofollow',999);
function the_content_nofollow($content)
{
    preg_match_all('/<a(.*?)href="(.*?)"(.*?)>/',$content,$matches);
    if($matches){
        foreach($matches[2] as $val){
            if(strpos($val,'://')!==false && strpos($val,home_url())===false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i',$val)){
                $content=str_replace("href=\"$val\"", "href=\"".home_url()."/go/?url=$val\" ",$content);
            }
        }
    }
    return $content;
}
/*****************************************************
 函数名称：wp_login_notify v1.0 by DH.huahua. 
 函数作用：有登录wp后台就会email通知博主
******************************************************/
function wp_login_notify()
{
    date_default_timezone_set('PRC');
    $admin_email = get_bloginfo ('admin_email');
    $to = $admin_email;
    $subject = '你的博客空间登录提醒';
    $message = '<p>你好！你的博客空间(' . get_option("blogname") . ')有登录！</p>' . 
    '<p>请确定是您自己的登录，以防别人攻击！登录信息如下：</p>' . 
    '<p>登录时间：' . date("Y-m-d H:i:s") .  '<p>' .
    '<p>登录IP：' . $_SERVER['HTTP_X_FORWARDED_FOR'] . '<p>';  
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
}
add_action('wp_login', 'wp_login_notify');
/**
 * 登陆验证码
 *
 * 登陆页面显示数字算术验证码
 */
//后台登陆数学验证码
function myplugin_add_login_fields() {
  //获取两个随机数, 范围0~9
  $num1=rand(-10,30);
  $num2=rand(2,70);
  //最终网页中的具体内容
      echo "<p><label for='math' class='small'>证明你不是机器人，告诉我</label> $num1 + $num2 = ?<input type='text' name='sum' class='input' value='' size='25' tabindex='4'>"
  ."<input type='hidden' name='num1' value='$num1'>"
  ."<input type='hidden' name='num2' value='$num2'></p>";
  }
  add_action('login_form','myplugin_add_login_fields');
  function login_val() {
  $sum=$_POST['sum'];//用户提交的计算结果
  switch($sum){
  //得到正确的计算结果则直接跳出
  case $_POST['num1']+$_POST['num2']:break;
  //未填写结果时的错误讯息
  case null:wp_die('错误: 请输入验证码.');break;
  //计算错误时的错误讯息
  default:wp_die('错误: 验证码错误,请重试.');
  }
  }
  add_action('login_form_login','login_val');
  /* 给分类目录和单页链接末尾加上斜杠 */
$permalink_structure = get_option('permalink_structure');
if (!$permalink_structure || '/' === substr($permalink_structure, -1))
    return;
add_filter('user_trailingslashit', 'ppm_fixe_trailingslash', 10, 2);
function ppm_fixe_trailingslash($url, $type)
{
   if ('single' === $type)
     return $url;
     return trailingslashit($url);
}
//在 WordPress 编辑器添加“下一页”按钮
function add_next_page_button($mce_buttons) {
  $pos = array_search('wp_more', $mce_buttons, true);
  if ($pos !== false) {
      $tmp_buttons = array_slice($mce_buttons, 0, $pos + 1);
      $tmp_buttons[] = 'wp_page';
      $mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos + 1));
  }
  return $mce_buttons;
}
add_filter('mce_buttons', 'add_next_page_button');
// 不显示前台源代码中的W3 Total Cache信息
add_filter('w3tc_can_print_comment','__return_false',10,1);
//输出缩略图地址
function get_thumbnail_src() {
  global $post;
  if ($values = get_post_custom_values("git_thumb")) { //输出自定义域图片地址
      $values = get_post_custom_values("git_thumb");
      $post_thumbnail_src = $values[0];
  } elseif (has_post_thumbnail()) { //如果有特色缩略图，则输出缩略图地址
      $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , 'full');
      $post_thumbnail_src = $thumbnail_src[0];
  } else {
      $post_thumbnail_src = 'https://image.jecho.cn/wp-content/uploads/2018/09/2018092323544371.jpg';
  };
  echo $post_thumbnail_src;
}