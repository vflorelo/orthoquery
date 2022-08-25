#!/bin/bash
id=$(cat)
tsv_file=$1
grep -w ^${id} ${tsv_file} | awk 'BEGIN{FS="\t"}{print $0 FS length($2)}' | sort -nrk3 | head -n1 | cut -f1,2 | perl -pe 's/^/\(\"/;s/\t/\"\,\"/g;s/$/\"\)\,/' 
