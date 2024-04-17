#!/bin/bash
orthogroup=$1
tsv_file=$2
orthogroup_datablock=$(grep -w "${orthogroup}" "${tsv_file}")
orthogroup_sql_str=$(echo "${orthogroup_datablock}" | perl -pe "s/\t/','/g;s/^/'/;s/$/'/" | perl -pe "s/''/NULL/g")
protein_list=$(echo "${orthogroup_datablock}" | cut -f1 --complement | perl -pe 's/\t/\n/g' | sort -V | uniq | grep -v ^$ | perl -pe 's/\n/\,/;' | perl -pe 's/\,$//')
protein_count=$(echo "${protein_list}" | perl -pe 's/\,/\n/g' | wc -l)
echo -e "(${orthogroup_sql_str},'$protein_list','$protein_count'),"