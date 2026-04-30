# User Management System

## Overview
The User Management system allows administrators to create, edit, and delete user accounts for the medical lab system.

## Features

### 1. User Roles
- **Admin**: Full access including user management
- **Staff**: Can create reports and manage tests (no user management access)

### 2. User Management Page
**Location**: `manage_users.php`

**Access**: Only available to users with `admin` role

**Features**:
- View all users in a table
- See user details: ID, Username, Full Name, Role, Created Date
- Add new users
- Edit existing users
- Delete users (except yourself)

### 3. Add/Edit User Modal
**Fields**:
- **Username**: Unique login identifier
- **Full Name**: Display name
- **Password**: 
  - Required when adding new user
  - Optional when editing (leave empty to keep current password)
- **Role**: Select from Admin or Staff

### 4. Security Features
- Only admins can access user management
- Passwords are hashed using `password_hash()`
- Username uniqueness validation
- Cannot delete your own account
- Non-admin users redirected to dashboard if they try to access

## API Endpoints

### save_user.php
Handles all user CRUD operations:

**Actions**:
1. `add` - Create new user
2. `update` - Update existing user
3. `delete` - Remove user

**Request**: POST with FormData
**Response**: JSON

```json
{
  "success": true,
  "message": "User added successfully"
}
```

Or error:
```json
{
  "success": false,
  "error": "Username already exists"
}
```

## Navigation
User Management menu item appears in navbar only for admin users.

## Database Schema
```sql
users table:
- id (INTEGER, PRIMARY KEY)
- username (VARCHAR(50), UNIQUE, NOT NULL)
- password (VARCHAR(255), NOT NULL, hashed)
- full_name (VARCHAR(100), NOT NULL)
- role (VARCHAR(20), DEFAULT 'staff')
- created_at (DATETIME, DEFAULT CURRENT_TIMESTAMP)
```

## Default Admin Account
- Username: `admin`
- Role: `admin`

## Usage Instructions

### Adding a New User
1. Login as admin
2. Navigate to "User Management" from navbar
3. Click "+ Add New User"
4. Fill in all required fields
5. Select appropriate role
6. Click "Save User"

### Editing a User
1. Find user in the table
2. Click "Edit" button
3. Modify fields as needed
4. Leave password empty to keep current password
5. Click "Save User"

### Deleting a User
1. Find user in the table
2. Click "Delete" button (not available for your own account)
3. Confirm deletion

## Files Created
1. `manage_users.php` - Main user management interface
2. `save_user.php` - Backend API for user operations
3. `includes/navbar.php` - Updated navigation with User Management link
4. `USER_MANAGEMENT.md` - This documentation

## Permissions
- All logged-in users: Can view dashboard, create reports
- Admin only: Can manage users, access user management page
