# Track Spec: Bill of Materials (BOM) Management API

## Overview
This track implements the API endpoints for managing the hierarchical relationships between products and materials. It allows users to define which components (products or materials) make up a finished or semi-finished product, ensuring data integrity through circular reference checks and providing recursive expansion capabilities for multi-level structures.

## Functional Requirements

### 1. BOM Relationship Management (CRUD)
- **Create BOM Entry (`POST /api/boms`):** Link a parent (Product) to a child (Product or Material).
    - Fields: `parent_id`, `parent_type`, `child_id`, `child_type`, `quantity`, `yield_rate`, `valid_from`, `valid_until`.
- **List/Update/Delete BOM Entry:** Standard operations to modify or remove specific relationships.

### 2. Recursive BOM Expansion
- **Expand BOM (`GET /api/products/{id}/bom-tree`):** Retrieve a fully expanded hierarchical tree of components.
    - **Depth Limit:** Support expansion up to 10 levels by default.
    - **Cumulative Quantity:** Calculate the total quantity needed for each child relative to the top-level parent.
    - **Structure:** Nested JSON representing the hierarchy.

### 3. Validation & Logic
- **Circular Reference Prevention:** Block any attempt to add a relationship that would result in a component containing itself at any level (e.g., A -> B -> A).
- **Polymorphic Integrity:** Ensure parents are always Products (as per domain logic, though the model supports both) and children can be either Products or Materials.

### 4. Authorization
- **Read Access:** All authenticated users.
- **Write Access:** Restricted to `System Administrator`, `Production Manager`, and `Manufacturing Leader`.

## Non-Functional Requirements
- **Performance:** Recursive queries should be optimized (using eager loading or specialized recursive CTEs if supported by the database driver).
- **Safety:** Prevent infinite recursion via depth limiting and pre-save validation.

## Acceptance Criteria
- A user can define a 3-level BOM (Product A -> Product B -> Material C).
- Retrieving the `bom-tree` for Product A correctly shows all levels with correct cumulative quantities.
- Attempting to make Material C a parent of Product A returns a validation error (422 Unprocessable Entity) due to circular dependency.
- Unauthorized roles (e.g., Cost Accountant) receive `403 Forbidden` on POST/PUT/DELETE.

## Out of Scope
- Visual Gantt chart rendering (this is API-only).
- Historical BOM versioning (this track focuses on the current active structure).
- Mass import of BOMs from Excel (dedicated track).
