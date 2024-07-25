# Laravel API Documentation

## Table of Contents

1. [Introduction](#introduction)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Environment Configuration](#environment-configuration)
5. [Running the Application](#running-the-application)
6. [API Endpoints](#api-endpoints)
    - [Initialize Payment](#initialize-payment)
    - [Verify Transaction](#verify-transaction)
    - [List Transactions](#list-transactions)
7. [Usage](#usage)
8. [License](#license)

## Introduction

This is a Laravel API project that handles payment transactions using Paystack. The API includes endpoints to initialize payments, verify transaction statuses, and list all transactions.

## Requirements

- PHP 8.0 or higher
- Composer
- Laravel 9.x
- MySQL or any supported database

## Installation

Follow these steps to set up the project locally:

1. **Clone the repository:**

    ```bash
    git clone https://github.com/Tope-fullstack/payment-backend.git
    cd payment-backend
    ```

2. **Install dependencies:**

    ```bash
    composer install
    ```

3. **Create a copy of the `.env` file:**

    ```bash
    cp .env.example .env
    ```

4. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

## Environment Configuration

Configure your `.env` file with the necessary settings. Below are the key configurations:

```dotenv
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

PAYSTACK_SECRET_KEY=your_paystack_secret_key
```

## Running the Application

Run the following command to start the local development server:

```bash
php artisan serve
```

Your application will be accessible at `http://localhost:8000`.

## API Endpoints

### Initialize Payment

- **URL:** `/api/initialize-payment`
- **Method:** `POST`
- **Parameters:**
  - `email` (string) - Customer's email address (required)
  - `amount` (integer) - Amount in kobo (required)
  - `callback_url` (string) - URL to redirect to after payment (optional)

- **Response:**

    ```json
    {
      "status": true,
      "message": "Authorization URL created",
      "data": {
        "authorization_url": "https://checkout.paystack.com/xxxxx",
        "access_code": "xxxxxx",
        "reference": "xxxxxx"
      }
    }
    ```

### Verify Transaction

- **URL:** `/api/verify-transaction`
- **Method:** `POST`
- **Parameters:**
  - `reference` (string) - Transaction reference (required)

- **Response:**

    ```json
    {
      "status": "success",
      "message": "Payment was successful"
    }
    ```

### List Transactions

- **URL:** `/api/transactions`
- **Method:** `GET`
- **Response:**

    ```json
    {
      "status": "success",
      "data": [
        {
          "reference": "xxxxxx",
          "amount": 5000,
          "status": "success",
          "created_at": "2023-07-24T12:34:56Z"
        },
        ...
      ]
    }
    ```

## Usage

### Initialize Payment

To initialize a payment, send a POST request to `/api/initialize-payment` with the required parameters. Use the `authorization_url` from the response to redirect the user for payment.

### Verify Transaction

After the user completes the payment, Paystack will redirect to the `callback_url` with a `reference` parameter. Send this reference to `/api/verify-transaction` to verify the transaction status.

### List Transactions

To retrieve a list of all transactions, send a GET request to `/api/transactions`.

### Example

#### Initialize Payment

```bash
curl -X POST http://localhost:8000/api/initialize-payment \
-H "Content-Type: application/json" \
-d '{
  "email": "customer@example.com",
  "amount": 5000
}'
```

#### Verify Transaction

```bash
curl -X POST http://localhost:8000/api/verify-transaction \
-H "Content-Type: application/json" \
-d '{
  "reference": "xxxxxx"
}'
```

#### List Transactions

```bash
curl -X GET http://localhost:8000/api/transactions \
-H "Content-Type: application/json"
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
