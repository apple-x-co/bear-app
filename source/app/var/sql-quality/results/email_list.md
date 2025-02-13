# SQL Performance Analysis
- **SQL File:** `email_list.sql`
- **Cost:** 1.10

## SQL
```sql
/* email_list */
SELECT `id`, `sender_email_address`, `sender_name`, `subject`, `text`, `html`, `schedule_at`, `sent_at`, `created_at`
  FROM `emails`
 WHERE `sent_at` IS NULL
   AND `schedule_at` <= NOW();

```

## Detected Issues


## Explain Tree
```
Table scan
+- Table
   table           emails
   rows            1
   filtered        33.33
   condition       (`sql_quality_db`.`emails`.`schedule_at` <= <cache>(now()))
```
## Analysis Detail

### Schema
{"emails":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"sender_email_address","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"sender_name","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"subject","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"text","DATA_TYPE":"text","COLUMN_TYPE":"text","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"html","DATA_TYPE":"text","COLUMN_TYPE":"text","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"schedule_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"sent_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"idx_emails_1","COLUMN_NAME":"sent_at","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":343},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":147456,"index_length":0,"auto_increment":1001,"create_time":"2025-01-25 16:11:40","update_time":null}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"1.10"},"table":{"table_name":"emails","access_type":"ref","possible_keys":["idx_emails_1"],"key":"idx_emails_1","used_key_parts":["sent_at"],"key_length":"6","ref":["const"],"rows_examined_per_scan":1,"rows_produced_per_join":0,"filtered":"33.33","index_condition":"(`sql_quality_db`.`emails`.`sent_at` is null)","cost_info":{"read_cost":"1.00","eval_cost":"0.03","prefix_cost":"1.10","data_read_per_join":"415"},"used_columns":["id","sender_email_address","sender_name","subject","text","html","schedule_at","sent_at","created_at"],"attached_condition":"(`sql_quality_db`.`emails`.`schedule_at` <= <cache>(now()))"}}

### EXPLAIN ANALYZE
-> Filter: (emails.schedule_at <= <cache>(now()))  (cost=1.03 rows=0.333) (actual time=0.00171..0.00171 rows=0 loops=1)
    -> Index lookup on emails using idx_emails_1 (sent_at=NULL), with index condition: (emails.sent_at is null)  (cost=1.03 rows=1) (actual time=0.0015..0.0015 rows=0 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。