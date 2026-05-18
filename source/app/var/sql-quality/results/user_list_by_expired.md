# SQL Performance Analysis
- **SQL File:** `users/user_list_by_expired.sql`
- **Cost:** 0.71

## SQL
```sql
/** user_list_by_expired */
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
WHERE `active` = 0
  AND `leaved_date` < :dateTime
  AND `purge_date` < :dateTime;

```

## Detected Issues
- 暗黙的な型変換が検出されました。 [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/ImplicitTypeConversion)

## Explain Tree
```
Table scan
+- Table
   table           users
   rows            1
   filtered        5.00
   condition       ((`sql_quality_db`.`users`.`active` = 0) and (`sql_quality_db`.`users`.`leaved_date` < TIMESTAMP'2100-01-01 00:00:00'))
```
## Analysis Detail

### Schema
{"users":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"uid","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(36)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"display_name","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"username","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(255)","IS_NULLABLE":"NO","COLUMN_KEY":"UNI","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"password","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(255)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"active","DATA_TYPE":"smallint","COLUMN_TYPE":"smallint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"signup_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"leaved_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"purge_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"last_logged_in_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"updated_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"idx_users_1","COLUMN_NAME":"username","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000},{"INDEX_NAME":"idx_users_2","COLUMN_NAME":"purge_date","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":1},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":114688,"index_length":65536,"auto_increment":1003,"create_time":"2026-01-16 11:46:03","update_time":"2026-01-17 15:43:48"}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"0.71"},"table":{"table_name":"users","access_type":"range","possible_keys":["idx_users_2"],"key":"idx_users_2","used_key_parts":["purge_date"],"key_length":"6","rows_examined_per_scan":1,"rows_produced_per_join":0,"filtered":"5.00","index_condition":"(`sql_quality_db`.`users`.`purge_date` < TIMESTAMP'2100-01-01 00:00:00')","cost_info":{"read_cost":"0.70","eval_cost":"0.01","prefix_cost":"0.71","data_read_per_join":"131"},"used_columns":["id","uid","username","password","active","signup_date","leaved_date","purge_date","last_logged_in_date","created_date","updated_date"],"attached_condition":"((`sql_quality_db`.`users`.`active` = 0) and (`sql_quality_db`.`users`.`leaved_date` < TIMESTAMP'2100-01-01 00:00:00'))"}}

### EXPLAIN ANALYZE
-> Filter: ((users.`active` = 0) and (users.leaved_date < TIMESTAMP'2100-01-01 00:00:00'))  (cost=0.71 rows=0.05) (actual time=0.0055..0.0055 rows=0 loops=1)
    -> Index range scan on users using idx_users_2 over (NULL < purge_date < '2100-01-01 00:00:00'), with index condition: (users.purge_date < TIMESTAMP'2100-01-01 00:00:00')  (cost=0.71 rows=1) (actual time=0.00421..0.00471 rows=1 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。