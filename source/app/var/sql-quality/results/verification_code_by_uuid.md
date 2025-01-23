# SQL Performance Analysis
- **SQL File:** `verification_code_by_uuid.sql`
- **Cost:** N/A

## SQL
```sql
SELECT `id`, `uuid`, `email_address`, `url`, `code`, `expire_at`, `verified_at`, `created_at`, `updated_at`
  FROM `verification_codes`
 WHERE `uuid` = :uuid
   AND `expire_at` >= NOW()
   AND `verified_at` IS NULL;

```

## Detected Issues


## Explain Tree
```
Message
info            no matching row in const table
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
{"verification_codes":{"columns":[{"COLUMN_NAME":"id","DATA_TYPE":"int","COLUMN_TYPE":"int unsigned","IS_NULLABLE":"NO","COLUMN_KEY":"PRI","COLUMN_DEFAULT":null,"EXTRA":"auto_increment"},{"COLUMN_NAME":"uuid","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"UNI","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"email_address","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(100)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"url","DATA_TYPE":"text","COLUMN_TYPE":"text","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"code","DATA_TYPE":"varchar","COLUMN_TYPE":"varchar(255)","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"expire_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"verified_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"YES","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"created_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""},{"COLUMN_NAME":"updated_at","DATA_TYPE":"datetime","COLUMN_TYPE":"datetime","IS_NULLABLE":"NO","COLUMN_KEY":"","COLUMN_DEFAULT":null,"EXTRA":""}],"indexes":[{"INDEX_NAME":"idx_codes_1","COLUMN_NAME":"uuid","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000},{"INDEX_NAME":"PRIMARY","COLUMN_NAME":"id","NON_UNIQUE":0,"SEQ_IN_INDEX":1,"CARDINALITY":1000}],"status":{"table_rows":1000,"data_length":16384,"index_length":0,"auto_increment":1001,"create_time":"2025-01-23 09:55:20","update_time":"2025-01-23 09:55:23"}}}

### EXPLAIN Results
{"query_block":{"select_id":1,"message":"no matching row in const table"}}

以上の分析を日本語で記述してください。