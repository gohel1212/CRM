<?php
// CRM Backend API - PHP with SQLite (No MySQL Required!)

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// SQLite Database (no MySQL required!)
$dbFile = 'crm_database.sqlite';

try {
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// Create tables if they don't exist
function createTables($pdo) {
    $tables = [
        'users' => "
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ",
        'contacts' => "
            CREATE TABLE IF NOT EXISTS contacts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT,
                phone TEXT,
                company TEXT,
                position TEXT,
                notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ",
        'customers' => "
            CREATE TABLE IF NOT EXISTS customers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT,
                phone TEXT,
                company TEXT,
                address TEXT,
                status TEXT DEFAULT 'potential',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        "
    ];
    
    foreach ($tables as $tableName => $sql) {
        try {
            $pdo->exec($sql);
        } catch(PDOException $e) {
            error_log("Error creating table $tableName: " . $e->getMessage());
        }
    }
    
    // Create trigger for updated_at
    $pdo->exec("
        CREATE TRIGGER IF NOT EXISTS update_contacts_timestamp 
        AFTER UPDATE ON contacts 
        BEGIN
            UPDATE contacts SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END
    ");
    
    $pdo->exec("
        CREATE TRIGGER IF NOT EXISTS update_customers_timestamp 
        AFTER UPDATE ON customers 
        BEGIN
            UPDATE customers SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END
    ");
}

// Initialize tables
createTables($pdo);

// JWT Secret
$jwt_secret = 'your-secret-key-change-in-production';

// Helper functions
function generateJWT($payload) {
    global $jwt_secret;
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode($payload);
    
    $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    
    $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, $jwt_secret, true);
    $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    return $base64Header . "." . $base64Payload . "." . $base64Signature;
}

function verifyJWT($token) {
    global $jwt_secret;
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;
    
    $header = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[0]));
    $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1]));
    $signature = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[2]));
    
    $expectedSignature = hash_hmac('sha256', $parts[0] . "." . $parts[1], $jwt_secret, true);
    
    if (!hash_equals($signature, $expectedSignature)) return false;
    
    return json_decode($payload, true);
}

function authenticateRequest() {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
    
    if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        http_response_code(401);
        echo json_encode(['error' => 'Access token required']);
        exit();
    }
    
    $token = $matches[1];
    $payload = verifyJWT($token);
    
    if (!$payload) {
        http_response_code(403);
        echo json_encode(['error' => 'Invalid token']);
        exit();
    }
    
    return $payload;
}

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api/', '', $path);

