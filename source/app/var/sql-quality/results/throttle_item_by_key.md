# SQL Performance Analysis
- **SQL File:** `throttles/throttle_item_by_key.sql`
- **Cost:** 0.35

## SQL
```sql
/* throttle_item_by_key */
SELECT `id`, `throttle_key`, `remote_ip`, `iteration_count`, `max_attempts`, `interval`, `expire_date`, `created_date`, `updated_date`
  FROM `throttles`
 WHERE `throttle_key` = :throttleKey
   AND `expire_date` >= NOW();

```

## Detected Issues


## Explain Tree
```
Table scan
+- Table
   table           throttles
   rows            1
   filtered        33.33
   condition       (`sql_quality_db`.`throttles`.`expire_date` >= <cache>(now()))
```
## Analysis Detail

### Schema
{"throttles":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"throttle_key","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"remote_ip","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"iteration_count","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"max_attempts","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"interval","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(20)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"expire_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"updated_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"idx_throttles_1","COLUMN_NAME":"throttle_key","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":1000},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":114688,"index_length":49152,"auto_increment":1001,"create_time":"2026-01-16 10:40:51","update_time":"2026-01-16 10:40:54"}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"0.35"},"table":{"table_name":"throttles","access_type":"ref","possible_keys":["idx_throttles_1"],"key":"idx_throttles_1","used_key_parts":["throttle_key"],"key_length":"402","ref":["const"],"rows_examined_per_scan":1,"rows_produced_per_join":0,"filtered":"33.33","cost_info":{"read_cost":"0.25","eval_cost":"0.03","prefix_cost":"0.35","data_read_per_join":"306"},"used_columns":["id","throttle_key","remote_ip","iteration_count","max_attempts","interval","expire_date","created_date","updated_date"],"attached_condition":"(`sql_quality_db`.`throttles`.`expire_date` >= <cache>(now()))"}}

### EXPLAIN ANALYZE
-> Filter: (throttles.expire_date >= <cache>(now()))  (cost=0.283 rows=0.333) (actual time=0.00292..0.00292 rows=0 loops=1)
    -> Index lookup on throttles using idx_throttles_1 (throttle_key='index')  (cost=0.283 rows=1) (actual time=0.00246..0.00246 rows=0 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。