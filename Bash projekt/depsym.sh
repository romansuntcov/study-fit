#!/bin/bash

USER="xsuntc00"
myTempFile=`mktemp /tmp/$USER.XXXXXX` 

trap "rm $myTempFile" SIGHUP EXIT QUIT

while getopts  :g:r:d:  arg
	do case $arg in 
	r)	podminka_r=1 OBJ_ID=$OPTARG;;
	d)	podminka_d=1 OBJ_ID=$OPTARG;;
	g)	echo "graf";;
	*)	echo "Use options r,d,g with a parametr">&2
		exit 1;;
	esac
done

((OPTIND--))
shift $OPTIND
Files="$*"
for i in $Files ; do
	identifikatory=$(nm $i | grep ' [BCGDT] ' | sed 's/^[0-9a-z]* *[BCGDT] / /')
	for j in $Files; do
		nedifinovane=$(nm $j | grep ' [U] ' |sed 's/^ *[U] / /')
		if [ "$i" = "$j" ]
			then continue
		fi
		for n in $identifikatory; do
			for m in $nedifinovane;do
				if [ "$n" = "$m" ]
					then
					if [ "$podminka_r" == "1" ]
						then 
							if [ "$OBJ_ID" = "$i" ]
							then echo "$j -> $i ($n)" >>$myTempFile
							fi
					elif [ "$podminka_d" == "1" ]
						then
							if [ "$OBJ_ID" = "$j" ]
							then echo "$j -> $i ($n)" >>$myTempFile
							fi
					else echo "$j -> $i ($n)" >>$myTempFile
					fi
				fi
			done
		done
	done
done

cat $myTempFile | sort | uniq
