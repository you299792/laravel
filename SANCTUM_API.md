# Laravel Sanctum API Implementation

Laravel Sanctum has been successfully implemented in your application. This document describes the available API endpoints and how to use them.

## Overview

Sanctum provides:
- **Token-based authentication** for SPAs and mobile apps
- **Personal access tokens** for API access
- **Secure token generation** and revocation

## API Endpoints

### Authentication Routes (Public)

#### Register a new user
```
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Response (201):
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null,
    "created_at": "2026-02-18...",
    "updated_at": "2026-02-18..."
  },
  "token": "1|Xwz9Kp2Lm8vN5qR3sT6uV4wX7yZ0aB9cD2eF5gH8jK1l"
}
```

#### Login user
```
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}

Response (200):
{
  "user": { /* user object */ },
  "token": "1|Xwz9Kp2Lm8vN5qR3sT6uV4wX7yZ0aB9cD2eF5gH8jK1l"
}
```

### Protected Routes (Requires Authentication)

All protected routes require the token in the header:
```
Authorization: Bearer {token}
```

#### Get current authenticated user
```
GET /api/me
Authorization: Bearer {token}

Response (200):
{
  "user": { /* authenticated user object */ }
}
```

#### Get current user (alternative)
```
GET /api/user
Authorization: Bearer {token}

Response (200):
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  ...
}
```

#### Logout user
```
POST /api/logout
Authorization: Bearer {token}

Response (200):
{
  "message": "Logged out successfully"
}
```

### User Management Routes (Requires Authentication)

#### Get all users
```
GET /api/users
Authorization: Bearer {token}

Response (200):
[
  {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    ...
  }
]
```

#### Get specific user
```
GET /api/users/{id}
Authorization: Bearer {token}

Response (200):
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  ...
}
```

#### Create new user
```
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Jane Doe",
  "email": "jane@example.com"
}

Response (201):
{
  "id": 2,
  "name": "Jane Doe",
  "email": "jane@example.com",
  ...
}
```

#### Update user
```
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Jane Smith",
  "email": "jane.smith@example.com"
}

Response (200):
{
  "id": 2,
  "name": "Jane Smith",
  "email": "jane.smith@example.com",
  ...
}
```

#### Delete user
```
DELETE /api/users/{id}
Authorization: Bearer {token}

Response (200):
{
  "message": "User deleted successfully"
}
```

#### Verify user email
```
POST /api/users/{id}/verify
Authorization: Bearer {token}

Response (200):
{
  "message": "Email verified successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": "2026-02-18...",
    ...
  }
}
```

## Using Tokens

### How to get a token

1. **Register**: Call `/api/register` - you'll receive a token immediately
2. **Login**: Call `/api/login` with credentials - you'll receive a token
3. **Manual token creation**: Use `$user->createToken('token-name')->plainTextToken` in code

### How to use a token

Include it in the `Authorization` header of all API requests:

```
Authorization: Bearer YOUR_TOKEN_HERE
```

### Token Revocation

Call `/api/logout` to revoke the current token, or:

```php
// In code
$user->tokens()->delete(); // Delete all tokens
$user->currentAccessToken()->delete(); // Delete current token
```

## Configuration

The Sanctum configuration is located at `config/sanctum.php`. Key settings:

- **stateful**: Domains that should receive stateful authentication (for SPAs)
- **guard**: Authentication guard to use
- **expiration**: Token expiration time in minutes (null = never expires)
- **token_prefix**: Prefix for tokens (security feature)

## Testing the API

### Using cURL

```bash
# Register
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@example.com","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"john@example.com","password":"password123"}'

# Get authenticated user
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer YOUR_TOKEN"

# Logout
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Using Postman

1. Register/Login and copy the token
2. In Postman, go to the request's **Authorization** tab
3. Select **Bearer Token**
4. Paste your token in the **Token** field
5. The header will be automatically added

## Security Best Practices

1. **Always use HTTPS** in production
2. **Store tokens securely** on the client (secure HTTP-only cookies recommended for SPAs)
3. **Implement token rotation** for long-lived applications
4. **Monitor token usage** and revoke unused tokens
5. **Set appropriate token expiration** times
6. **Use CORS** appropriately to restrict token access
7. **Never log or expose tokens** in error messages

## Running Migrations

Make sure the personal_access_tokens table exists:

```bash
php artisan migrate
```

## What was implemented

1. ✅ Added `HasApiTokens` trait to the User model
2. ✅ Created `AuthController` with register, login, logout, and me endpoints
3. ✅ Updated API routes with proper authentication middleware
4. ✅ Personal access tokens table migration (already exists)
5. ✅ Token-based API security

## Next Steps

- Run `php artisan migrate` if you haven't already
- Test the endpoints using cURL, Postman, or your frontend application
- Implement token refresh logic if needed
- Add rate limiting to authentication endpoints
- Implement email verification flow
- Add ability management for fine-grained permissions
