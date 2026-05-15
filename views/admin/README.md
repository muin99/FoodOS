# Platform Admin Views (Role 4)

| View file | Controller | PDF feature |
|-----------|------------|-------------|
| `auth/login.php` | AuthController | Admin login |
| `dashboard/index.php` | DashboardController | Key metrics + analytics |
| `restaurants/index.php` | RestaurantController | Approve/reject/suspend/feature |
| `customers/index.php` | CustomerController | Search, deactivate/reactivate |
| `agents/index.php` | AgentController | Approve/reject/deactivate agents |
| `cuisines/index.php` | CuisineController | Platform cuisine categories |
| `orders/index.php` | OrderController | All orders + filters |
| `complaints/index.php` | ComplaintController | Resolve + admin note |
| `settings/index.php` | SettingsController | Commission, fees, delivery formula |
| `reports/monthly.php` | DashboardController | Monthly platform summary |

AJAX: `assets/js/admin/dashboard_metrics.js` → `api/admin/dashboard_metrics.php`
