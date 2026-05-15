# Project 01 — Online Food Ordering System (FoodOS)

## Overview

A multi-sided food delivery platform connecting **customers**, **restaurant managers**, and **delivery agents** under a single **platform admin**. Customers browse restaurants and menus, place orders, and track deliveries in real time. Restaurant managers run operations through the platform. Delivery agents manage assignments and earnings. Platform admin oversees all parties and platform health.

## Roles

| Role | Responsibility |
|------|----------------|
| Customer | Browse, order, track, review |
| Restaurant Manager | Menu, orders, reviews, analytics |
| Delivery Agent | Accept deliveries, update status, track earnings |
| Platform Admin | User management, platform oversight, reports |

## Technical Requirements (from brief)

- **MVC**: separate Models, Views, Controllers — no business logic in view files
- **PHP** server-side, **MySQL** database
- All queries via **mysqli prepared statements** (no raw string insertion)
- **PHP sessions** with **role-based access control** on every protected page
- **At least one AJAX feature per role** (XMLHttpRequest → PHP API → JSON)
- Runnable on **XAMPP Apache** when cloned
- **Git**: feature branches + Pull Requests (do not push directly to main/master)
- Submit: (1) working codebase, (2) hardcopy report for your role’s features

## Separation of Concerns

- Each group member owns their role’s code and submission.
- Do **not** rely on other members for DB creation, data insertion, or sessions.
- Each member may only insert DB data needed to demonstrate **their own** work.

## Folder Structure

```
FoodOS/
├── config/              # DB connection, app constants
├── database/            # Shared schema (schema.sql)
├── includes/            # Bootstrap, auth (RBAC), helpers
├── models/              # Data access (mysqli prepared statements)
├── controllers/         # Business logic per role
│   ├── customer/
│   ├── manager/
│   ├── agent/
│   └── admin/
├── views/               # Presentation only (no business logic)
│   ├── layouts/
│   ├── customer/
│   ├── manager/
│   ├── agent/
│   └── admin/
├── api/                 # AJAX endpoints (return JSON)
│   ├── customer/
│   ├── manager/
│   ├── agent/
│   └── admin/
├── assets/              # css, js, uploads
└── index.php            # Front controller / entry
```

## Shared Database Tables

`users`, `restaurants`, `menu_categories`, `menu_items`, `discounts`, `orders`, `order_items`, `delivery_agents`, `delivery_assignments`, `reviews`, `saved_restaurants`, `delivery_addresses`, `complaints`, `platform_settings`

See `database/schema.sql`.

## AJAX Features (required per role)

| Role | AJAX feature (per PDF) |
|------|-------------------------|
| Customer | Real-time order status tracking (polling) |
| Restaurant Manager | Incoming orders without page refresh |
| Delivery Agent | Delivery status updates + new assignment notification while online |
| Platform Admin | Implement at least one AJAX feature for your admin UI (e.g. live dashboard metrics) |

## Submission Checklist

- [ ] Each role individually accessible with own login and dashboard
- [ ] RBAC prevents cross-role page access
- [ ] Shared schema implemented consistently
- [ ] At least one AJAX feature per role
- [ ] Server-side validation on all forms with descriptive errors
- [ ] Git: feature branches + PRs for major features
- [ ] Hardcopy report for your assigned role

## Member Assignment

| Folder | Owner |
|--------|--------|
| `controllers/customer/`, `views/customer/`, `api/customer/` | Role 1 — Customer |
| `controllers/manager/`, `views/manager/`, `api/manager/` | Role 2 — Restaurant Manager |
| `controllers/agent/`, `views/agent/`, `api/agent/` | Role 3 — Delivery Agent |
| `controllers/admin/`, `views/admin/`, `api/admin/` | Role 4 — Platform Admin |
| `models/`, `config/`, `includes/`, `database/` | Shared (each member must still be able to run standalone) |

Each PHP file contains a header comment describing what to implement per the project PDF.
