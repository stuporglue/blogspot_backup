#!/usr/bin/env php
<?php

// for i in $(find . -type f | grep -v .html | grep -v .txt | grep -v .php); do file $i;done | grep HTML | sed "s/:.*//" > htmlfiles
// for i in (cat htmlfiles); do php ./make_image_html_to_image.php "$i";done

$dirname = dirname($argv[1]);
$basename = basename($argv[1]);
chdir($dirname);

/*
 * Download and use local imaages
 */
$doc = new DomDocument();
$doc->loadHtmlFile($basename);
$replacements = Array();
foreach($doc->getElementsByTagName('img') as $link){
    $href = $link->getAttribute('src');
    if(preg_match('/(jpg|gif|jpeg|png)/i',$href)){
        shell_exec("wget $href --output-document $basename");
    }
}
