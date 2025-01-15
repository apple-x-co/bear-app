# SQL Analysis Summary

## Query Analysis List
| SQL File | Cost | Level | Issues | Report |
|----------|------|-------|---------|---------|
| admin\_delete\_list.sql | 0.71 | Low (< μ) | - | [Details](admin_delete_list.md) |
| admin\_email\_by\_admin\_id.sql | 1.10 | Medium (μ ± σ) | - | [Details](admin_email_by_admin_id.md) |
| admin\_item.sql | 1.00 | Medium (μ ± σ) | - | [Details](admin_item.md) |
| admin\_item\_by\_username.sql | 1.00 | Medium (μ ± σ) | - | [Details](admin_item_by_username.md) |
| admin\_permission\_by\_admin\_id.sql | 1.10 | Medium (μ ± σ) | - | [Details](admin_permission_by_admin_id.md) |
| email\_list.sql | 1.10 | Medium (μ ± σ) | FullTableScan | [Details](email_list.md) |
| email\_recipient\_list.sql | 1.10 | Medium (μ ± σ) | - | [Details](email_recipient_list.md) |
| test\_item.sql | 1.10 | Medium (μ ± σ) | FullTableScan | [Details](test_item.md) |
| test\_list.sql | 1.10 | Medium (μ ± σ) | FullTableScan | [Details](test_list.md) |
| throttle\_item\_by\_key.sql | 1.20 | High (μ + σ to μ + 2σ) | FullTableScan | [Details](throttle_item_by_key.md) |

## Project Statistics
- Total SQLs analyzed: 10
- Average query cost: 1.05
- Standard deviation: 0.13