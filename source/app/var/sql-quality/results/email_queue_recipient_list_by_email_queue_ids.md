# SQL Performance Analysis
- **SQL File:** `email_queue_recipients/email_queue_recipient_list_by_email_queue_ids.sql`
- **Cost:** 0.35

## SQL
```sql
/* email_queue_recipient_list */
SELECT `id`, `email_queue_id`, `recipient_type`, `recipient_email_address`, `recipient_name`, `created_date`
FROM `email_queue_recipients`
WHERE `email_queue_id` IN (:emailQueueIds)
ORDER BY `id` DESC;

```

## Detected Issues


## Explain Tree
```
Sort
+- Table scan
   +- Table
      table           email_queue_recipients
      rows            1
      filtered        100.00
      condition       (`sql_quality_db`.`email_queue_recipients`.`email_queue_id` = 0)
```
## Analysis Detail

### Schema
{"email_queue_recipients":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"bigint","COLUMN_TYPE":"bigint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"email_queue_id","DATA_TYPE":"bigint","COLUMN_TYPE":"bigint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"recipient_type","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(10)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"recipient_email_address","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"recipient_name","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"fk_email_queue_recipients_1","COLUMN_NAME":"email_queue_id","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":1000},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":16384,"index_length":16384,"auto_increment":1001,"create_time":"2026-01-16 11:46:03","update_time":"2026-01-16 11:46:43"}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"0.35"},"ordering_operation":{"using_filesort":false,"table":{"table_name":"email_queue_recipients","access_type":"ref","possible_keys":["fk_email_queue_recipients_1"],"key":"fk_email_queue_recipients_1","used_key_parts":["email_queue_id"],"key_length":"8","ref":["const"],"rows_examined_per_scan":1,"rows_produced_per_join":1,"filtered":"100.00","backward_index_scan":true,"cost_info":{"read_cost":"0.25","eval_cost":"0.10","prefix_cost":"0.35","data_read_per_join":"872"},"used_columns":["id","email_queue_id","recipient_type","recipient_email_address","recipient_name","created_date"],"attached_condition":"(`sql_quality_db`.`email_queue_recipients`.`email_queue_id` = 0)"}}}

### EXPLAIN ANALYZE
-> Filter: (email_queue_recipients.email_queue_id = 0)  (cost=0.35 rows=1) (actual time=0.003..0.003 rows=0 loops=1)
    -> Index lookup on email_queue_recipients using fk_email_queue_recipients_1 (email_queue_id=0) (reverse)  (cost=0.35 rows=1) (actual time=0.00254..0.00254 rows=0 loops=1)

### SHOW WARNINGS
[{"Level":"Warning","Code":1292,"Message":"Truncated incorrect DOUBLE value: 'test@example.com'"}]

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。