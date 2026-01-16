# SQL Performance Analysis
- **SQL File:** `verification_codes/verification_code_verified.sql`
- **Cost:** N/A

## SQL
```sql
/* verification_code_verified */
UPDATE `verification_codes`
   SET `verified_date` = :verifiedDate,
       `updated_date` = NOW()
 WHERE `id` = :id;

```

## Detected Issues
- 暗黙的な型変換が検出されました。 [Learn more](https://koriym.github.io/Koriym.SqlQuality/issues/ImplicitTypeConversion)

## Explain Tree
```
Table scan
+- Table
   table           verification_codes
   rows            1
   filtered        100.00
   condition       (`sql_quality_db`.`verification_codes`.`id` = 1)
```
## Analysis Detail

### Schema
N/A

### EXPLAIN JSON
{"select_id":1,"table":{"update":true,"table_name":"verification_codes","access_type":"range","possible_keys":["PRIMARY"],"key":"PRIMARY","used_key_parts":["id"],"key_length":"4","ref":["const"],"rows_examined_per_scan":1,"filtered":"100.00","attached_condition":"(`sql_quality_db`.`verification_codes`.`id` = 1)"}}

### EXPLAIN ANALYZE
<not executable by iterator executor>

### SHOW WARNINGS
N/A

## Analysis Instructions
Create a SQL performance analysis report for this query. Begin with a table of key metrics showing current values and their impact. Then describe the detected issues, focusing on the root causes. Follow with specific improvement recommendations, including SQL examples and their expected impact. End with implementation priorities and any important considerations. Keep the analysis focused on actionable insights that will lead to significant performance gains.


以上の分析を日本語で記述してください。