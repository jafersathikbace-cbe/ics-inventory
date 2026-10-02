# ICS Inventory – Engineering Notes

## Inventory flow

1. A product is associated with raw or composite materials through its BOM.
2. Composite materials expand into their child materials when an order is evaluated.
3. The stock service calculates the total material requirement for the requested quantity.
4. Current stock is checked against the requirement and threshold.
5. The order and BOM snapshot are persisted in one transaction.
6. Stock is deducted while material rows are locked for update.
7. Each deduction creates a stock-log record for auditability.

## Roles

| Role | Intended access |
| --- | --- |
| Operator | View catalog, place orders, update order status, view reports |
| Admin | Inventory, BOM, product, order, report management |
| Super Admin | Full access including user management and ownership transfer |

## Reporting

Daily reports combine order activity and current material stock into a PDF. Reports are stored on the public disk and tracked in the `reports` table. Optional Telegram delivery is isolated behind `TelegramService`.

## Design considerations

- Inventory mutations are transactional.
- `lockForUpdate()` protects the final deduction against concurrent writes.
- Order items retain a BOM snapshot so historical orders remain understandable after a BOM changes.
- Permission middleware is applied at the route boundary.
