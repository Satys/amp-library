<?php

require __DIR__.'/vendor/autoload.php';

use Lullabot\AMP\AMP;
use Lullabot\AMP\Validate\Scope;

// Create an AMP object
$amp = new AMP();

$html = file_get_contents('https://www.foodmate.io/story/45323/fruits-for-weight-loss');	
if ($html == '') echo '1';
// print_r($html);
// return;
// Load up the HTML into the AMP object
// Note that we only support UTF-8 or ASCII string input and output. (UTF-8 is a superset of ASCII) 
// $amp->loadHtml($html);

// If you're feeding it a complete document use the following line instead
$amp->loadHtml($html, ['scope' => Scope::HTML_SCOPE]);

// If you want some performance statistics (see https://github.com/Lullabot/amp-library/issues/24)
// $amp->loadHtml($html, ['add_stats_html_comment' => true]);

// Convert to AMP HTML and store output in a variable
$amp_html = $amp->convertToAmpHtml();
$css = 'a{text-decoration: none;}h1,h2,h3{text-align: center;}body{padding:10px;}span{font-size:large;}';
$tmp = explode("</head>", $amp_html);
$amp_html = $tmp[0]."<style amp-custom>".$css."</style>".$tmp[1];
$amp_html = preg_replace("/<img[^>]+\>/i", "", $amp_html);
$amp_html = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $amp_html);
$amp_html = str_replace("data-cfsrc", "src", $amp_html);


$amp_html = strip_tags_content($amp_html, '<template>', TRUE);

// Print AMP HTML
print($amp_html);


function strip_tags_content($text, $tags = '', $invert = FALSE) { 

  preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags); 
  $tags = array_unique($tags[1]); 
    
  if(is_array($tags) AND count($tags) > 0) { 
    if($invert == FALSE) { 
      return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text); 
    } 
    else { 
      return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text); 
    } 
  } 
  elseif($invert == FALSE) { 
    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text); 
  } 
  return $text; 
} 

// Print validation issues and fixes made to HTML provided in the $html string
// print($amp->warningsHumanText());

// warnings that have been passed through htmlspecialchars() function
// print($amp->warningsHumanHtml());

// You can do the above steps all over again without having to create a fresh object
// $amp->loadHtml($another_string)
// ...
// ...
