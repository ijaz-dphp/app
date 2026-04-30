# Password Change Feature

## Overview
Users can now change their account password through a dedicated interface accessible from the navbar.

## Access
**Location**: Click "My Account" dropdown in navbar → "Change Password"

**Direct URL**: `https://belldial.softarts.co/change_password.php`

**Access Level**: All logged-in users (both Admin and Staff)

## Features

### 1. User Account Dropdown Menu
- Located in the navbar next to the user's name
- Click "My Account ▼" to reveal dropdown
- Options:
  - 🔒 Change Password
  - 🚪 Logout

### 2. Change Password Page
**Fields Required**:
1. **Current Password** - Verify user identity
2. **New Password** - Minimum 6 characters
3. **Confirm New Password** - Must match new password

**Validation**:
- All fields are required
- Current password must be correct
- New password must be at least 6 characters
- New password and confirmation must match
- Passwords are securely hashed using PHP's `password_hash()`

### 3. Security Features
✅ Current password verification  
✅ Password strength requirement (min 6 characters)  
✅ Password confirmation matching  
✅ Secure password hashing with bcrypt  
✅ Session-based authentication  
✅ Success/error message feedback  

## Files Created

1. **`change_password.php`**
   - User interface for password change
   - Shows success/error messages
   - Password requirements display
   - Clean, simple form

2. **`change_password_process.php`**
   - Backend processor
   - Validates all inputs
   - Verifies current password
   - Updates password in database
   - Redirects with status messages

3. **`includes/navbar.php`** (Updated)
   - Added "My Account" dropdown
   - Change Password link
   - Logout link
   - Click-outside-to-close functionality

## How to Use

### For Users:
1. **Access Change Password**
   - Click "My Account ▼" in top navbar
   - Select "🔒 Change Password"

2. **Change Your Password**
   - Enter your current password
   - Enter new password (min 6 characters)
   - Confirm new password
   - Click "Change Password"

3. **Confirmation**
   - Green success message: Password changed
   - Red error message: Check requirements

4. **Cancel**
   - Click "Cancel" to return to dashboard

### For Admins:
Admins can also:
- Change passwords via User Management page (when editing users)
- Reset any user's password (doesn't require current password)

## Error Messages

| Error | Meaning |
|-------|---------|
| "All fields are required" | One or more fields empty |
| "New password must be at least 6 characters long" | Password too short |
| "New password and confirmation do not match" | Passwords don't match |
| "Current password is incorrect" | Wrong current password |
| "User not found" | Session issue, re-login |

## Success Message
✅ "Password changed successfully!" - Password updated, can now login with new password

## Navigation Flow
```
Navbar → My Account ▼ → Change Password
  ↓
Change Password Form
  ↓
Submit → Validate → Update Database
  ↓
Success Message → Can now use new password
```

## Database Updates
- Updates `users.password` field
- Password is hashed before storage
- Old password immediately invalidated

## Best Practices

### For Users:
- Choose a strong password
- Don't share your password
- Change password periodically
- Remember your new password before logging out

### For Admins:
- Educate users about password security
- Monitor failed login attempts
- Use password reset via User Management if needed

## Technical Details

### Password Hashing
```php
password_hash($password, PASSWORD_DEFAULT)
```
- Uses bcrypt algorithm
- Automatic salt generation
- Industry-standard security

### Session Management
- Session-based authentication
- Password changes don't log user out
- New password effective immediately

### Validation Steps
1. Check all fields present
2. Verify minimum length
3. Confirm password match
4. Verify current password
5. Hash new password
6. Update database
7. Show success message

## Integration Points
- Works with existing user authentication system
- Compatible with both admin and staff accounts
- Integrates with User Management for admin password resets
- Uses existing Database class for connections

## URL Structure
- Change Password Page: `/change_password.php`
- Process Backend: `/change_password_process.php` (POST only)
- Redirect on Success: Back to `/change_password.php` with success message
- Redirect on Error: Back to `/change_password.php` with error message
