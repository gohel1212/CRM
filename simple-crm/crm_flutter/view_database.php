<?php
// Simple Database Viewer for CRM
echo "<h1>CRM Database Viewer</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .section { margin: 30px 0; }
    .count { color: #666; font-style: italic; }
</style>";

// Connect to SQLite database
$dbFile = 'crm_database.sqlite';

try {
    $pdo = new PDO("sqlite:$dbFile");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connected to SQLite database: <strong>$dbFile</strong></p>";
    
    // Get table list
    $tables = ['users', 'contacts', 'customers'];
    
    foreach ($tables as $table) {
        echo "<div class='section'>";
        echo "<h2>📋 Table: $table</h2>";
        
        // Get count
        $countStmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $countStmt->fetch()['count'];
        echo "<p class='count'>Total records: $count</p>";
        
        if ($count > 0) {
            // Get all data
            $stmt = $pdo->query("SELECT * FROM $table ORDER BY id DESC LIMIT 10");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (!empty($data)) {
                echo "<table>";
                echo "<tr>";
                foreach (array_keys($data[0]) as $column) {
                    echo "<th>" . htmlspecialchars($column) . "</th>";
                }
                echo "</tr>";
                
                foreach ($data as $row) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        // Hide password field for security
                        if (strpos($value, '$2y$') === 0) {
                            echo "<td><em>***password***</em></td>";
                        } else {
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                    }
                    echo "</tr>";
                }
                echo "</table>";
                
                if ($count > 10) {
                    echo "<p><em>Showing last 10 records of $count total</em></p>";
                }
            }
        } else {
            echo "<p><em>No records found</em></p>";
        }
        
        echo "</div>";
    }
    
    // Database info
    echo "<div class='section'>";
    echo "<h2>📊 Database Information</h2>";
    echo "<p><strong>File:</strong> $dbFile</p>";
    echo "<p><strong>Size:</strong> " . number_format(filesize($dbFile)) . " bytes</p>";
    echo "<p><strong>Last Modified:</strong> " . date('Y-m-d H:i:s', filemtime($dbFile)) . "</p>";
    echo "</div>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<p><a href='#' onclick='location.reload()'>🔄 Refresh</a> | ";
echo "<a href='start_app.bat'>🚀 Start App</a></p>";
?>
