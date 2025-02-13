# SQL Performance Analysis
- **SQL File:** `admin_delete_list.sql`
- **Cost:** 1.10

## SQL
```sql
/* admin_delete_list */
SELECT `admin_id`, `request_at`, `schedule_at`, `deleted_at`, `created_at`
  FROM `admin_deletes`
 WHERE `deleted_at` IS NULL
   AND `schedule_at` <= NOW();

```

## Detected Issues


## Explain Tree
```
Table scan
+- Table
   table           admin_deletes
   rows            1
   filtered        33.33
   condition       (`sql_quality_db`.`admin_deletes`.`schedule_at` <= <cache>(now()))
```
## Analysis Detail

### Schema
{"admin_deletes":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"admin_id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"UNI","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"request_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"schedule_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"deleted_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"idx_admin_deletes_1","COLUMN_NAME":"admin_id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":100},{"INDEX_NAME":"idx_admin_deletes_2","COLUMN_NAME":"deleted_at","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":92},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":100}],"status":{"table_rows":100,"data_length":16384,"index_length":32768,"auto_increment":101,"create_time":"2025-01-25 16:05:11","update_time":null}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"1.10"},"table":{"table_name":"admin_deletes","access_type":"ref","possible_keys":["idx_admin_deletes_2"],"key":"idx_admin_deletes_2","used_key_parts":["deleted_at"],"key_length":"6","ref":["const"],"rows_examined_per_scan":1,"rows_produced_per_join":0,"filtered":"33.33","index_condition":"(`sql_quality_db`.`admin_deletes`.`deleted_at` is null)","cost_info":{"read_cost":"1.00","eval_cost":"0.03","prefix_cost":"1.10","data_read_per_join":"10"},"used_columns":["admin_id","request_at","schedule_at","deleted_at","created_at"],"attached_condition":"(`sql_quality_db`.`admin_deletes`.`schedule_at` <= <cache>(now()))"}}

### EXPLAIN ANALYZE
-> Filter: (admin_deletes.schedule_at <= <cache>(now()))  (cost=1.03 rows=0.333) (actual time=0.0162..0.0162 rows=0 loops=1)
    -> Index lookup on admin_deletes using idx_admin_deletes_2 (deleted_at=NULL), with index condition: (admin_deletes.deleted_at is null)  (cost=1.03 rows=1) (actual time=0.0159..0.0159 rows=0 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。