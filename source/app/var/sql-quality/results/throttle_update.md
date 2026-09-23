# SQL Performance Analysis
- **SQL File:** `throttles/throttle_update.sql`
- **Cost:** N/A

## SQL
```sql
/* throttle_update */
UPDATE `throttles`
   SET `remote_ip` = :remoteIp,
       `iteration_count` = :iterationCount,
       `expire_date` = :expireDate,
       `updated_date` = :updatedDate
 WHERE `id` = :id;

```

## Detected Issues
- 暗黙的な型変換が検出されました。 [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/ImplicitTypeConversion)

## Explain Tree
```
Table scan
+- Table
   table           throttles
   rows            1
   filtered        100.00
   condition       (`sql_quality_db`.`throttles`.`id` = 1)
```
## Analysis Detail

### Schema
N/A

### EXPLAIN JSON
{"select_id":1,"table":{"update":true,"table_name":"throttles","access_type":"range","possible_keys":["PRIMARY"],"key":"PRIMARY","used_key_parts":["id"],"key_length":"4","ref":["const"],"rows_examined_per_scan":1,"filtered":"100.00","attached_condition":"(`sql_quality_db`.`throttles`.`id` = 1)"}}

### EXPLAIN ANALYZE
N/A (EXPLAIN ANALYZE skipped: statement is not a read-only SELECT)
### SHOW WARNINGS
[{"Level":"Note","Code":1003,"Message":"update `sql_quality_db`.`throttles` set `sql_quality_db`.`throttles`.`remote_ip` = '127.0.0.1',`sql_quality_db`.`throttles`.`iteration_count` = 100,`sql_quality_db`.`throttles`.`expire_date` = '2024-01-01 12:00:00',`sql_quality_db`.`throttles`.`updated_date` = '2024-01-01 12:00:00' where (`sql_quality_db`.`throttles`.`id` = 1)"}]

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。