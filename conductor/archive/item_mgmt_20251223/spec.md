# Track Spec: Product and Material Master Data Management

## Overview
This track implements the core CRUD (Create, Read, Update, Delete) functionality for managing Product and Material master data. It provides the necessary API endpoints for maintaining the catalog of items manufactured and used within the ProCost Manager system, protected by the previously implemented RBAC system.

## Functional Requirements

### 1. Product Management API
- **List Products (`GET /api/products`):** Retrieve a paginated list of all products.
- **Show Product (`GET /api/products/{id}`):** Retrieve detailed information for a specific product.
- **Create Product (`POST /api/products`):** Add a new product to the system.
    - Fields: `product_code` (unique), `name`, `category`, `unit`, `standard_cost`, `standard_manufacturing_time`, `lead_time`, `safety_stock`, `reorder_point`.
- **Update Product (`PUT/PATCH /api/products/{id}`):** Update existing product details.
- **Delete Product (`DELETE /api/products/{id}`):** Remove a product (logical or physical delete based on system preference).

### 2. Material Management API
- **List Materials (`GET /api/materials`):** Retrieve a paginated list of all materials.
- **Show Material (`GET /api/materials/{id}`):** Retrieve detailed information for a specific material.
- **Create Material (`POST /api/materials`):** Add a new material.
    - Fields: `material_code` (unique), `name`, `category`, `unit`, `standard_price`, `lead_time`, `minimum_order_quantity`, `safety_stock`, `is_lot_managed`, `has_expiry_management`.
- **Update Material (`PUT/PATCH /api/materials/{id}`):** Update material details.
- **Delete Material (`DELETE /api/materials/{id}`):** Remove a material.

### 3. Authorization & Validation
- **Authentication:** All endpoints require a valid Sanctum token.
- **Role-Based Access:** 
    - **Read Access:** All authenticated roles (Admin, Planner, Leader, Accountant).
    - **Write Access (Create/Update/Delete):** Restricted to System Administrator, Production Manager, Cost Accountant, and Manufacturing Leader.
- **Validation:** Enforce unique constraints on `product_code` and `material_code`. Ensure numeric fields (costs, times, quantities) are validated correctly.

## Non-Functional Requirements
- **Consistency:** API responses should follow a consistent JSON structure.
- **Error Handling:** Return appropriate HTTP status codes (422 for validation errors, 403 for unauthorized access, 404 for missing items).

## Acceptance Criteria
- Full CRUD cycle for Products verified via feature tests.
- Full CRUD cycle for Materials verified via feature tests.
- Unique code constraints are enforced (cannot create two products with the same `product_code`).
- Unauthorized roles are blocked from performing write operations (403 Forbidden).

## Out of Scope
- Bulk import/export from Excel (to be handled in a dedicated track).
- Management UI/Forms (this track is API-only).
- Relationship management (e.g., BOM) beyond basic item definitions.
