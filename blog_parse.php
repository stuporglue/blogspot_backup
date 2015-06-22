#!/usr/bin/env php
<?php

$html = "<!doctype html><html><head><title>TITLE</title><style>img{max-width:600px;clear:both;margin:auto auto;}video{margin:auto auto; max-width:600px;}</style></head><body><h1>TITLE</h1>CONTENT</body></html>";

$xml = simplexml_load_file($argv[1]);

foreach($xml->entry as $entry){
    $cateogryType = preg_replace('|.*#|','',$entry->category['term']);
    if($cateogryType == 'post'){

        $published = strtotime($entry->published);

        $outputDir = date('Y/m/d',$published);
        @mkdir($outputDir,0755,TRUE);
        $filename = $outputDir . '/' . date('H_i_',$published) . preg_replace('|[^A-Za-z0-9_-]+|','_',$entry->title) . ".html";

        $output = str_replace('TITLE',$entry->title,$html);
        $output = str_replace('CONTENT',$entry->content,$output);

        file_put_contents($filename,$output);
        print $filename . "\n";
    }
}

exec('find ./ -type f -name "*.html" -exec tidy -f errors.txt -m -utf8 -i {} \;');
