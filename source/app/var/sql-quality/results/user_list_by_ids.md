# SQL Performance Analysis
- **SQL File:** `users/user_list_by_ids.sql`
- **Cost:** N/A

## SQL
```sql
/** user_list_by_ids */
SELECT `id`
     , `uid`
     , `username`
     , `password`
     , `active`
     , `signup_date`
     , `leaved_date`
     , `purge_date`
     , `last_logged_in_date`
     , `created_date`
     , `updated_date`
FROM `users`
WHERE `id` IN (:ids);

```

## Detected Issues


## Explain Tree
```
Message
info            no matching row in const table
```
## Analysis Detail

### Schema
{"users":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"uid","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(36)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"display_name","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"username","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(255)","IS_NULLABLE":"NO","COLUMN_KEY":"UNI","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"password","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(255)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"active","DATA_TYPE":"smallint","COLUMN_TYPE":"smallint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"signup_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"leaved_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"purge_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"last_logged_in_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"updated_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"idx_users_1","COLUMN_NAME":"username","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":999},{"INDEX_NAME":"idx_users_2","COLUMN_NAME":"purge_date","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":1},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":999}],"status":{"table_rows":1000,"data_length":16384,"index_length":0,"auto_increment":1001,"create_time":"2026-01-16 11:46:03","update_time":"2026-01-16 11:46:44"}}}

### EXPLAIN JSON
{"select_id":1,"message":"no matching row in const table"}

### EXPLAIN ANALYZE
-> Zero rows (no matching row in const table)  (cost=0..0 rows=0) (actual time=84e-6..84e-6 rows=0 loops=1)

### SHOW WARNINGS
[{"Level":"Warning","Code":1292,"Message":"Truncated incorrect DOUBLE value: 'test value'"}]

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。