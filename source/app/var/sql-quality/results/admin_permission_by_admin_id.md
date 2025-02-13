# SQL Performance Analysis
- **SQL File:** `admin_permission_by_admin_id.sql`
- **Cost:** 0.91

## SQL
```sql
/* admin_permission_by_admin_id */
SELECT `id`, `admin_id`, `access`, `resource_name`, `permission_name`, `created_at`
  FROM `admin_permissions`
 WHERE `admin_id` = :adminId;

```

## Detected Issues


## Explain Tree
```
Table scan
+- Table
   table           admin_permissions
   rows            1
   filtered        100.00
```
## Analysis Detail

### Schema
{"admin_permissions":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"admin_id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"access","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(5)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"resource_name","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(30)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"permission_name","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(10)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"fk_admin_permissions_1","COLUMN_NAME":"admin_id","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":1000},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":81920,"index_length":16384,"auto_increment":1001,"create_time":"2025-01-23 09:55:20","update_time":null}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"0.91"},"table":{"table_name":"admin_permissions","access_type":"ref","possible_keys":["fk_admin_permissions_1"],"key":"fk_admin_permissions_1","used_key_parts":["admin_id"],"key_length":"4","ref":["const"],"rows_examined_per_scan":1,"rows_produced_per_join":1,"filtered":"100.00","cost_info":{"read_cost":"0.81","eval_cost":"0.10","prefix_cost":"0.91","data_read_per_join":"200"},"used_columns":["id","admin_id","access","resource_name","permission_name","created_at"]}}

### EXPLAIN ANALYZE
-> Index lookup on admin_permissions using fk_admin_permissions_1 (admin_id=1)  (cost=0.912 rows=1) (actual time=0.00192..0.00217 rows=1 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。