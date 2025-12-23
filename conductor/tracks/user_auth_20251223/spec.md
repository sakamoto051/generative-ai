# Track Spec: User Authentication and Role-Based Access Control

## Overview
This track implements a secure token-based authentication system using Laravel Sanctum and a simple Role-Based Access Control (RBAC) mechanism. This will serve as the security foundation for the ProCost Manager system, allowing for different levels of access for factory staff and administrators.

## Functional Requirements

### 1. Authentication
- **Token-based Auth:** Implement Laravel Sanctum for API-based authentication.
- **Login:** Users can authenticate using their `employee_number` (or `email`) and `password` to receive an API token.
- **Logout:** Authenticated users can revoke their current API token.
- **Session Management:** Secure handling of tokens for subsequent requests.

### 2. Role-Based Access Control (RBAC)
- **Simple Role System:** Utilize the `role_id` in the `users` table to determine user permissions.
- **Initial Roles Seeding:**
    - **System Administrator (ID: 1):** Full system access.
    - **Production Manager (ID: 2):** Planning and BOM management.
    - **Manufacturing Leader (ID: 3):** Execution and reporting.
    - **Cost Accountant (ID: 4):** Financial analysis and cost calculation.
- **Middleware:** Create a custom middleware (e.g., `CheckRole`) to restrict access to routes based on the user's role.

## Non-Functional Requirements
- **Security:** Passwords must be hashed using industry-standard algorithms (Bcrypt).
- **Performance:** Authentication checks should be fast and have minimal overhead.

## Acceptance Criteria
- A user can log in via an API endpoint and receive a valid Sanctum token.
- Protected routes return `401 Unauthorized` if no valid token is provided.
- Routes restricted by role return `403 Forbidden` if the authenticated user does not have the required role.
- Database seeder correctly populates the `roles` table and at least one test user for each role.

## Out of Scope
- Password reset via email (to be implemented in a future track).
- Frontend UI for login/user management (this track focuses on the backend/API).
