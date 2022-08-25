#!/bin/bash
orthogroup=$(cat)
tsv_file=$1
orthogroup_datablock=$(grep -w "${orthogroup}" "${tsv_file}")
orthogroup_sql_str=$(echo "${orthogroup_datablock}" | perl -pe "s/\t/','/g;s/^/'/;s/$/'/")
protein_list=$(echo "${orthogroup_datablock}" | cut -f1 --complement | perl -pe 's/\t/\,/g')
protein_count=$(echo "${protein_list}" | perl -pe 's/\,/\n/g' | wc -l)
echo -e "(${orthogroup_sql_str},'$protein_list','$protein_count'),"