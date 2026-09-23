# SQL Performance Analysis
- **SQL File:** `admins/admin_permission_add.sql`
- **Cost:** N/A

## SQL
```sql
/* admin_permission_add */
INSERT INTO `admin_permissions` (`admin_id`, `access`, `resource_name`, `permission_name`, `created_date`)
VALUES (:adminId, :access, :resourceName, :permissionName, :createdDate);

```

## Detected Issues
- フルテーブルスキャンが検出されました。 [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/FullTableScan)

## Explain Tree
```
Table scan
+- Table
   table           admin_permissions
   rows            
   filtered        
```
## Analysis Detail

### Schema
N/A

### EXPLAIN JSON
{"select_id":1,"table":{"insert":true,"table_name":"admin_permissions","access_type":"ALL"}}

### EXPLAIN ANALYZE
N/A (EXPLAIN ANALYZE skipped: statement is not a read-only SELECT)
### SHOW WARNINGS
[{"Level":"Note","Code":1003,"Message":"insert into `sql_quality_db`.`admin_permissions` (`sql_quality_db`.`admin_permissions`.`admin_id`,`sql_quality_db`.`admin_permissions`.`access`,`sql_quality_db`.`admin_permissions`.`resource_name`,`sql_quality_db`.`admin_permissions`.`permission_name`,`sql_quality_db`.`admin_permissions`.`created_date`) values (1,'allow','Test Name','Test Name','2024-01-01 12:00:00')"}]

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。