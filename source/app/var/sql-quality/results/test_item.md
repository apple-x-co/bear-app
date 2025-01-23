# SQL Performance Analysis
- **SQL File:** `test_item.sql`
- **Cost:** 101.25

## SQL
```sql
/* test item */
SELECT id, title, date_created
  FROM test
 WHERE id = :id

```

## Detected Issues
- Full table scan detected. [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/FullTableScan)

## Explain Tree
```
Table scan
+- Table
   table           test
   rows            1000
   filtered        10.00
   condition       (`sql_quality_db`.`test`.`id` = 1)
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
{"test":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(255)","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":"","EXTRA":""},{"COLUMN_NAME":"title","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(255)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"date_created","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":16384,"index_length":0,"auto_increment":null,"create_time":"2025-01-23 09:55:20","update_time":"2025-01-23 09:55:23"}}}

### EXPLAIN Results
{"query_block":{"select_id":1,"cost_info":{"query_cost":"101.25"},"table":{"table_name":"test","access_type":"ALL","possible_keys":["PRIMARY"],"rows_examined_per_scan":1000,"rows_produced_per_join":100,"filtered":"10.00","cost_info":{"read_cost":"91.25","eval_cost":"10.00","prefix_cost":"101.25","data_read_per_join":"200K"},"used_columns":["id","title","date_created"],"attached_condition":"(`sql_quality_db`.`test`.`id` = 1)"}}}

以上の分析を日本語で記述してください。