# Customer Views (Role 1)

Create one view per controller action. **No business logic in views** — only display data passed from controllers.

| View file | Controller | PDF feature |
|-----------|------------|-------------|
| `auth/register.php` | AuthController | Register (name, email, phone, password) |
| `auth/login.php` | AuthController | Login / logout |
| `profile/edit.php` | ProfileController | Update info, password, profile picture |
| `addresses/index.php` | AddressController | CRUD saved addresses, set default |
| `restaurants/browse.php` | RestaurantController | Browse, search, filter |
| `restaurants/detail.php` | RestaurantController | Detail + menu by category |
| `cart/view.php` | CartController | Session cart, discounts applied |
| `checkout/index.php` | CheckoutController | Address, payment, summary |
| `checkout/confirmation.php` | CheckoutController | Order ID, estimated delivery |
| `orders/history.php` | OrderController | Order history, re-order, cancel |
| `orders/track.php` | OrderController | Track page + AJAX status badge |
| `reviews/form.php` | ReviewController | Rating 1–5 + comment |
| `reviews/list.php` | ReviewController | Own past reviews |
| `favourites/index.php` | FavouritesController | Favourites list |
| `complaints/submit.php` | ComplaintController | Submit complaint |

AJAX client script: `assets/js/customer/order_tracking.js` → `api/customer/order_status.php`
