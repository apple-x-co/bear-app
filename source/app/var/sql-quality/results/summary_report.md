# SQL Analysis Summary

## Query Analysis

| SQL File | Cost | Exec Time (ms) | Level | Issues | Report |
|----------|------|----------------|-------|---------|--------|
| admin\_delete\_list.sql | 1.10 | 0.04 | Medium (μ ± σ) | - | [Details](admin\_delete\_list.md) |
| admin\_email\_by\_admin\_id.sql | 0.95 | 0.06 | Medium (μ ± σ) | - | [Details](admin\_email\_by\_admin\_id.md) |
| admin\_item.sql | 1.00 | 0.05 | Medium (μ ± σ) | - | [Details](admin\_item.md) |
| admin\_item\_by\_email.sql | 1.00 | 0.06 | Medium (μ ± σ) | FullTableScan | [Details](admin\_item\_by\_email.md) |
| admin\_item\_by\_username.sql | 1.00 | 0.07 | Medium (μ ± σ) | - | [Details](admin\_item\_by\_username.md) |
| admin\_permission\_by\_admin\_id.sql | 0.91 | 0.05 | Medium (μ ± σ) | - | [Details](admin\_permission\_by\_admin\_id.md) |
| admin\_token\_item\_by\_token.sql | 1.00 | 0.05 | Medium (μ ± σ) | - | [Details](admin\_token\_item\_by\_token.md) |
| bad\_password\_item.sql | 1.00 | 0.05 | Medium (μ ± σ) | - | [Details](bad\_password\_item.md) |
| email\_list.sql | 1.10 | 0.05 | Medium (μ ± σ) | - | [Details](email\_list.md) |
| email\_recipient\_list.sql | 0.95 | 0.04 | Medium (μ ± σ) | - | [Details](email\_recipient\_list.md) |
| test\_item.sql | 1.00 | 0.04 | Medium (μ ± σ) | - | [Details](test\_item.md) |
| test\_list.sql | 101.25 | 0.36 | ⚠️⚠️Very High (> μ + 2σ) | FullTableScan | [Details](test\_list.md) |
| throttle\_item\_by\_key.sql | 1.10 | 0.07 | Medium (μ ± σ) | - | [Details](throttle\_item\_by\_key.md) |
| verification\_code\_by\_uuid.sql | 1.00 | 0.05 | Medium (μ ± σ) | - | [Details](verification\_code\_by\_uuid.md) |

## Queries with Optimizer Impact

| SQL File | Base Access | Optimized Access | Cost Impact | Base Issues | Plan Changes |
|:----------|:------------|:----------------|:------------|:------------|:-------------|
| admin\_item\_by\_email.sql | ALL, 1000 rows, 100.0% | Unknown | -99.0% | FullTableScan | - |

## Statistics

- Total queries analyzed: 14
- Average query cost: 8.17
- Standard deviation: 25.82

