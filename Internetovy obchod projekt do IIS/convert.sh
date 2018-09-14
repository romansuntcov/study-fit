#!/bin/sh

find $1 -type f -name "*.utf8.php" -delete

find $1 -type f -name "*.php" | \
    (while read file; do
        iconv -f iso-8859-2 -t UTF-8 "$file" > "${file%.php}.utf8.php";
    done);
    
find $1 -type f -name "*.utf8.php" |
    (while read file; do
        rm "${file%.utf8.php}.php" && mv $file "${file%.utf8.php}.php";
    done);