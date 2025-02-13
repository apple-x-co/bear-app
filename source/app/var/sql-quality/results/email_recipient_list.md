# SQL Performance Analysis
- **SQL File:** `email_recipient_list.sql`
- **Cost:** 0.95

## SQL
```sql
/* email_recipient_list */
SELECT `id`, `email_id`, `recipient_type`, `recipient_email_address`, `recipient_name`, `created_at`
  FROM `email_recipients`
 WHERE `email_id` IN (:emailIds);

```

## Detected Issues


## Explain Tree
```
Table scan
+- Table
   table           email_recipients
   rows            1
   filtered        100.00
```
## Analysis Detail

### Schema
{"email_recipients":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"email_id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"recipient_type","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(10)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"recipient_email_address","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"recipient_name","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"fk_email_recipients_1","COLUMN_NAME":"email_id","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":1000},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":98304,"index_length":16384,"auto_increment":1001,"create_time":"2025-01-23 09:55:20","update_time":null}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"0.95"},"table":{"table_name":"email_recipients","access_type":"ref","possible_keys":["fk_email_recipients_1"],"key":"fk_email_recipients_1","used_key_parts":["email_id"],"key_length":"4","ref":["const"],"rows_examined_per_scan":1,"rows_produced_per_join":1,"filtered":"100.00","cost_info":{"read_cost":"0.85","eval_cost":"0.10","prefix_cost":"0.95","data_read_per_join":"864"},"used_columns":["id","email_id","recipient_type","recipient_email_address","recipient_name","created_at"]}}

### EXPLAIN ANALYZE
-> Index lookup on email_recipients using fk_email_recipients_1 (email_id=1)  (cost=0.95 rows=1) (actual time=0.002..0.00225 rows=1 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。