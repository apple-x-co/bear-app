# SQL Performance Analysis
- **SQL File:** `admins/admin_item_by_username.sql`
- **Cost:** 100.25

## SQL
```sql
/* admin_item_username */
SELECT `id`, `username`, `password`, `display_name`, `active`, `created_date`, `updated_date`
  FROM `admins`
 WHERE `username` = :username;

```

## Detected Issues
- フルテーブルスキャンが検出されました。 [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/FullTableScan)
- 非効率的な範囲スキャンが検出されました。範囲条件が多すぎる行をカバーしています。 [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/IneffectiveRangeScan)

## Explain Tree
```
Table scan
+- Table
   table           admins
   rows            1000
   filtered        10.00
   condition       (`sql_quality_db`.`admins`.`username` = 'Test Name')
```
## Analysis Detail

### Schema
{"admins":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"username","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"password","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(255)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"display_name","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"active","DATA_TYPE":"smallint","COLUMN_TYPE":"smallint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"updated_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":2}],"status":{"table_rows":1000,"data_length":16384,"index_length":0,"auto_increment":1001,"create_time":"2026-09-22 16:39:48","update_time":"2026-09-22 16:40:36"}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"100.25"},"table":{"table_name":"admins","access_type":"ALL","rows_examined_per_scan":1000,"rows_produced_per_join":100,"filtered":"10.00","cost_info":{"read_cost":"90.25","eval_cost":"10.00","prefix_cost":"100.25","data_read_per_join":"180K"},"used_columns":["id","username","password","display_name","active","created_date","updated_date"],"attached_condition":"(`sql_quality_db`.`admins`.`username` = 'Test Name')"}}

### EXPLAIN ANALYZE
-> Filter: (admins.username = 'Test Name')  (cost=100 rows=100) (actual time=0.37..0.37 rows=0 loops=1)
    -> Table scan on admins  (cost=100 rows=1000) (actual time=0.0181..0.309 rows=1000 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。