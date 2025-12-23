# Track Plan: User Authentication and Role-Based Access Control

## Phase 1: Sanctum and RBAC Foundation
- [x] Task: Install and configure Laravel Sanctum 6129d11
- [x] Task: Create and run migration for the `roles` table (including seed data) 3f103d4
- [x] Task: Create `CheckRole` middleware to enforce RBAC 6fefe19
- [x] Task: Write Unit Tests for `CheckRole` middleware logic e41b4ce
- [ ] Task: Conductor - User Manual Verification 'Sanctum and RBAC Foundation' (Protocol in workflow.md)

## Phase 2: Login and Logout API [checkpoint: 616ec7b]
- [x] Task: Write Feature Tests for Login API (successful login, failed credentials, role validation) e61d1fd
- [x] Task: Implement `AuthController` with `login` and `logout` methods 67081b4
- [x] Task: Register authentication routes in `routes/api.php` 67081b4
- [x] Task: Write Feature Tests for Logout API (token revocation) 67081b4
- [ ] Task: Conductor - User Manual Verification 'Login and Logout API' (Protocol in workflow.md)

## Phase 3: Route Protection and Seeding
- [x] Task: Apply `auth:sanctum` and `CheckRole` middleware to example protected routes 83c9cb9
- [~] Task: Create `UserSeeder` to populate test users for each role
- [ ] Task: Write Integration Tests to verify end-to-end flow (Login -> Protected Route Access)
- [ ] Task: Conductor - User Manual Verification 'Route Protection and Seeding' (Protocol in workflow.md)
