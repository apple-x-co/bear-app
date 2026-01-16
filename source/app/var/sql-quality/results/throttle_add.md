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
```
## Analysis Detail

### Schema
N/A

### EXPLAIN JSON
{"select_id":1,"table":{"insert":true,"table_name":"throttles","access_type":"ALL"}}

### EXPLAIN ANALYZE
<not executable by iterator executor>

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。