# SQL Analysis Summary

## Query Analysis

| SQL File | Cost | Exec Time (ms) | Level | Issues | Report |
|----------|------|----------------|-------|---------|--------|
| admins/admin\_delete\_list.sql | 0.35 | 0.12 | Medium (μ ± σ) | - | [Details](admin\_delete\_list.md) |
| admins/admin\_email\_by\_admin\_id.sql | 0.35 | 0.07 | Medium (μ ± σ) | - | [Details](admin\_email\_by\_admin\_id.md) |
| admins/admin\_email\_delete.sql | 1.00 | 0.06 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](admin\_email\_delete.md) |
| admins/admin\_email\_verified.sql | 1.00 | 0.11 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](admin\_email\_verified.md) |
| admins/admin\_item.sql | 1.00 | 0.07 | Medium (μ ± σ) | - | [Details](admin\_item.md) |
| admins/admin\_item\_by\_email.sql | 1.00 | 0.07 | Medium (μ ± σ) | FullTableScan | [Details](admin\_item\_by\_email.md) |
| admins/admin\_item\_by\_username.sql | 1.00 | 0.07 | Medium (μ ± σ) | - | [Details](admin\_item\_by\_username.md) |
| admins/admin\_password\_update.sql | 1.00 | 0.08 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](admin\_password\_update.md) |
| admins/admin\_permission\_by\_admin\_id.sql | 0.35 | 0.07 | Medium (μ ± σ) | - | [Details](admin\_permission\_by\_admin\_id.md) |
| admins/admin\_token\_item\_by\_token.sql | 1.00 | 0.08 | Medium (μ ± σ) | - | [Details](admin\_token\_item\_by\_token.md) |
| admins/admin\_token\_remove\_by\_admin\_id.sql | 1.00 | 0.06 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](admin\_token\_remove\_by\_admin\_id.md) |
| admins/admin\_update.sql | 1.00 | 0.07 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](admin\_update.md) |
| bad\_passwords/bad\_password\_item.sql | 1.00 | 0.07 | Medium (μ ± σ) | - | [Details](bad\_password\_item.md) |
| email\_queue\_recipients/email\_queue\_recipient\_add.sql | 1.00 | 0.49 | Medium (μ ± σ) | FullTableScan | [Details](email\_queue\_recipient\_add.md) |
| email\_queue\_recipients/email\_queue\_recipient\_delete\_sent\_older.sql | 0.81 | 0.09 | Medium (μ ± σ) | FullTableScan | [Details](email\_queue\_recipient\_delete\_sent\_older.md) |
| email\_queue\_recipients/email\_queue\_recipient\_list\_by\_email\_queue\_ids.sql | 0.35 | 0.11 | Medium (μ ± σ) | - | [Details](email\_queue\_recipient\_list\_by\_email\_queue\_ids.md) |
| email\_queues/email\_queue\_delete\_by\_sent\_older.sql | 1.00 | 0.09 | Medium (μ ± σ) | - | [Details](email\_queue\_delete\_by\_sent\_older.md) |
| email\_queues/email\_queue\_list\_by\_sendable.sql | 0.70 | 0.14 | Medium (μ ± σ) | - | [Details](email\_queue\_list\_by\_sendable.md) |
| email\_queues/email\_queue\_update\_active.sql | 1.00 | 0.06 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](email\_queue\_update\_active.md) |
| email\_queues/email\_queue\_update\_attempts.sql | 1.00 | 0.07 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](email\_queue\_update\_attempts.md) |
| email\_queues/email\_queue\_update\_sent.sql | 1.00 | 0.06 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](email\_queue\_update\_sent.md) |
| throttles/throttle\_add.sql | 1.00 | 0.54 | Medium (μ ± σ) | FullTableScan | [Details](throttle\_add.md) |
| throttles/throttle\_item\_by\_key.sql | 7.65 | 0.11 | ⚠️⚠️Very High (> μ + 2σ) | - | [Details](throttle\_item\_by\_key.md) |
| throttles/throttle\_remove\_by\_key.sql | 1.00 | 0.07 | Medium (μ ± σ) | - | [Details](throttle\_remove\_by\_key.md) |
| throttles/throttle\_update.sql | 1.00 | 0.07 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](throttle\_update.md) |
| users/user\_delete.sql | 1.00 | 0.06 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](user\_delete.md) |
| users/user\_item.sql | 1.00 | 0.08 | Medium (μ ± σ) | - | [Details](user\_item.md) |
| users/user\_item\_by\_username.sql | 1.00 | 0.07 | Medium (μ ± σ) | - | [Details](user\_item\_by\_username.md) |
| users/user\_list\_by\_ids.sql | 1.00 | 0.07 | Medium (μ ± σ) | - | [Details](user\_list\_by\_ids.md) |
| users/user\_update.sql | 1.00 | 0.07 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](user\_update.md) |
| verification\_codes/verification\_code\_by\_uuid.sql | 1.00 | 0.07 | Medium (μ ± σ) | - | [Details](verification\_code\_by\_uuid.md) |
| verification\_codes/verification\_code\_verified.sql | 1.00 | 0.08 | Medium (μ ± σ) | ImplicitTypeConversion | [Details](verification\_code\_verified.md) |

## Queries with Optimizer Impact

| SQL File | Base Access | Optimized Access | Cost Impact | Base Issues | Plan Changes |
|:----------|:------------|:----------------|:------------|:------------|:-------------|
| admins/admin\_item\_by\_email.sql | ALL, 1000 rows, 100.0% | Unknown | -99.0% | FullTableScan | - |

## Statistics

- Total queries analyzed: 32
- Average query cost: 1.11
- Standard deviation: 1.19

