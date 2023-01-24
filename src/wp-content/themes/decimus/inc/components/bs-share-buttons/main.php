<?php
/*Plugin Name: bS Share Buttons
Plugin URI: https://bootscore.me/plugins/bs-share-buttons/
Description: Share Buttons for bootScore theme https://bootscore.me. Use Shortcode [bs-share-buttons] to display buttons in content or widget. Use <?php echo do_shortcode("[bs-share-buttons]"); ?&gt; to display in .php files.
Version: 5.2.0
Author: bootScore
Author URI: https://bootscore.me
License: MIT License
Text Domain: decimus
*/


// Function to handle the thumbnail request
function get_the_post_thumbnail_src($img)
{
  return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';
}
function bs_share_buttons($content) {

    // TODO if this component is disabled -> return ''
    global $post;
    if(is_singular() || is_home()){
    
        // Get current page URL 
        $bs_url = urlencode(get_permalink());
 
        // Get current page title
        $bs_title = str_replace( ' ', '%20', get_the_title());
        
        // Subject
        $bs_subject = __( 'Look what I found: ', 'decimus' );

        // Get Post Thumbnail for pinterest
        $bs_thumb = get_the_post_thumbnail_src(get_the_post_thumbnail());
  
        // Construct sharing URL without using any script
        $twitterURL = 'https://twitter.com/intent/tweet?text='.$bs_subject.' '.$bs_title.'&amp;url='.$bs_url;
        $facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$bs_url;
        $whatsappURL = 'whatsapp://send?text='.$bs_subject.' '.$bs_title . ' ' . $bs_url;
        $linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url='.$bs_url.'&amp;title='.$bs_title;
        $redditURL = 'http://reddit.com/submit?url='.$bs_url.'&amp;title='.$bs_title;
        $tumblrURL = 'http://www.tumblr.com/share/link?url='.$bs_url.'&amp;title='.$bs_title;
        $bufferURL = 'https://bufferapp.com/add?url='.$bs_url.'&amp;text='.$bs_title;
        $mixURL = 'http://www.stumbleupon.com/submit?url='.$bs_url.'&amp;text='.$bs_title;
        $vkURL = 'http://vkontakte.ru/share.php?url='.$bs_url.'&amp;text='.$bs_title;
        $mailURL = 'mailto:?Subject='.$bs_subject.' '.$bs_title.'&amp;Body='.$bs_title.' '.$bs_url.'';
        
       if(!empty($bs_thumb)) {
            $pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$bs_url.'&amp;media='.$bs_thumb[0].'&amp;description='.$bs_title;
        }
        else {
            $pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$bs_url.'&amp;description='.$bs_title;
        }
 
        // Based on popular demand added Pinterest too
        //$pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$bs_url.'&amp;media='.$bs_thumb[0].'&amp;description='.$bs_title;

        // Add sharing button at the end of page/page content
        $content .= '<div id="share-buttons" class="mb-3">';
        $content .= '<a class="mb-1 btn btn-sm btn-twitter" title="Twitter" href="'. $twitterURL .'" target="_blank" rel="nofollow"><i class="fab fa-twitter"></i></a> ';
        $content .= '<a class="mb-1 btn btn-sm btn-facebook" title="Facebook" href="'.$facebookURL.'" target="_blank" rel="nofollow"><i class="fab fa-facebook-f"></i></a> ';
        $content .= '<a class="mb-1 btn btn-sm btn-whatsapp" title="Whatsapp" href="'.$whatsappURL.'" target="_blank" rel="nofollow"><i class="fab fa-whatsapp"></i></a> ';
        $content .= '<a class="mb-1 btn btn-sm btn-pinterest" title="Pinterest" href="'.$pinterestURL.'" data-pin-custom="true" target="_blank" rel="nofollow"><i class="fab fa-pinterest-p"></i></a> ';
        $content .= '<a class="mb-1 btn btn-sm btn-linkedin" title="LinkedIn" href="'.$linkedInURL.'" target="_blank" rel="nofollow"><i class="fab fa-linkedin-in"></i></a> ';
        $content .= '<a class="mb-1 btn btn-sm btn-reddit" title="Reddit" href="'.$redditURL.'" target="_blank" rel="nofollow"><i class="fab fa-reddit-alien"></i></a> ';
        $content .= '<a class="mb-1 btn btn-sm btn-tumblr" title="Tumblr" href="'.$tumblrURL.'" target="_blank" rel="nofollow"><i class="fab fa-tumblr"></i></a> ';
        $content .= '<a class="mb-1 btn btn-sm btn-buffer" title="Buffer" href="'.$bufferURL.'" target="_blank" rel="nofollow"><i class="fab fa-buffer"></i></a> ';
        $content .= '<a class="mb-1 btn btn-sm btn-mix" title="mix" href="'.$mixURL.'" target="_blank" rel="nofollow"><i class="fab fa-mix"></i></a> ';
        $content .= '<a class="mb-1 btn btn-sm btn-vk" title="vk" href="'.$vkURL.'" target="_blank" rel="nofollow"><i class="fab fa-vk"></i></a> ';
        $content .= '<a class="mb-1 btn btn-sm btn-mail btn-dark" title="Mail" href="'.$mailURL.'"><i class="fas fa-envelope"></i></a> ';
        $content .= '<a class="mb-1 btn btn-sm btn-print btn-dark" title="Print" href="javascript:;" onclick="window.print()"><i class="fas fa-print"></i></a>';
        $content .= '</div>';
        
        return $content;
    }else{
        // if not a post/page then don't include sharing button
        return $content;
    }
};


// This will create a wordpress shortcode [share-buttons].
add_shortcode('bs-share-buttons','bs_share_buttons');
