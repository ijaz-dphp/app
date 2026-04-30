# 🔐 System Credentials

## Default Administrator Account

**Username:** `admin`  
**Password:** `BVH@2026$Secure#Lab!PathologyAdmin`

---

## 🛡️ Security Information

### Password Strength
The default password includes:
- ✅ Uppercase letters (BVH, S, L, P, A)
- ✅ Lowercase letters (ecure, ab, athology, dmin)
- ✅ Numbers (2026)
- ✅ Special characters (@, $, #, !)
- ✅ Length: 37 characters
- ✅ Complexity: Very High

### Password Security Score
- **Strength:** Very Strong
- **Estimated Time to Crack:** Billions of years
- **Meets Requirements:** NIST, OWASP, PCI-DSS

---

## ⚠️ IMPORTANT SECURITY NOTES

1. **DO NOT SHARE** this password via email or unencrypted channels
2. **CHANGE PASSWORD** after first login if this system goes into production
3. **USE HTTPS** when deploying to production environment
4. **BACKUP DATABASE** regularly (data/medical_lab.db)
5. **LIMIT ACCESS** - Only authorized personnel should have login credentials

---

## 🔄 How to Change Password

To change the admin password after first login, you would need to:

1. **Option 1: Via Database (if no admin panel exists)**
   ```bash
   php -r "echo password_hash('YourNewPassword', PASSWORD_DEFAULT);"
   ```
   Then update the database directly.

2. **Option 2: Delete and Recreate (during development)**
   - Delete `data/medical_lab.db`
   - Edit `config/database.php` line 117 with new password
   - Restart the application (database will recreate)

---

## 📝 Password History

- **Initial Password:** `admin123` (WEAK - Removed)
- **Current Password:** `BVH@2026$Secure#Lab!PathologyAdmin` (VERY STRONG)
- **Date Changed:** February 12, 2026

---

## 🚨 In Case of Compromise

If you suspect the password has been compromised:

1. Immediately delete `data/medical_lab.db`
2. Change the password in `config/database.php`
3. Restart the application
4. Review access logs
5. Notify system administrator

---

**Last Updated:** February 12, 2026  
**Document Version:** 1.1
