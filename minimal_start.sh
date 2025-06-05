#!/bin/bash

# Ultra-minimal startup script for Railway debugging
echo "=== MINIMAL DEBUG START ==="
echo "Current directory: $(pwd)"
echo "PHP version: $(php --version | head -n1)"

# Set port
PORT=${PORT:-8080}
echo "Using port: $PORT"

# List files to confirm we're in the right place
echo "Files in current directory:"
ls -la | head -10

# Test basic PHP functionality
echo "Testing basic PHP..."
php -r "echo 'Basic PHP test OK\n';"

# Check if simple_test.php exists and works
if [ -f "simple_test.php" ]; then
    echo "Testing simple_test.php..."
    php simple_test.php
else
    echo "simple_test.php not found"
fi

# Start the most basic PHP server possible
echo "Starting basic PHP server on 0.0.0.0:$PORT..."
echo "This will serve files from current directory"

# Use exec to replace shell process
exec php -S 0.0.0.0:$PORT -t . -d display_errors=1 -d log_errors=1
