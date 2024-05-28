# Project Name

This is a Symfony project implementing a basic web application with user registration, login, and category management using the API Platform and JWT authentication.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Routes](#routes)
- [Entities](#entities)
- [Forms](#forms)
- [API](#api)
- [Authentication](#authentication)
- [Testing](#testing)
- [API Documentation](#api-documentation)

## Requirements

- PHP >= 8.2
- Composer
- Symfony CLI (optional but recommended)

## Installation

1. Clone the repository:

    ```sh
    git clone https://github.com/your-repo/project-name.git
    cd project-name
    ```

2. Install dependencies:

    ```sh
    composer install
    ```

3. Create the `.env.local` file and configure your database connection:

    ```sh
    cp .env .env.local
    # Update DATABASE_URL in .env.local
    ```

4. Generate SSL keys for JWT authentication:

    ```sh
    mkdir -p config/jwt
    openssl genrsa -out config/jwt/private.pem -aes256 4096
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
    ```

5. Add the JWT passphrase to your `.env` file:

    ```env
    JWT_PASSPHRASE=your-passphrase
    ```

6. Create the database and run migrations:

    ```sh
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```

7. Start the development server:

    ```sh
    symfony server:start
    ```

## Usage

- Access the home page at `http://localhost:8000/`
- Register a new user at `http://localhost:8000/register`
- Login at `http://localhost:8000/login`

## Project Structure

- **src/Controller**: Contains the controllers for handling HTTP requests.
- **src/Entity**: Contains the entity classes representing the data model.
- **src/Form**: Contains form classes for handling form submissions.
- **src/Repository**: Contains repository classes for querying the database.
- **templates**: Contains the Twig templates for rendering HTML views.

## Routes

- **HomeController**
  - `GET /`: Home page

- **RegistrationController**
  - `GET|POST /register`: User registration

- **SecurityController**
  - `GET|POST /login`: User login
  - `GET /logout`: User logout

## Entities

- **User**
  - Represents a user in the system.

- **Category**
  - Represents a category for posts.

- **Post**
  - Represents a blog post.

## Forms

- **RegistrationFormType**
  - Form for user registration.

## API

This project uses the API Platform to expose the following resources:

- **Category**
  - `GET /categories/{id}`: Get a category by ID.
  - `GET /categories`: Get the collection of categories.
  - `POST /categories`: Create a new category (admin only).
  - `PUT /categories/{id}`: Update a category (admin only).
  - `DELETE /categories/{id}`: Delete a category (admin only).
  - `PATCH /categories/{id}`: Partially update a category (admin only).

- **Post**
  - `GET /posts/{id}`: Get a post by ID.
  - `GET /posts`: Get the collection of posts.
  - `POST /posts`: Create a new post (logged-in users).
  - `PUT /posts/{id}`: Update a post (logged-in users).
  - `DELETE /posts/{id}`: Delete a post (logged-in users).
  - `PATCH /posts/{id}`: Partially update a post (logged-in users).

- **User**
  - `GET /users/{id}`: Get a user by ID (current user).
  - `GET /users`: Get the collection of users (logged-in users).
  - `POST /users`: Create a new user.
  - `PUT /users/{id}`: Update a user (current user).
  - `DELETE /users/{id}`: Delete a user (current user).
  - `PATCH /users/{id}`: Partially update a user (current user).

## Authentication

This project uses JWT authentication to secure the API endpoints.

### Obtaining a Token

1. **Create a New Request in Postman**:
  - **Method**: POST
  - **URL**: `http://localhost:8000/api/login_check`
  - **Headers**:
    - `Content-Type`: `application/json`
  - **Body** (raw, JSON):

    ```json
    {
      "email": "your_email",
      "password": "your_password"
    }
    ```

2. **Send the Request**: Click "Send" and you should receive a response containing a JWT token.

### Using the Token

1. **Create a New Request in Postman**:
  - **Method**: POST
  - **URL**: `http://localhost:8000/api/posts`
  - **Headers**:
    - `Content-Type`: `application/json`
    - `Authorization`: `Bearer YOUR_JWT_TOKEN`
  - **Body** (raw, JSON):

    ```json
    {
      "title": "My New Post",
      "content": "This is the content of my new post.",
      "slug": "my-new-post",
      "category": "/categories/1",
      "author": "/users/1"
    }
    ```

2. **Send the Request**: Click "Send".

## Testing

To run the tests, use the following command:

```sh
php bin/phpunit
```

## API Documentation

The API documentation is available at `http://localhost:8000/api/docs`.