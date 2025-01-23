# SQL Performance Analysis
- **SQL File:** `test_list.sql`
- **Cost:** 101.25

## SQL
```sql
/* test list */
SELECT id, title, date_created
  FROM test;

```

## Detected Issues
- Full table scan detected. [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/FullTableScan)

## Explain Tree
```
Table scan
+- Table
   table           test
   rows            1000
   filtered        100.00
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
N/A

### EXPLAIN Results
{"query_block":{"select_id":1,"cost_info":{"query_cost":"101.25"},"table":{"table_name":"test","access_type":"ALL","rows_examined_per_scan":1000,"rows_produced_per_join":1000,"filtered":"100.00","cost_info":{"read_cost":"1.25","eval_cost":"100.00","prefix_cost":"101.25","data_read_per_join":"1M"},"used_columns":["id","title","date_created"]}}}

以上の分析を日本語で記述してください。