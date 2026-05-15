# Delivery Agent Views (Role 3)

| View file | Controller | PDF feature |
|-----------|------------|-------------|
| `auth/register.php` | AuthController | Register (vehicle type), await approval |
| `auth/login.php` | AuthController | Login after approval |
| `profile/edit.php` | ProfileController | Profile, vehicle, online/offline toggle |
| `assignments/available.php` | AssignmentController | Available ready orders |
| `assignments/active.php` | AssignmentController | Current delivery + status updates |
| `history/index.php` | HistoryController | Past deliveries + earnings per trip |
| `earnings/summary.php` | EarningsController | Today / week / month / all-time |
| `performance/index.php` | PerformanceController | Stats + complaints against agent |

AJAX:
- `assets/js/agent/delivery_status.js` → `api/agent/update_delivery_status.php`
- `assets/js/agent/assignment_notify.js` → `api/agent/new_assignment_notify.php`
