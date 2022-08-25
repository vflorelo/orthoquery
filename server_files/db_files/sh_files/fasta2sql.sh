#!/bin/bash
fasta_file=$1
base_name=$(echo "${fasta_file}" | perl -pe 's/\.faa//')
tsv_datablock=$(perl -pe 'if(/\>/){s/$/\t/};s/\n//;s/\>/\n/g' ${fasta_file} | tail -n+2 | sort -V --parallel=16	 | uniq)
protein_list=$(echo "${tsv_datablock}" | cut -f1 | sort -V | uniq)
for protein in $protein_list
do
  grep -w "${protein}" <(echo "${tsv_datablock}") | awk 'BEGIN{FS="\t"}{print $1 FS $2 FS length($2)}' | sort -nrk3 | head -n1 | awk 'BEGIN{FS="\t"}{print "(\""$1"\",\""$2"\"),"}'
done > ${base_name}.sql