// Route handling
switch ($method) {
    case 'POST':
        switch ($path) {
            case 'register':
                $input = json_decode(file_get_contents('php://input'), true);
                
                if (!isset($input['name']) || !isset($input['email']) || !isset($input['password'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'All fields are required']);
                    exit();
                }
                
                // Check if user exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$input['email']]);
                
                if ($stmt->fetch()) {
                    http_response_code(400);
                    echo json_encode(['error' => 'User already exists']);
                    exit();
                }
                
                // Hash password and insert user
                $hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$input['name'], $input['email'], $hashedPassword]);
                
                http_response_code(201);
                echo json_encode(['message' => 'User registered successfully', 'userId' => $pdo->lastInsertId()]);
                break;
                
            case 'login':
                $input = json_decode(file_get_contents('php://input'), true);
                
                if (!isset($input['email']) || !isset($input['password'])) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Email and password are required']);
                    exit();
                }
                
                // Find user
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$input['email']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$user || !password_verify($input['password'], $user['password'])) {
                    http_response_code(401);
                    echo json_encode(['error' => 'Invalid credentials']);
                    exit();
                }
                
                // Generate JWT token
                $token = generateJWT(['userId' => $user['id'], 'email' => $user['email']]);
                
                echo json_encode([
                    'message' => 'Login successful',
                    'token' => $token,
                    'user' => [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email']
                    ]
                ]);
                break;
                
            case 'contacts':
                authenticateRequest();
                $input = json_decode(file_get_contents('php://input'), true);
                
                $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, company, position, notes) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $input['name'],
                    $input['email'] ?? null,
                    $input['phone'] ?? null,
                    $input['company'] ?? null,
                    $input['position'] ?? null,
                    $input['notes'] ?? null
                ]);
                
                http_response_code(201);
                echo json_encode(['message' => 'Contact created successfully', 'id' => $pdo->lastInsertId()]);
                break;
                
            case 'customers':
                authenticateRequest();
                $input = json_decode(file_get_contents('php://input'), true);
                
                $stmt = $pdo->prepare("INSERT INTO customers (name, email, phone, company, address, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $input['name'],
                    $input['email'] ?? null,
                    $input['phone'] ?? null,
                    $input['company'] ?? null,
                    $input['address'] ?? null,
                    $input['status'] ?? 'potential'
                ]);
                
                http_response_code(201);
                echo json_encode(['message' => 'Customer created successfully', 'id' => $pdo->lastInsertId()]);
                break;
        }
        break;
        
    case 'GET':
        switch ($path) {
            case 'dashboard':
                authenticateRequest();
                
                $stats = [];
                
                // Get total contacts
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM contacts");
                $stats['totalContacts'] = $stmt->fetch()['count'];
                
                // Get total customers
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM customers");
                $stats['totalCustomers'] = $stmt->fetch()['count'];
                
                // Get active customers
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM customers WHERE status = 'active'");
                $stats['activeCustomers'] = $stmt->fetch()['count'];
                
                $stats['potentialCustomers'] = $stats['totalCustomers'] - $stats['activeCustomers'];
                
                echo json_encode($stats);
                break;
                
            case 'contacts':
                authenticateRequest();
                
                $stmt = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
                $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode($contacts);
                break;
                
            case 'customers':
                authenticateRequest();
                
                $stmt = $pdo->query("SELECT * FROM customers ORDER BY created_at DESC");
                $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode($customers);
                break;
        }
        break;
        
    case 'PUT':
        $pathParts = explode('/', $path);
        $resource = $pathParts[0];
        $id = $pathParts[1] ?? null;
        
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID required for update']);
            exit();
        }
        
        authenticateRequest();
        $input = json_decode(file_get_contents('php://input'), true);
        
        switch ($resource) {
            case 'contacts':
                $stmt = $pdo->prepare("UPDATE contacts SET name = ?, email = ?, phone = ?, company = ?, position = ?, notes = ? WHERE id = ?");
                $stmt->execute([
                    $input['name'],
                    $input['email'] ?? null,
                    $input['phone'] ?? null,
                    $input['company'] ?? null,
                    $input['position'] ?? null,
                    $input['notes'] ?? null,
                    $id
                ]);
                
                echo json_encode(['message' => 'Contact updated successfully']);
                break;
                
            case 'customers':
                $stmt = $pdo->prepare("UPDATE customers SET name = ?, email = ?, phone = ?, company = ?, address = ?, status = ? WHERE id = ?");
                $stmt->execute([
                    $input['name'],
                    $input['email'] ?? null,
                    $input['phone'] ?? null,
                    $input['company'] ?? null,
                    $input['address'] ?? null,
                    $input['status'] ?? 'potential',
                    $id
                ]);
                
                echo json_encode(['message' => 'Customer updated successfully']);
                break;
        }
        break;
        
    case 'DELETE':
        $pathParts = explode('/', $path);
        $resource = $pathParts[0];
        $id = $pathParts[1] ?? null;
        
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID required for deletion']);
            exit();
        }
        
        authenticateRequest();
        
        switch ($resource) {
            case 'contacts':
                $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
                $stmt->execute([$id]);
                
                echo json_encode(['message' => 'Contact deleted successfully']);
                break;
                
            case 'customers':
                $stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
                $stmt->execute([$id]);
                
                echo json_encode(['message' => 'Customer deleted successfully']);
                break;
        }
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
        break;
}
?>
