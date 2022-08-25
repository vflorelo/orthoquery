#!/bin/bash
tsv_file=$1
orthogroup_list=$(tail -n+2 "${tsv_file}" | cut -f1 | sort -V | uniq)
echo "${orthogroup_list}" | awk -v tsv_file="${tsv_file}" '{print "echo "$1" |./orthogroups2sql.sh "tsv_file}' | parallel -j 32
