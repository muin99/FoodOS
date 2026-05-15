# Restaurant Manager Views (Role 2)

| View file | Controller | PDF feature |
|-----------|------------|-------------|
| `auth/register.php` | AuthController | Register restaurant for approval |
| `auth/login.php` | AuthController | Login after approval |
| `restaurant/profile.php` | RestaurantController | Profile, logo, hours, radius, open toggle |
| `menu/categories.php` | MenuCategoryController | CRUD + reorder categories |
| `menu/items.php` | MenuItemController | CRUD menu items |
| `discounts/index.php` | DiscountController | Offers + campaign performance |
| `orders/dashboard.php` | OrderController | Active orders by status + AJAX incoming |
| `orders/history.php` | OrderController | Full order history |
| `reviews/index.php` | ReviewController | Reviews + public reply |
| `analytics/index.php` | AnalyticsController | Sales analytics |
| `complaints/index.php` | ComplaintController | Related complaints (read-only) |

AJAX: `assets/js/manager/incoming_orders.js` → `api/manager/incoming_orders.php`
