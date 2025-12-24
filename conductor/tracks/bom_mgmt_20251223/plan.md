# Track Plan: Bill of Materials (BOM) Management API

## Phase 1: BOM Relationship CRUD
- [x] Task: Write Feature Tests for BOM CRUD (Create, Update, Delete) 4b1ca81
- [x] Task: Create `StoreBomRequest` and `UpdateBomRequest` with basic validation 9cec904
- [ ] Task: Implement `BomController` for standard CRUD operations
- [ ] Task: Register BOM routes in `routes/api.php` with role protection
- [ ] Task: Conductor - User Manual Verification 'BOM Relationship CRUD' (Protocol in workflow.md)

## Phase 2: Circular Reference & Integrity Validation
- [ ] Task: Implement Circular Reference check logic (Recursive search)
- [ ] Task: Add Circular Reference validation to `StoreBomRequest`
- [ ] Task: Write Unit Tests specifically for circular dependency detection
- [ ] Task: Write Unit Tests for polymorphic integrity (Parent must be Product)
- [ ] Task: Conductor - User Manual Verification 'Circular Reference & Integrity Validation' (Protocol in workflow.md)

## Phase 3: Recursive Tree Expansion
- [ ] Task: Implement Recursive BOM Expansion logic in `BomService` or `Product` model
- [ ] Task: Create `BomTreeResource` for standardized hierarchical output
- [ ] Task: Implement `GET /api/products/{id}/bom-tree` endpoint
- [ ] Task: Write Feature Tests for multi-level tree expansion and quantity calculation
- [ ] Task: Conductor - User Manual Verification 'Recursive Tree Expansion' (Protocol in workflow.md)
