# SQL Analysis Summary

## Query Analysis

| SQL File | Cost | Exec Time (ms) | Level | Issues | Report |
|----------|------|----------------|-------|---------|--------|
| admins/admin\_delete\_list.sql | 0.35 | 0.12 | Low (< μ) | - | [Details](admin\_delete\_list.md) |
| admins/admin\_email\_by\_admin\_id.sql | 0.35 | 0.08 | Low (< μ) | - | [Details](admin\_email\_by\_admin\_id.md) |
| admins/admin\_item.sql | 1.00 | 0.13 | Medium (μ ± σ) | - | [Details](admin\_item.md) |
| admins/admin\_item\_by\_email.sql | 1.00 | 0.08 | Medium (μ ± σ) | FullTableScan | [Details](admin\_item\_by\_email.md) |
| admins/admin\_item\_by\_username.sql | 1.00 | 0.10 | Medium (μ ± σ) | - | [Details](admin\_item\_by\_username.md) |
| admins/admin\_permission\_by\_admin\_id.sql | 0.35 | 0.08 | Low (< μ) | - | [Details](admin\_permission\_by\_admin\_id.md) |
| admins/admin\_token\_item\_by\_token.sql | 1.00 | 0.10 | Medium (μ ± σ) | - | [Details](admin\_token\_item\_by\_token.md) |
| bad\_passwords/bad\_password\_item.sql | 1.00 | 0.06 | Medium (μ ± σ) | - | [Details](bad\_password\_item.md) |
| email\_queues/email\_queue\_list\_by\_sendable.sql | 0.35 | 0.10 | Low (< μ) | - | [Details](email\_queue\_list\_by\_sendable.md) |
| throttles/throttle\_item\_by\_key.sql | 0.35 | 0.08 | Low (< μ) | - | [Details](throttle\_item\_by\_key.md) |
| verification\_codes/verification\_code\_by\_uuid.sql | 1.00 | 0.12 | Medium (μ ± σ) | - | [Details](verification\_code\_by\_uuid.md) |

## Queries with Optimizer Impact

| SQL File | Base Access | Optimized Access | Cost Impact | Base Issues | Plan Changes |
|:----------|:------------|:----------------|:------------|:------------|:-------------|
| admins/admin\_item\_by\_email.sql | ALL, 1000 rows, 100.0% | Unknown | -99.0% | FullTableScan | - |

## Statistics

- Total queries analyzed: 11
- Average query cost: 0.70
- Standard deviation: 0.32

