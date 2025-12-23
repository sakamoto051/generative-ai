# Track Plan: Core Database Schema and Models

## Phase 1: User Management Foundation [checkpoint: 1900720]
- [x] Task: Create migration to add `employee_number`, `role`, and `factory_id` to `users` table 579dd6b
- [x] Task: Update `User` model with new fillable fields and relationships 2269acc
- [x] Task: Write Tests for `User` model attributes and basic validation 8b102fe
- [ ] Task: Conductor - User Manual Verification 'User Management Foundation' (Protocol in workflow.md)

## Phase 2: Product & Material Foundation [checkpoint: 07c2ac4]
- [x] Task: Write Tests for `Product` and `Material` models 5dd5fd0
- [x] Task: Create migration and model for `Product` 984f90a
- [x] Task: Create migration and model for `Material` ef3e4f4
- [ ] Task: Conductor - User Manual Verification 'Product & Material Foundation' (Protocol in workflow.md)

## Phase 3: BOM Structure
- [x] Task: Write Tests for `Bom` model relationships (recursive/polymorphic) d90a793
- [x] Task: Create migration and model for `Bom` 9ae411b
- [x] Task: Implement relationships between `Product`, `Material`, and `Bom` 3a9b76e
- [ ] Task: Conductor - User Manual Verification 'BOM Structure' (Protocol in workflow.md)
