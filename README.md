# Blogspot Backup
Some scripts to help backup a blogspot site.

It uses the site export, chops it into individual HTML files, downloads any images that are linked to (to get the largest size available) and downloads any youtube videos it finds embedded.

These run on OSX with php, wget, tidy, file and youtube-dl installed

1. Export your blog from blogspot
2. ./blog_parse.php blog_export.xml
3. find ./ -type f -name "*.html" -exec ./get_images.php {} \;

Some image links are actually links to simple HTML wrapper pages. Find those and fix them

4.  for i in $(find . -type f | grep -v .html | grep -v .txt | grep -v .php); do file $i;done | grep HTML | sed "s/:.*//" > htmlfiles
5. for i in (cat htmlfiles); do php ./make_image_html_to_image.php "$i";done
6. find ./ -type f -name "*.html" -exec ./get_videos.php {} \;
