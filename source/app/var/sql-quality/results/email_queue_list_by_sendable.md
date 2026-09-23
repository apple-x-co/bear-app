# SQL Performance Analysis
- **SQL File:** `email_queues/email_queue_list_by_sendable.sql`
- **Cost:** 0.35

## SQL
```sql
/* email_queue_list_by_sendable */
SELECT `id`
     , `sender_email_address`
     , `sender_name`
     , `subject`
     , `text`
     , `html`
     , `priority`
     , `active`
     , `attempts`
     , `max_attempts`
     , `schedule_date`
     , `sent_date`
     , `created_date`
FROM `email_queues`
WHERE `sent_date` IS NULL
  AND `schedule_date` <= :scheduleDate
  AND `active` = 1
  AND `max_attempts` > `attempts`
ORDER BY CASE `priority`
             WHEN 'high' THEN 0
             WHEN 'normal' THEN 1
             ELSE 2
         END, `schedule_date`;

```

## Detected Issues
- 暗黙的な型変換が検出されました。 [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/ImplicitTypeConversion)

## Explain Tree
```
Sort (using filesort)
+- Table scan
   +- Table
      table           email_queues
      rows            1
      filtered        5.00
      condition       ((`sql_quality_db`.`email_queues`.`active` = 1) and (`sql_quality_db`.`email_queues`.`schedule_date` <= TIMESTAMP'2024-01-01 12:00:00') and (`sql_quality_db`.`email_queues`.`max_attempts` > `sql_quality_db`.`email_queues`.`attempts`))
```
## Analysis Detail

### Schema
{"email_queues":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"bigint","COLUMN_TYPE":"bigint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"sender_email_address","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"sender_name","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"subject","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"text","DATA_TYPE":"text","COLUMN_TYPE":"text","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"html","DATA_TYPE":"text","COLUMN_TYPE":"text","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"priority","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(10)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"active","DATA_TYPE":"smallint","COLUMN_TYPE":"smallint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"attempts","DATA_TYPE":"smallint","COLUMN_TYPE":"smallint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"max_attempts","DATA_TYPE":"smallint","COLUMN_TYPE":"smallint unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"schedule_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"sent_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_date","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"idx_email_queues_1","COLUMN_NAME":"sent_date","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":356},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":163840,"index_length":49152,"auto_increment":1001,"create_time":"2026-09-22 16:39:48","update_time":"2026-09-22 16:40:38"}}}

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"0.35"},"ordering_operation":{"using_filesort":true,"table":{"table_name":"email_queues","access_type":"ref","possible_keys":["idx_email_queues_1"],"key":"idx_email_queues_1","used_key_parts":["sent_date"],"key_length":"6","ref":["const"],"rows_examined_per_scan":1,"rows_produced_per_join":0,"filtered":"5.00","index_condition":"(`sql_quality_db`.`email_queues`.`sent_date` is null)","cost_info":{"read_cost":"0.25","eval_cost":"0.01","prefix_cost":"0.35","data_read_per_join":"65"},"used_columns":["id","sender_email_address","sender_name","subject","text","html","priority","active","attempts","max_attempts","schedule_date","sent_date","created_date"],"attached_condition":"((`sql_quality_db`.`email_queues`.`active` = 1) and (`sql_quality_db`.`email_queues`.`schedule_date` <= TIMESTAMP'2024-01-01 12:00:00') and (`sql_quality_db`.`email_queues`.`max_attempts` > `sql_quality_db`.`email_queues`.`attempts`))"}}}

### EXPLAIN ANALYZE
-> Sort: (case email_queues.priority when 'high' then 0 when 'normal' then 1 else 2 end), email_queues.schedule_date  (cost=0.255 rows=1) (actual time=0.0025..0.0025 rows=0 loops=1)
    -> Filter: ((email_queues.`active` = 1) and (email_queues.schedule_date <= TIMESTAMP'2024-01-01 12:00:00') and (email_queues.max_attempts > email_queues.attempts))  (cost=0.255 rows=1) (actual time=0.00171..0.00171 rows=0 loops=1)
        -> Index lookup on email_queues using idx_email_queues_1 (sent_date=NULL), with index condition: (email_queues.sent_date is null)  (cost=0.255 rows=1) (actual time=0.00158..0.00158 rows=0 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。