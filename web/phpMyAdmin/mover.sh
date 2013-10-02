#!/bin/bash

DIR=/var/www/phpMyAdmin/


for FILE in `find -amin -30`
do
	mv -v "$FILE" $DIR
done

