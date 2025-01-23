# SQL Performance Analysis
- **SQL File:** `admin_delete_list.sql`
- **Cost:** 10.25

## SQL
```sql
/* admin_delete_list */
SELECT `admin_id`, `request_at`, `schedule_at`, `deleted_at`, `created_at`
  FROM `admin_deletes`
 WHERE `schedule_at` <= NOW()
   AND `deleted_at` IS NULL;

```

## Detected Issues
- Full table scan detected. [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/FullTableScan)

## Explain Tree
```
Table scan
+- Table
   table           admin_deletes
   rows            100
   filtered        10.00
   condition       ((`sql_quality_db`.`admin_deletes`.`schedule_at` <= <cache>(now())) and (`sql_quality_db`.`admin_deletes`.`deleted_at` is null))
```

## AI Prompt
Based on the provided MySQL table schemas and EXPLAIN results, please provide:

1. Brief Assessment
   - Summarize key performance bottlenecks identified in the EXPLAIN output
   - Highlight any concerning access patterns (table scans, suboptimal joins)
   - Note any missing or underutilized indexes

2. Specific Optimization Recommendations
   a) Index Improvements
      - New indexes to create (with exact column combinations)
      - Existing indexes to modify or remove
      - Coverage analysis for frequently accessed columns
   b) Query Optimization
      - Join order and method improvements
      - Subquery optimization opportunities
      - Filtering and sorting efficiency
   c) Schema Enhancements (if applicable)
      - Table structure improvements
      - Partitioning considerations
      - Data type optimizations

3. Implementation Details
   For each recommendation:
     - Exact SQL statements for implementation
     - Estimated impact on query performance
     - Potential risks or trade-offs
     - Implementation priority (High/Medium/Low)

4. Additional Considerations
   - Impact on existing indexes and storage requirements
   - Effects on write performance
   - Maintenance requirements
   - Backup/restore implications

Please focus on practical, high-impact improvements that can be implemented with minimal risk.

### Schema
{"admin_deletes":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"admin_id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"UNI","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"request_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"schedule_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"MUL","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"deleted_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"idx_admin_deletes_1","COLUMN_NAME":"admin_id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":100},{"INDEX_NAME":"idx_admin_deletes_2","COLUMN_NAME":"schedule_at","NON_UNIQUE":1,"SEQ_IN_INDEX":1,"CARDINALITY":89},{"INDEX_NAME":"idx_admin_deletes_2","COLUMN_NAME":"deleted_at","NON_UNIQUE":1,"SEQ_IN_INDEX":2,"CARDINALITY":100},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":100}],"status":{"table_rows":100,"data_length":16384,"index_length":32768,"auto_increment":101,"create_time":"2025-01-23 09:55:20","update_time":"2025-01-23 09:55:21"}}}

### EXPLAIN Results
{"query_block":{"select_id":1,"cost_info":{"query_cost":"10.25"},"table":{"table_name":"admin_deletes","access_type":"ALL","possible_keys":["idx_admin_deletes_2"],"rows_examined_per_scan":100,"rows_produced_per_join":10,"filtered":"10.00","cost_info":{"read_cost":"9.25","eval_cost":"1.00","prefix_cost":"10.25","data_read_per_join":"320"},"used_columns":["admin_id","request_at","schedule_at","deleted_at","created_at"],"attached_condition":"((`sql_quality_db`.`admin_deletes`.`schedule_at` <= <cache>(now())) and (`sql_quality_db`.`admin_deletes`.`deleted_at` is null))"}}}

以上の分析を日本語で記述してください。