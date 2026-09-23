# SQL Performance Analysis
- **SQL File:** `bad_passwords/bad_password_add.sql`
- **Cost:** N/A

## SQL
```sql
/* bad_password_add */
INSERT INTO `bad_passwords` (`password`, `created_date`)
VALUES (:password, :createdDate);

```

## Detected Issues
- フルテーブルスキャンが検出されました。 [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/FullTableScan)

## Explain Tree
```
Table scan
+- Table
   table           bad_passwords
   rows            
   filtered        
```
## Analysis Detail

### Schema
N/A

### EXPLAIN JSON
{"select_id":1,"table":{"insert":true,"table_name":"bad_passwords","access_type":"ALL"}}

### EXPLAIN ANALYZE
N/A (EXPLAIN ANALYZE skipped: statement is not a read-only SELECT)
### SHOW WARNINGS
[{"Level":"Note","Code":1003,"Message":"insert into `sql_quality_db`.`bad_passwords` (`sql_quality_db`.`bad_passwords`.`password`,`sql_quality_db`.`bad_passwords`.`created_date`) values ('password123','2024-01-01 12:00:00')"}]

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。