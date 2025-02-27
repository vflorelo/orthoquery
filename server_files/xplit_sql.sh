#!/bin/bash
sql_file=$1
create_clause=$(head -n15 "${sql_file}")
insert_clause=$(head -n16 "${sql_file}" | tail -n 1)
sql_datablock=$(tail -n+17 "${sql_file}")
num_lines=$(echo "${sql_datablock}" | wc -l)
num_bins=$(echo "${num_lines}" | awk '{a=$1%500}{if(a==0){print int(($1/500)+1)}else{print int($1/500)}}')
echo "${create_clause}" > 00.sql
mysql -u root -p"V84012561m" -h localhost -D orthologues < 00.sql
for bin in $(seq 1 ${num_bins})
do
  echo "${bin}"
  start_line=$(echo $bin | awk '{print (($1-1)*100)+1}');
  insert_datablock=$(echo "${sql_datablock}" | tail -n+${start_line} | head -n 500 | perl -pe 's/\n//g' | perl -pe 's/\,$/\;/;s/\)\,/\)\,\n/g')
  echo -e "${insert_clause}\n${insert_datablock}" > "${bin}.sql"
  mysql -u root -p"V84012561m" -h localhost -D orthologues < "${bin}.sql"
  rm ${bin}.sql
done
rm 00.sql