# CRM Flutter Application

A complete Customer Relationship Management (CRM) application built with Flutter and MySQL backend.

## Features

- **Authentication System**: Login and registration with JWT tokens
- **Dashboard**: Overview of contacts and customers with statistics
- **Contact Management**: Add, edit, delete, and view contacts
- **Customer Management**: Add, edit, delete, and view customers with status tracking
- **Modern UI**: Beautiful and responsive Material Design interface
- **Real-time Data**: Live updates from MySQL database

## Screens

1. **Login Screen**: User authentication with email and password
2. **Register Screen**: New user registration
3. **Dashboard Screen**: Overview with statistics and quick actions
4. **Contact Screen**: Manage business contacts
5. **Customer Screen**: Manage customers with status filtering

## Tech Stack

### Frontend (Flutter)
- Flutter SDK
- Provider for state management
- Go Router for navigation
- HTTP for API calls
- Shared Preferences for local storage

### Backend (Node.js + MySQL)
- Express.js server
- MySQL database
- JWT authentication
- bcryptjs for password hashing
- CORS enabled

## Setup Instructions

### Backend Setup

1. **Install Node.js** (https://nodejs.org/)

2. **Install MySQL** (https://dev.mysql.com/downloads/)

3. **Create MySQL Database**:
   ```sql
   CREATE DATABASE crm_database;
   ```

4. **Navigate to backend directory**:
   ```bash
   cd backend
   ```

5. **Install dependencies**:
   ```bash
   npm install
   ```

6. **Update database credentials** in `server.js`:
   ```javascript
   const db = mysql.createConnection({
     host: 'localhost',
     user: 'root',
     password: 'your_mysql_password', // Change this
     database: 'crm_database'
   });
   ```

7. **Start the server**:
   ```bash
   npm start
   ```

   The server will run on http://localhost:3000

### Flutter Setup

1. **Install Flutter** (https://flutter.dev/docs/get-started/install)

2. **Navigate to project root**:
   ```bash
   cd crm_flutter
   ```

3. **Install dependencies**:
   ```bash
   flutter pub get
   ```

4. **Run the application**:
   ```bash
   flutter run
   ```

## API Endpoints

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

## Database Schema

### Users Table
```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Contacts Table
```sql
CREATE TABLE contacts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255),
  phone VARCHAR(20),
  company VARCHAR(255),
  position VARCHAR(255),
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Customers Table
```sql
CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255),
  phone VARCHAR(20),
  company VARCHAR(255),
  address TEXT,
  status ENUM('active', 'inactive', 'potential') DEFAULT 'potential',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Project Structure

```
crm_flutter/
├── lib/
│   ├── models/
│   │   ├── user.dart
│   │   ├── contact.dart
│   │   ├── customer.dart
│   │   └── dashboard_stats.dart
│   ├── providers/
│   │   └── auth_provider.dart
│   ├── screens/
│   │   ├── login_screen.dart
│   │   ├── register_screen.dart
│   │   ├── dashboard_screen.dart
│   │   ├── contact_screen.dart
│   │   └── customer_screen.dart
│   ├── services/
│   │   └── api_service.dart
│   └── main.dart
├── backend/
│   ├── server.js
│   ├── package.json
│   └── README.md
└── pubspec.yaml
```

## Usage

1. **Start the backend server** (make sure MySQL is running)
2. **Run the Flutter app**
3. **Register a new account** or login with existing credentials
4. **Navigate through the app** using the dashboard and bottom navigation
5. **Add contacts and customers** using the floating action buttons
6. **View statistics** on the dashboard

## Features in Detail

### Authentication
- Secure password hashing with bcryptjs
- JWT token-based authentication
- Persistent login sessions
- Form validation

### Contact Management
- Add contact with name, email, phone, company, position, and notes
- Edit existing contacts
- Delete contacts with confirmation
- Search and filter capabilities

### Customer Management
- Add customers with status tracking (Active, Inactive, Potential)
- Filter customers by status
- Complete customer information including address
- Status-based color coding

### Dashboard
- Real-time statistics
- Quick action buttons
- Refresh functionality
- Welcome message with user name

## Security Features

- Password hashing with bcryptjs
- JWT token authentication
- Input validation
- SQL injection prevention
- CORS configuration

## Future Enhancements

- Email notifications
- File uploads for contacts/customers
- Advanced search and filtering
- Data export functionality
- Mobile push notifications
- Offline support
- Multi-language support

## Troubleshooting

### Common Issues

1. **MySQL Connection Error**: Ensure MySQL is running and credentials are correct
2. **Port Already in Use**: Change the port in server.js if 3000 is occupied
3. **Flutter Build Error**: Run `flutter clean` and `flutter pub get`
4. **API Connection Error**: Ensure backend server is running before starting Flutter app

### Development Tips

- Use `flutter run` with hot reload for development
- Check browser console for API errors
- Use MySQL Workbench for database management
- Enable Developer Mode on Windows for symlink support

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License.