# Track Plan: Bill of Materials (BOM) Management API

## Phase 1: BOM Relationship CRUD [checkpoint: bf88561]
- [x] Task: Write Feature Tests for BOM CRUD (Create, Update, Delete) 4b1ca81
- [x] Task: Create `StoreBomRequest` and `UpdateBomRequest` with basic validation 9cec904
- [x] Task: Implement `BomController` for standard CRUD operations 4c64185
- [x] Task: Register BOM routes in `routes/api.php` with role protection 4c64185
- [x] Task: Conductor - User Manual Verification 'BOM Relationship CRUD' (Protocol in workflow.md) bf88561

## Phase 2: Circular Reference & Integrity Validation [checkpoint: 4704f7a]
- [x] Task: Implement Circular Reference check logic (Recursive search) 375ba54
- [x] Task: Add Circular Reference validation to StoreBomRequest 39fd2a6
- [x] Task: Add Circular Reference validation to `UpdateBomRequest` 73af33c
- [x] Task: Write Unit Tests specifically for circular dependency detection 375ba54
- [x] Task: Write Unit Tests for polymorphic integrity (Parent must be Product) 43fdf8b
- [x] Task: Conductor - User Manual Verification 'Circular Reference & Integrity Validation' (Protocol in workflow.md) 4704f7a

## Phase 3: Recursive Tree Expansion
- [x] Task: Implement Recursive BOM Expansion logic in `BomService` or `Product` model b042af7
- [x] Task: Create `BomTreeResource` for standardized hierarchical output 7fbea78
- [x] Task: Implement `GET /api/products/{id}/bom-tree` endpoint 7fbea78
- [x] Task: Write Feature Tests for multi-level tree expansion and quantity calculation 7fbea78
- [ ] Task: Conductor - User Manual Verification 'Recursive Tree Expansion' (Protocol in workflow.md)
