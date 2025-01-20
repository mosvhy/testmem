# Corat Coret Reconcile
- Project: PAYOK
- Vendors: 
    - INT (OWNER):
        - INT_1
        - INT_2
    - PL (AGG)
    - IB (PG)

- File excel:
    - INT_1/INTERNAL/PAYOK/paid_240101.csv
    - INT_2/INTERNAL/PAYOK/paid_240101.csv
    - PL/PROJECT/PAYOK/paid_240101.csv
    - IB/PROJECT/PAYOK/paid_240101.csv
- Reconcile created: PAYOK_PAID_240101
- Columns definition:
    - paid_int_1_240101:
        - vendor_reff_no
        - reff_no
    - paid_int_2_240101:
        - vendor_reff_no
        - reff_no
    - paid_pl_240101:
        - aggregate_no
        - vendor_reff_no
    - paid_ib_240101:
        - aggregate_no
        - vendor_reff_no
        - trx_no

# Mapping:

### Relation Between INT_1 ~ INT_2 ~ PL ~ IB
| INT_1  | INT_2  | PL  | IB    |
| - | - | - | - |
| INT_1.vendor_reff_no | INT_2.vendor_reff_no | PL.vendor_reff_no  | IB.vendor_reff_no |

### Relation Between PL ~ IB
| PL     | IB     |
| - | - |
| PL.vendor_reff_no  | IB.vendor_reff_no  |
| PL.aggregate_no | IB.aggregate_no |


### Columns to compare (PAID/CASH-IN/TRX):
- status: exact
- amount: tolerated
- fee: ignored
- total_amount: tolerated
- payment_method: mapped, exact
- created_at: exact
- paid_at: tolerated

## Complete reconciliation sample of each vendor (single row data)

### Reconcile Setting:
- Tolerance time: 10 minutes
- Tolerance amount: 1000

### Definition of cell's value
- 'NULL' mean column exists but doesn't have value or empty
- '-' mean column doesn't exists
- '<ins>underscored value</ins>' is the value that different with the other vendor (source data)

### Sample-01:

#### Output (Table Detail Transaction)
| Column Name        | INT_1               | INT_2               | PL             | IB                             | Shown Value                    |
| -                  | -                   | -                   | -              | -                              | -                              |
| **reff_no**        | TXRFF250101001      | TXRFF250101001      | -              | -                              | TXRFF250101001                 |
| **vendor_reff_no** | VRFF2501010001      | VRFF2501010001      | VRFF2501010001 | VRFF2501010001                 | VRFF2501010001                 |
| **aggregate_no**   | -                   | -                   | AGGID250101001 | AGGID250101001                 | AGGID250101001                 |
| **status**         | <ins>UNPAID</ins>   | PAID                | PAID           | PAID                           | <ins>UNPAID</ins>              |
| **amount**         | 1000                | 1100                | -              | 1000                           | 1000                           |
| **fee**            | 100                 | 0                   | -              | 0                              | 100                            |
| **total_amount**   | 1100                | 1100                | -              | <ins>3000</ins>                | <ins>1100</ins>                |
| **payment_method** | QRIS                | <ins>NULL</ins>     | -              | QRIS                           | <ins>QRIS</ins>                |
| **created_at**     | 2025-01-01 00:00:00 | 2025-01-01 00:01:00 | -              | <ins>2025-01-01 00:15:00</ins> | 2025-01-01 00:00:00            |
| **paid_at**        | <ins>NULL</ins>     | 2025-01-01 01:00:00 | -              | 2025-01-01 01:00:00            | <ins>2025-01-01 01:00:00</ins> |

#### Output (Table List Transaction):
| no | reff_no | vendor_reff_no | aggregate_no | status | amount | fee | total_amount | payment_method | created_at | paid_at |
| - | - | - | - | - | - | - | - | - | - | - |
| 1 | TXRFF250101001                 | VRFF2501010001                 | AGGID250101001                 | <ins>UNPAID</ins>              | 1000                           | 100                            | <ins>1100</ins>                | <ins>QRIS</ins>                | 2025-01-01 00:00:00            | <ins>2025-01-01 01:00:00</ins>  |

#### Differences:
- **INT_1.status** : different
- **INT_1.paid_at** : NULL while the others was exists
- **INT_2.payment_method** : NULL while the others was exists
- **IB.created_at** : Out of the tolerated time while the other are around the tolerated time
- **IB.total_amount** : Out of the tolerated amount while the other are around the tolerated amount

### Explanation
All columns of every vendors are merged into one and will be used for showing the output and also


