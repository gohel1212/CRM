# CRM Backend - PHP Version (No Node.js Required!)

## Prerequisites
1. **PHP 7.4+** (most systems have this)
2. **MySQL** (same as before)
3. **Web Server** (Apache/Nginx or PHP built-in server)

## Setup Instructions

### Step 1: Create MySQL Database
```sql
mysql -u root -p
CREATE DATABASE crm_database;
exit;
```

### Step 2: Configure Database Connection
Edit `backend/api.php` and update the database credentials (around line 10):
```php
$host = 'localhost';
$username = 'root';
$password = 'your_mysql_password'; // Change this
$database = 'crm_database';
```

### Step 3: Start PHP Server

**Option A: Using PHP Built-in Server (Easiest)**
```bash
cd backend
php -S localhost:3000 api.php
```

**Option B: Using Apache/Nginx**
- Place `api.php` in your web server's document root
- Access via: `http://localhost/api.php`

### Step 4: Test the API
Open browser and go to: `http://localhost:3000/api/dashboard`
You should see an authentication error (which is expected).

## API Endpoints (Same as Node.js version)

### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login user

### Contacts
- `GET /api/contacts` - Get all contacts
- `POST /api/contacts` - Create new contact
- `PUT /api/contacts/:id` - Update contact
- `DELETE /api/contacts/:id` - Delete contact

### Customers
- `GET /api/customers` - Get all customers
- `POST /api/customers` - Create new customer
- `PUT /api/customers/:id` - Update customer
- `DELETE /api/customers/:id` - Delete customer

### Dashboard
- `GET /api/dashboard` - Get dashboard statistics

## Features
- ✅ JWT Authentication
- ✅ Password hashing with PHP's password_hash()
- ✅ CORS enabled
- ✅ Automatic table creation
- ✅ Same API as Node.js version
- ✅ No external dependencies

## Running the Complete Application

1. **Start PHP Backend:**
   ```bash
   cd backend
   php -S localhost:3000 api.php
   ```

2. **Start Flutter App:**
   ```bash
   cd ..
   flutter run -d chrome
   ```

That's it! No Node.js required. The PHP backend provides the exact same API as the Node.js version.
