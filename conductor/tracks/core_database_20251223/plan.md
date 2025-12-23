# Track Plan: Core Database Schema and Models

## Phase 1: User Management Foundation
- [ ] Task: Create migration to add `employee_number`, `role`, and `factory_id` to `users` table
- [ ] Task: Update `User` model with new fillable fields and relationships
- [ ] Task: Write Tests for `User` model attributes and basic validation
- [ ] Task: Conductor - User Manual Verification 'User Management Foundation' (Protocol in workflow.md)

## Phase 2: Product & Material Foundation
- [ ] Task: Write Tests for `Product` and `Material` models
- [ ] Task: Create migration and model for `Product`
- [ ] Task: Create migration and model for `Material`
- [ ] Task: Conductor - User Manual Verification 'Product & Material Foundation' (Protocol in workflow.md)

## Phase 3: BOM Structure
- [ ] Task: Write Tests for `Bom` model relationships (recursive/polymorphic)
- [ ] Task: Create migration and model for `Bom`
- [ ] Task: Implement relationships between `Product`, `Material`, and `Bom`
- [ ] Task: Conductor - User Manual Verification 'BOM Structure' (Protocol in workflow.md)
