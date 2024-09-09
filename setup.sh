#!/bin/bash

# Function to print usage information
print_usage() {
    echo "Usage: $0 [--dev]"
    exit 1
}

# Function to check if composer is installed
preflight_check() {
    if ! command -v composer &> /dev/null; then
        echo "Error: composer is not installed. Please install composer and try again."
        exit 1
    fi
}

# Function to handle composer swapping
swap_composer() {
    local file_to_swap="$1"
    local temp_composer_json="composer.json.bak"

    # Check if the specified file exists
    if [ ! -f "$file_to_swap" ]; then
        echo "File $file_to_swap does not exist."
        exit 1
    fi

    # Step 1: Temporarily rename the existing composer.json file
    if [ -f "composer.json" ]; then
        mv composer.json "$temp_composer_json"
    fi

    # Step 2: Rename the specified file to composer.json
    mv "$file_to_swap" composer.json

    # Step 3: Remove composer.lock
    rm -f composer.lock

    # Step 4: Run composer install
    composer install

    # Step 5: Rename the swapped file back to its original name
    mv composer.json "$file_to_swap"

    # Step 6: Restore the original composer.json file
    if [ -f "$temp_composer_json" ]; then
        mv "$temp_composer_json" composer.json
    fi

    echo "Composer setup completed."
}

# Main script logic
preflight_check  # Ensure composer is installed before proceeding

if [ "$1" == "--dev" ]; then
    echo "Setting up development environment using composer-dev.json..."
    swap_composer "composer-dev.json"
elif [ -z "$1" ]; then
    echo "Setting up production environment using composer.json..."
    composer install
else
    print_usage
fi

## Set up action scheduler library. If this plugin doesn't use action scheduler, remove this.
echo "Installing action scheduler..."
git submodule add https://github.com/woocommerce/action-scheduler.git libraries/action-scheduler
git submodule update --init --recursive
