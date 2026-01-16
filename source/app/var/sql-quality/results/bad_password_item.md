# SQL Performance Analysis
- **SQL File:** `bad_passwords/bad_password_item.sql`
- **Cost:** N/A

## SQL
```sql
SELECT `password`, `created_date`
  FROM `bad_passwords`
 WHERE `password` = :password;

```

## Detected Issues


## Explain Tree
```
Message
info            no matching row in const table
```
## Analysis Detail

### Schema
{"bad_passwords":{"columns":[{"COLUMN_NAME":"password","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(255)","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"password","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":65536,"index_length":0,"auto_increment":null,"create_time":"2026-01-16 10:40:51","update_time":"2026-01-16 10:40:53"}}}

### EXPLAIN JSON
{"select_id":1,"message":"no matching row in const table"}

### EXPLAIN ANALYZE
-> Zero rows (no matching row in const table)  (cost=0..0 rows=0) (actual time=82e-6..82e-6 rows=0 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。