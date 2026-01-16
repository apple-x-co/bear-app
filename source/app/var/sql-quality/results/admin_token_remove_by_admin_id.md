# SQL Performance Analysis
- **SQL File:** `admins/admin_token_remove_by_admin_id.sql`
- **Cost:** N/A

## SQL
```sql
/* admin_token_remove_by_admin_id */
DELETE FROM `admin_tokens` WHERE `admin_id` = :adminId;

```

## Detected Issues
- 暗黙的な型変換が検出されました。 [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/ImplicitTypeConversion)

## Explain Tree
```
Table scan
+- Table
   table           admin_tokens
   rows            1
   filtered        100.00
   condition       (`sql_quality_db`.`admin_tokens`.`admin_id` = 1)
```
## Analysis Detail

### Schema
{"admin_tokens":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"admin_id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"token","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"UNI","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"expire_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"fk_admin_tokens_1","COLUMN_NAME":"admin_id","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":999},{"INDEX_NAME":"idx_admin_tokens_1","COLUMN_NAME":"token","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":999},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":999}],"status":{"table_rows":1000,"data_length":81920,"index_length":65536,"auto_increment":1001,"create_time":"2026-01-16 11:46:03","update_time":"2026-01-16 11:46:42"}}}

### EXPLAIN JSON
{"select_id":1,"table":{"delete":true,"table_name":"admin_tokens","access_type":"range","possible_keys":["fk_admin_tokens_1"],"key":"fk_admin_tokens_1","used_key_parts":["admin_id"],"key_length":"4","ref":["const"],"rows_examined_per_scan":1,"filtered":"100.00","attached_condition":"(`sql_quality_db`.`admin_tokens`.`admin_id` = 1)"}}

### EXPLAIN ANALYZE
<not executable by iterator executor>

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。