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
## Analysis Detail

### Schema
N/A

### EXPLAIN JSON
{"select_id":1,"cost_info":{"query_cost":"101.25"},"table":{"table_name":"test","access_type":"ALL","rows_examined_per_scan":1000,"rows_produced_per_join":1000,"filtered":"100.00","cost_info":{"read_cost":"1.25","eval_cost":"100.00","prefix_cost":"101.25","data_read_per_join":"1M"},"used_columns":["id","title","date_created"]}}

### EXPLAIN ANALYZE
-> Table scan on test  (cost=101 rows=1000) (actual time=0.00625..0.138 rows=1000 loops=1)

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。