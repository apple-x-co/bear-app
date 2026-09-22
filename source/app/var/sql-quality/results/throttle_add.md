# SQL Performance Analysis
- **SQL File:** `throttles/throttle_add.sql`
- **Cost:** N/A

## SQL
```sql
/* throttle_add */
INSERT INTO `throttles` (`throttle_key`, `remote_ip`, `iteration_count`, `max_attempts`, `interval`, `expire_date`, `created_date`, `updated_date`)
VALUES (:throttleKey, :remoteIp, :iterationCount, :maxAttempts, :interval, :expireDate, :createdDate, :updatedDate);

```

## Detected Issues
- フルテーブルスキャンが検出されました。 [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/FullTableScan)

## Explain Tree
```
Table scan
+- Table
   table           throttles
   rows            
   filtered        
```
## Analysis Detail

### Schema
N/A

### EXPLAIN JSON
{"select_id":1,"table":{"insert":true,"table_name":"throttles","access_type":"ALL"}}

### EXPLAIN ANALYZE
N/A (EXPLAIN ANALYZE skipped: statement is not a read-only SELECT)
### SHOW WARNINGS
[{"Level":"Note","Code":1003,"Message":"insert into `sql_quality_db`.`throttles` (`sql_quality_db`.`throttles`.`throttle_key`,`sql_quality_db`.`throttles`.`remote_ip`,`sql_quality_db`.`throttles`.`iteration_count`,`sql_quality_db`.`throttles`.`max_attempts`,`sql_quality_db`.`throttles`.`interval`,`sql_quality_db`.`throttles`.`expire_date`,`sql_quality_db`.`throttles`.`created_date`,`sql_quality_db`.`throttles`.`updated_date`) values ('test-key','127.0.0.1',100,10,'30 minutes','2024-01-01 12:00:00','2024-01-01 12:00:00','2024-01-01 12:00:00')"}]

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。