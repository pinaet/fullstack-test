```markdown
# Property Listing Application

This is a Laravel application that allows users to view and search for properties that are for sale and unsold. The application provides features such as pagination, search functionality, and sorting options.

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js
- MySQL
- Docker (optional)

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/your-username/property-listing-app.git
   ```

2. Navigate to the project directory:

   ```bash
   cd property-listing-app
   ```

3. Install the PHP dependencies using Composer:

   ```bash
   composer install
   ```

4. Install the JavaScript dependencies using npm:

   ```bash
   npm install
   ```

5. Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

6. Generate an application key:

   ```bash
   php artisan key:generate
   ```

7. Configure the database connection in the `.env` file:

   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

8. Run the database migrations:

   ```bash
   php artisan migrate
   ```

9. Seed the database with sample data (optional):

   ```bash
   php artisan db:seed
   ```

10. Start the development server:

    ```bash
    npm run dev
    ```

11. Access the application in your web browser at `http://localhost:8080`.

## Docker Usage (Optional)

If you prefer to use Docker, you can follow these steps:

1. Make sure you have Docker installed on your system.

2. Build and start the Docker containers:

   ```bash
   ./vendor/bin/sail up -d
   ```

3. Run the database migrations:

   ```bash
   ./vendor/bin/sail artisan migrate
   ```

4. Seed the database with sample data (optional):

   ```bash
   ./vendor/bin/sail artisan db:seed
   ```

5. Access the application in your web browser at `http://localhost:8080`.

## Usage

- The home page displays a list of 25 properties that are for sale and unsold.
- Use the search box to filter properties by title and location.
- Click on the sorting options to sort properties by price or date listed.
- Navigate through the paginated results using the provided links.
- Access properties based on selected provinces using dynamic routing (e.g., `/bangkok/`).

## Running Tests

To run the unit tests, execute the following command:

```bash
php artisan test
```

## API Endpoints

- `/api/properties`: Retrieves a list of properties with pagination and search functionality.

## Additional Information

For more details on the application's features, security best practices, performance optimization, and troubleshooting, please refer to the `Answers.to.technical.questions.md` file.

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvement, please open an issue or submit a pull request.

## License

This project is open-source and available under the [MIT License](https://opensource.org/licenses/MIT).
```
