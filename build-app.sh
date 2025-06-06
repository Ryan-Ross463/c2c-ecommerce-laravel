#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x build-app.sh`

# Exit the script if any command fails
set -e

# Check if npm is available
which npm || echo "npm not found in PATH"
which node || echo "node not found in PATH"

# Install Node.js dependencies
npm install

# Build assets using NPM
npm run build

echo "Assets built successfully!"
echo "Build phase complete - database-dependent operations will be handled at runtime."
