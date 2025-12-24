# Track Plan: Product and Material Master Data Management

## Phase 1: Product Management API [checkpoint: 692191d]
- [x] Task: Write Feature Tests for Product CRUD (List, Show, Create, Update, Delete) 169c61d
- [x] Task: Implement `ProductController` with all CRUD methods 439e3fc
- [x] Task: Create `StoreProductRequest` and `UpdateProductRequest` for validation 312f647
- [x] Task: Register product routes in `routes/api.php` with `CheckRole` protection 439e3fc
- [ ] Task: Conductor - User Manual Verification 'Product Management API' (Protocol in workflow.md)

## Phase 2: Material Management API
- [x] Task: Write Feature Tests for Material CRUD (List, Show, Create, Update, Delete) 2fa5d26
- [ ] Task: Implement `MaterialController` with all CRUD methods
- [ ] Task: Create `StoreMaterialRequest` and `UpdateMaterialRequest` for validation
- [ ] Task: Register material routes in `routes/api.php` with `CheckRole` protection
- [ ] Task: Conductor - User Manual Verification 'Material Management API' (Protocol in workflow.md)

## Phase 3: Authorization and Consistency
- [ ] Task: Write Integration Tests to verify RBAC across all new item endpoints
- [ ] Task: Ensure consistent JSON response format for all CRUD operations
- [ ] Task: Conductor - User Manual Verification 'Authorization and Consistency' (Protocol in workflow.md)
