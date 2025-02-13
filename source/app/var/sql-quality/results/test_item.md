# SQL Performance Analysis
- **SQL File:** `test_item.sql`
- **Cost:** 1.00

## SQL
```sql
/* test item */
SELECT id, title, date_created
  FROM test
 WHERE id = :id

```

## Detected Issues


## Explain Tree
```
Table scan
+- Table
   table           test
   rows            1
   filtered        100.00
```
## Analysis Detail

### Schema
{"test":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(255)","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":"","EXTRA":""},{"COLUMN_NAME":"title","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(255)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"date_created","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":81920,"index_length":0,"auto_increment":null,"create_time":"2025-01-25 16:19:39","update_time":null}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"1.00"},"table":{"table_name":"test","access_type":"const","possible_keys":["PRIMARY"],"key":"PRIMARY","used_key_parts":["id"],"key_length":"1022","ref":["const"],"rows_examined_per_scan":1,"rows_produced_per_join":1,"filtered":"100.00","cost_info":{"read_cost":"0.00","eval_cost":"0.10","prefix_cost":"0.00","data_read_per_join":"2K"},"used_columns":["id","title","date_created"]}}

### EXPLAIN ANALYZE
-> Rows fetched before execution  (cost=0..0 rows=1) (actual time=42e-6..84e-6 rows=1 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。