#!/usr/bin/env php
<?php

// find ./ -type f -name "*.html" -exec php ./get_images.php {} \;

$dirname = dirname($argv[1]);
$htmlFile = basename($argv[1]);
chdir($dirname);

/*
 * Download and use local imaages
 */
$doc = new DomDocument();
$doc->loadHtmlFile($htmlFile);
$replacements = Array();
foreach($doc->getElementsByTagName('a') as $link){
    $href = $link->getAttribute('href');
    if(preg_match('/(jpg|gif|jpeg|png)/i',$href)){
        $basename = basename($href);
        if(!file_exists($basename)){
            shell_exec("wget \"$href\" --output-document \"$basename\"");
        }

        if(file_exists($basename)){
            $newImgNode = $doc->createElement('img');
            $newImgNode->setAttribute('src',"./$basename");
            $replacements[] = Array($link,$newImgNode);
        }
    }
}

foreach($replacements as $pair){
    $pair[0]->parentNode->replaceChild($pair[1],$pair[0]);
}

$doc->saveHtmlFile($htmlFile);
