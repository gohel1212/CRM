#!/bin/bash
echo "Starting CRM Flutter Application..."
echo

echo "Starting PHP Backend Server..."
ROOT_DIR="$(cd "$(dirname "$0")" && pwd)"
BACKEND_DIR="$ROOT_DIR/backend"
php -S 127.0.0.1:3000 "$BACKEND_DIR/api_sqlite.php" &
BACKEND_PID=$!

echo "Waiting 3 seconds for server to start..."
sleep 3

echo "Starting Flutter Application..."
cd "$ROOT_DIR" && flutter run -d chrome &
FLUTTER_PID=$!

echo
echo "Both servers are starting..."
echo "PHP Backend: http://127.0.0.1:3000"
echo "Flutter App: Will open in Chrome browser"
echo
echo "Press Ctrl+C to stop both servers..."

# Wait for user to stop
wait $BACKEND_PID $FLUTTER_PID
