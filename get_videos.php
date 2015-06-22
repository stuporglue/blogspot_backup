<?php

// find ./ -type f -name "*.html" -exec php ./get_videos.php {} \;

$dirname = dirname($argv[1]);
$htmlFile = basename($argv[1]);
chdir($dirname);

/*
 * Download and use local imaages
 */
$doc = new DomDocument();
$doc->loadHtmlFile($htmlFile);
$replacements = Array();
foreach($doc->getElementsByTagName('iframe') as $link){
    $href = $link->getAttribute('src');
    if(strpos($href,'youtube')){
        $basename = basename($href);
        $basename = preg_replace('|?.*|','',$basename);
        if(!file_exists("$basename.mp4")){
            shell_exec("youtube-dl -f mp4 -o \"$basename.mp4\" \"$href\"");
        }

        if(file_exists("$basename.mp4")){
            $newImgNode = $doc->createDocumentFragment();
            $newImgNode->appendXml("<video width='500' controls='controls'> <source src='$basename.mp4' type='video/mp4'/> Your browser does not support the video tag.  </video>");
            $replacements[] = Array($link,$newImgNode);
        }else{
            print "I didn't get $dirname/$basename.mp4\n";
        }
    }else{
        print "Found iframe: $href\n";
    }
}

foreach($replacements as $pair){
    $pair[0]->parentNode->replaceChild($pair[1],$pair[0]);
}

$doc->saveHtmlFile($htmlFile);
