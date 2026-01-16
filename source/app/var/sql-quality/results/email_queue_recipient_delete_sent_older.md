# SQL Performance Analysis
- **SQL File:** `email_queue_recipients/email_queue_recipient_delete_sent_older.sql`
- **Cost:** 0.81

## SQL
```sql
/* email_queue_recipient_delete_sent_older */
DELETE
FROM `email_queue_recipients`
WHERE EXISTS(SELECT 1
             FROM `email_queues`
             WHERE `email_queues`.`id` = `email_queue_recipients`.`email_queue_id`
               AND `email_queues`.`sent_date` <= :sentDate);

```

## Detected Issues


## Explain Tree
```
JOIN
+- Index lookup
   key             fk_email_queue_recipients_1
   rows            1
   filtered        100.00
   +- Table
      table           email_queue_recipients
```
## Analysis Detail

### Schema
{"email_queue_recipients":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"bigint","COLUMN_TYPE":"bigint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"email_queue_id","DATA_TYPE":"bigint","COLUMN_TYPE":"bigint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"recipient_type","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(10)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"recipient_email_address","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"recipient_name","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"fk_email_queue_recipients_1","COLUMN_NAME":"email_queue_id","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":1000},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":16384,"index_length":16384,"auto_increment":1001,"create_time":"2026-01-16 11:46:03","update_time":"2026-01-16 11:46:43"}},"email_queues":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"bigint","COLUMN_TYPE":"bigint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"sender_email_address","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"sender_name","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"subject","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"text","DATA_TYPE":"text","COLUMN_TYPE":"text","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"html","DATA_TYPE":"text","COLUMN_TYPE":"text","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"active","DATA_TYPE":"smallint","COLUMN_TYPE":"smallint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"attempts","DATA_TYPE":"smallint","COLUMN_TYPE":"smallint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"max_attempts","DATA_TYPE":"smallint","COLUMN_TYPE":"smallint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"schedule_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"sent_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"idx_email_queues_1","COLUMN_NAME":"sent_date","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":542},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":163840,"index_length":49152,"auto_increment":1001,"create_time":"2026-01-16 11:46:03","update_time":"2026-01-16 11:46:43"}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"0.81"},"nested_loop":[{"table":{"table_name":"email_queues","access_type":"range","possible_keys":["PRIMARY","idx_email_queues_1"],"key":"idx_email_queues_1","used_key_parts":["sent_date"],"key_length":"6","rows_examined_per_scan":1,"rows_produced_per_join":1,"filtered":"100.00","using_index":true,"cost_info":{"read_cost":"0.36","eval_cost":"0.10","prefix_cost":"0.46","data_read_per_join":"1K"},"used_columns":["id","sent_date"],"attached_condition":"(`sql_quality_db`.`email_queues`.`sent_date` <= TIMESTAMP'2024-01-01 12:00:00')"}},{"table":{"delete":true,"table_name":"email_queue_recipients","access_type":"ref","possible_keys":["fk_email_queue_recipients_1"],"key":"fk_email_queue_recipients_1","used_key_parts":["email_queue_id"],"key_length":"8","ref":["sql_quality_db.email_queues.id"],"rows_examined_per_scan":1,"rows_produced_per_join":1,"filtered":"100.00","cost_info":{"read_cost":"0.25","eval_cost":"0.10","prefix_cost":"0.81","data_read_per_join":"872"},"used_columns":["id","email_queue_id","recipient_type","recipient_email_address","recipient_name","created_date"]}}]}

### EXPLAIN ANALYZE
-> Delete from email_queue_recipients (buffered)  (cost=0.91 rows=1) (actual time=0.006..0.006 rows=0 loops=1)
    -> Nested loop inner join  (cost=0.81 rows=1) (actual time=0.00487..0.00487 rows=0 loops=1)
        -> Filter: (email_queues.sent_date <= TIMESTAMP'2024-01-01 12:00:00')  (cost=0.46 rows=1) (actual time=0.00396..0.00396 rows=0 loops=1)
            -> Covering index range scan on email_queues using idx_email_queues_1 over (NULL < sent_date <= '2024-01-01 12:00:00')  (cost=0.46 rows=1) (actual time=0.00354..0.00354 rows=0 loops=1)
        -> Index lookup on email_queue_recipients using fk_email_queue_recipients_1 (email_queue_id=email_queues.id)  (cost=0.35 rows=1) (never executed)

### SHOW WARNINGS
[{"Level":"Note","Code":1276,"Message":"Field or reference 'sql_quality_db.email_queue_recipients.email_queue_id' of SELECT #2 was resolved in SELECT #1"}]

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。