# SQL Analysis Summary

## Query Analysis List
| SQL File | Cost | Level | Issues | Report |
|----------|------|-------|---------|---------|
| admin\_delete\_list.sql | 0.35 | Medium (μ ± σ) | - | [Details](admin_delete_list.md) |
| admin\_email\_by\_admin\_id.sql | 0.35 | Medium (μ ± σ) | - | [Details](admin_email_by_admin_id.md) |
| admin\_item.sql | 1.00 | Medium (μ ± σ) | - | [Details](admin_item.md) |
| admin\_item\_by\_email.sql | 0.00 | Medium (μ ± σ) | - | [Details](admin_item_by_email.md) |
| admin\_item\_by\_username.sql | 0.00 | Medium (μ ± σ) | - | [Details](admin_item_by_username.md) |
| admin\_permission\_by\_admin\_id.sql | 0.35 | Medium (μ ± σ) | - | [Details](admin_permission_by_admin_id.md) |
| admin\_token\_item\_by\_token.sql | 0.00 | Medium (μ ± σ) | - | [Details](admin_token_item_by_token.md) |
| bad\_password\_item.sql | 0.00 | Medium (μ ± σ) | - | [Details](bad_password_item.md) |
| email\_list.sql | 102.25 | High (μ + σ to μ + 2σ) | FullTableScan | [Details](email_list.md) |
| email\_recipient\_list.sql | 0.35 | Medium (μ ± σ) | - | [Details](email_recipient_list.md) |
| test\_item.sql | 101.25 | High (μ + σ to μ + 2σ) | FullTableScan | [Details](test_item.md) |
| test\_list.sql | 101.25 | High (μ + σ to μ + 2σ) | FullTableScan | [Details](test_list.md) |
| throttle\_item\_by\_key.sql | 0.35 | Medium (μ ± σ) | - | [Details](throttle_item_by_key.md) |
| verification\_code\_by\_uuid.sql | 0.00 | Medium (μ ± σ) | - | [Details](verification_code_by_uuid.md) |

## Project Statistics
- Total SQLs analyzed: 14
- Average query cost: 21.96
- Standard deviation: 41.58