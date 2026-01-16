# SQL Performance Analysis
- **SQL File:** `admins/admin_delete_list.sql`
- **Cost:** 0.35

## SQL
```sql
/* admin_delete_list */
SELECT `admin_id`, `request_date`, `schedule_date`, `deleted_date`, `created_date`
  FROM `admin_deletes`
 WHERE `deleted_date` IS NULL
   AND `schedule_date` <= NOW();

```

## Detected Issues


## Explain Tree
```
Table scan
+- Table
   table           admin_deletes
   rows            1
   filtered        33.33
   condition       (`sql_quality_db`.`admin_deletes`.`schedule_date` <= <cache>(now()))
```
## Analysis Detail

### Schema
{"admin_deletes":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"admin_id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"UNI","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"request_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"schedule_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"deleted_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"idx_admin_deletes_1","COLUMN_NAME":"admin_id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":100},{"INDEX_NAME":"idx_admin_deletes_2","COLUMN_NAME":"deleted_date","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":85},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":100}],"status":{"table_rows":100,"data_length":16384,"index_length":32768,"auto_increment":101,"create_time":"2026-01-16 11:46:03","update_time":"2026-01-16 11:46:41"}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"0.35"},"table":{"table_name":"admin_deletes","access_type":"ref","possible_keys":["idx_admin_deletes_2"],"key":"idx_admin_deletes_2","used_key_parts":["deleted_date"],"key_length":"6","ref":["const"],"rows_examined_per_scan":1,"rows_produced_per_join":0,"filtered":"33.33","index_condition":"(`sql_quality_db`.`admin_deletes`.`deleted_date` is null)","cost_info":{"read_cost":"0.25","eval_cost":"0.03","prefix_cost":"0.35","data_read_per_join":"10"},"used_columns":["admin_id","request_date","schedule_date","deleted_date","created_date"],"attached_condition":"(`sql_quality_db`.`admin_deletes`.`schedule_date` <= <cache>(now()))"}}

### EXPLAIN ANALYZE
-> Filter: (admin_deletes.schedule_date <= <cache>(now()))  (cost=0.283 rows=0.333) (actual time=0.0105..0.0105 rows=0 loops=1)
    -> Index lookup on admin_deletes using idx_admin_deletes_2 (deleted_date=NULL), with index condition: (admin_deletes.deleted_date is null)  (cost=0.283 rows=1) (actual time=0.003..0.003 rows=0 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。