
# Spotify API - Laravel REST API

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Spotify API

A complete RESTful API built with Laravel 11 for managing Spotify artist data with full CRUD operations, search, filtering, and automatic documentation.

## 🚀 Features

- Complete REST API for artists
- Automatic API documentation with Scramble
- MySQL database with migrations
- Advanced search and filtering
- Pagination
- Input validation
- Error handling
- PHPUnit tests
- PHP 8.2+ with attributes

## 📋 Requirements

- PHP 8.2+
- Composer 2.5+
- MySQL 5.7+
- Git

## ⚡ Installation

### 1. Clone the repository
```bash
git clone https://github.com/your-username/spotify-api.git
cd spotify-api
```
### 2. Install dependencies
```bash
composer install
```
### 3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```
### 4. Configure database
Edit the .env file:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spotify_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
```
### 5. Create database
```sql
CREATE DATABASE spotify_api;
```
### 6. Run migrations and seeders
```bash
php artisan migrate --seed
```
### 7. Install frontend dependencies (optional)
```bash
npm install
```
### 8. Start the development server
```bash
# Backend only
php artisan serve

# Frontend with hot reload (optional)
npm run dev
```
The application will be available at: http://localhost:8000

🧪 Testing
Run Tests
```bash
# Run all tests
php artisan test
```
## Test Structure
```text
tests/
├── Unit/
│   ├── ArtistTest.php
│   └── ExampleTest.php
└── Feature/
    ├── ArtistControllerTest.php
    └── ExampleTest.php
```
## 📚 API Documentation
### Interactive Documentation
Access the automatic API documentation at:
http://localhost:8000/docs/api

### 📚 API Endpoints

#### 🎵 Artists Endpoints

| Method | Endpoint                          | Description                  | Parameters                                                  |
|--------|----------------------------------|------------------------------|-------------------------------------------------------------|
| GET    | /api/artists                     | List all artists             | search, genre, popular, page, per_page                     |
| POST   | /api/artists                     | Create a new artist          | spotify_id, name, genres, popularity, followers, image_url, spotify_url |
| GET    | /api/artists/{id}                | Get specific artist          | -                                                           |
| PUT    | /api/artists/{id}                | Update artist                | name, genres, popularity, followers, image_url, spotify_url |
| DELETE | /api/artists/{id}                | Delete artist                | -                                                           |
| GET    | /api/artists/popularity/range    | Filter by popularity range   | min, max                                                    |

#### Query Parameters

| Parameter | Type    | Description                    | Example             |
|-----------|---------|--------------------------------|------------------- |
| search    | string  | Search by artist name          | ?search=pitbull    |
| genre     | string  | Filter by genre               | ?genre=pop         |
| popular   | boolean | Order by popularity            | ?popular=true      |
| page      | integer | Page number                    | ?page=2            |
| per_page  | integer | Items per page                 | ?per_page=20       |
| min       | integer | Minimum popularity (0-100)     | ?min=80            |
| max       | integer | Maximum popularity (0-100)     | ?max=95            |

### 💡 Usage Examples
Create an Artist
```bash
curl -X POST http://localhost:8000/api/artists \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "spotify_id": "0TnOYISbd1XYRBk9myaseg",
    "name": "Pitbull",
    "genres": ["pop", "latin", "dance"],
    "popularity": 85,
    "followers": 15872345,
    "image_url": "https://i.scdn.co/image/ab6761610000e5ebd42a27db3286b58553da8858",
    "spotify_url": "https://open.spotify.com/artist/0TnOYISbd1XYRBk9myaseg"
  }'
```
List Artists with Filters
```bash
curl -X GET "http://localhost:8000/api/artists?search=pitbull&genre=pop&popular=true&page=1&per_page=10" \
  -H "Accept: application/json"
```
Get Artist by ID
```bash
curl -X GET http://localhost:8000/api/artists/1 \
  -H "Accept: application/json"
```
Update Artist
```bash
curl -X PUT http://localhost:8000/api/artists/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Pitbull Updated",
    "popularity": 90
  }'
```
Filter by Popularity Range
```bash
curl -X GET "http://localhost:8000/api/artists/popularity/range?min=80&max=95" \
  -H "Accept: application/json"
```
Delete Artist
```bash
curl -X DELETE http://localhost:8000/api/artists/1 \
  -H "Accept: application/json"
```
## 🏗️ Project Structure
```text
app/
├── Models/
│   └── Artist.php
├── Http/
│   └── Controllers/
│       └── ArtistController.php
├── Console/
│   └── Commands/
database/
├── factories/
│   └── ArtistFactory.php
├── migrations/
│   └── 2024_01_01_000000_create_artists_table.php
├── seeders/
│   └── DatabaseSeeder.php
routes/
└── api.php
tests/
├── Unit/
│   └── ArtistTest.php
└── Feature/
    └── ArtistControllerTest.php
config/
└── scramble.php
```
## 💾 Data Model

### Artist Model

| Field       | Type      | Description                     |
|------------ |---------- |-------------------------------- |
| id          | bigint    | Primary key                     |
| spotify_id  | string    | Unique Spotify identifier       |
| name        | string    | Artist name                     |
| genres      | json      | Music genres array              |
| popularity  | integer   | Popularity score (0-100)       |
| followers   | integer   | Number of followers             |
| image_url   | string    | Artist image URL                |
| spotify_url | string    | Spotify profile URL             |
| created_at  | timestamp | Creation timestamp              |
| updated_at  | timestamp | Update timestamp                |
