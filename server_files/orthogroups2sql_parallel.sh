#!/bin/bash
tsv_file=$1
orthogroup_list=$(tail -n+2 "${tsv_file}" | cut -f1 | sort -V | uniq)
column_list=$(head -n1 "${tsv_file}" | perl -pe 's/\t/\n/g')
column_sql_str=$(head -n1 "${tsv_file}" | perl -pe 's/^/\`/;s/\t/\`\,\`/g;s/$/\`/')
echo "SET NAMES utf8;" >> orthogroups.sql
echo "SET NAMES utf8mb4;" >> orthogroups.sql
echo "DROP TABLE IF EXISTS \`orthogroups\`;" >> orthogroups.sql
echo "CREATE TABLE \`orthogroups\` (" >> orthogroups.sql
echo "  \`assoc\` int(11) NOT NULL AUTO_INCREMENT," >> orthogroups.sql
for column in ${column_list}
do
  echo "  \`${column}\` text DEFAULT NULL," >> orthogroups.sql
done
echo "  \`protein_list\` longtext DEFAULT NULL," >> orthogroups.sql
echo "  \`protein_count\` int(11) DEFAULT NULL," >> orthogroups.sql
echo "  PRIMARY KEY (\`assoc\`)" >> orthogroups.sql
echo ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;" >> orthogroups.sql
echo "INSERT into \`orthogroups\` (${column_sql_str},\`protein_list\`,\`protein_count\`) VALUES " >> orthogroups.sql
echo "${orthogroup_list}" | awk -v tsv_file="${tsv_file}" '{print "./orthogroups2sql.sh "$1,tsv_file}' | parallel -j 32 > orthogroups.tmp.sql
sort -V orthogroups.tmp.sql >> orthogroups.sql
rm orthogroups.tmp.sql