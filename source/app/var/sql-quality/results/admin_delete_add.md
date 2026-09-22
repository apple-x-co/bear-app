# SQL Performance Analysis
- **SQL File:** `admins/admin_delete_add.sql`
- **Cost:** N/A

## SQL
```sql
/* admin_delete_add */
INSERT INTO `admin_deletes` (`admin_id`, `request_date`, `schedule_date`, `deleted_date`, `created_date`)
VALUES (:adminId, :requestDate, :scheduleDate, NULL, :createdDate);

```

## Detected Issues
- フルテーブルスキャンが検出されました。 [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/FullTableScan)

## Explain Tree
```
Table scan
+- Table
   table           admin_deletes
   rows            
   filtered        
```
## Analysis Detail

### Schema
N/A

### EXPLAIN JSON
{"select_id":1,"table":{"insert":true,"table_name":"admin_deletes","access_type":"ALL"}}

### EXPLAIN ANALYZE
N/A (EXPLAIN ANALYZE skipped: statement is not a read-only SELECT)
### SHOW WARNINGS
[{"Level":"Note","Code":1003,"Message":"insert into `sql_quality_db`.`admin_deletes` (`sql_quality_db`.`admin_deletes`.`admin_id`,`sql_quality_db`.`admin_deletes`.`request_date`,`sql_quality_db`.`admin_deletes`.`schedule_date`,`sql_quality_db`.`admin_deletes`.`deleted_date`,`sql_quality_db`.`admin_deletes`.`created_date`) values (1,'2024-01-01 12:00:00','2024-01-01 12:00:00',NULL,'2024-01-01 12:00:00')"}]

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。