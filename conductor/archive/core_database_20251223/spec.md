# Track Spec: Core Database Schema and Models

## Overview
This track focuses on setting up the fundamental database architecture for ProCost Manager. It includes creating migrations and Eloquent models for the core entities: Users, Products, Materials, and Bill of Materials (BOM).

## Requirements

### 1. User Management
- **Table:** `users` (already exists in default Laravel, but may need expansion)
- **Fields:** employee_number, name, email, password, role_id, factory_id.
- **Model:** `User` with relationship to `Role` and `Factory`.

### 2. Product Management
- **Table:** `products`
- **Fields:** product_code (unique), name, category, unit, standard_cost, standard_manufacturing_time, lead_time, safety_stock, reorder_point.
- **Model:** `Product`.

### 3. Material Management
- **Table:** `materials`
- **Fields:** material_code (unique), name, category, unit, standard_price, lead_time, minimum_order_quantity, safety_stock, is_lot_managed, has_expiry_management.
- **Model:** `Material`.

### 4. Bill of Materials (BOM)
- **Table:** `boms`
- **Fields:** parent_id (morphs to Product/Material for semi-finished), child_id (morphs to Product/Material), quantity, yield_rate, valid_from, valid_until.
- **Model:** `Bom` with recursive relationship support.

## Success Criteria
- Migrations successfully run against MySQL.
- Eloquent models established with correct fillable attributes and relationships.
- Unit tests verify database schema integrity and model relationships.
