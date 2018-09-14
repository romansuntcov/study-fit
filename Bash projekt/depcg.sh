#!/bin/bash

USER="xsuntc00"
myTempFile=`mktemp /tmp/$USER.XXXXXX` 

trap "rm $myTempFile" SIGHUP EXIT QUIT

while getopts  :g:pr:d:  arg
	do case $arg in 
	r)	podminka_r=1 OBJ_ID=$OPTARG;;
	d)	podminka_d=1 OBJ_ID=$OPTARG;;
	p)	podminka_p=1;;
	g)	echo "graf";;
	*)	echo "Use options r,d,g with a parametr">&2
		exit 1;;
	esac
done

((OPTIND--))
shift $OPTIND

Files="$*"

for i in $Files
	do y=$(objdump -d -j .text $i | grep "callq *\|>:"| cut -d "<" -f2 | grep ">\|>:" | sed s/^_*//)
done

for j in $y
	do
		if [[ $j =~ :$ ]]
		then
			CALLER=$(echo $j | cut -d ">" -f1)
		else
			CALLEE=$(echo $j | cut -d ">" -f1)
				if [ "$podminka_p" == "" ] && [[ $CALLEE =~ @plt$ ]]
					then continue
				fi
				if [ "$podminka_r" == "1" ]
					then if [ "$OBJ_ID" = "$CALLEE" ]
						then echo "$CALLER -> $CALLEE" >>$myTempFile
					fi
				elif [ "$podminka_d" == "1" ]
					then if [ "$OBJ_ID" = "$CALLER" ]
						then echo "$CALLER -> $CALLEE" >>$myTempFile
					fi
				else echo "$CALLER -> $CALLEE" >>$myTempFile
				fi
		fi
done

cat $myTempFile | sort | uniq 

