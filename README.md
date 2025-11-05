# News Aggregator API

Engineered a centralized news aggregation service using Laravel that integrates multiple external APIs (NewsAPI, The Guardian, The New York Times). The system normalizes disparate data into a consistent schema and exposes a unified REST API, enabling clients to perform efficient searches, filtering, and retrieval of articles from a single endpoint.

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [API Documentation](#api-documentation)
- [Testing](#testing)
## Features

- **Multi-source aggregation**: Fetches articles from NewsAPI, The Guardian, and The New York Times
- **Unified API**: Single endpoint for all news sources
- **Advanced filtering**: Search by keywords, date range, category, source, and author
- **Automatic updates**: Scheduled article updates every 10 minutes
- **Duplicate prevention**: Smart article deduplication
- **Comprehensive tests**: Feature and unit tests included
- **Automatic Documentation**: Automatic Documentation of api endpoints

## Tech Stack

- **Framework**: Laravel 10
- **PHP**: 8.1+
- **Database**: MySQL
- **Testing**: PHPUnit
- **APIs**: NewsAPI, The Guardian API, NY Times API

## Requirements

| Requirement           | Version                |
|-----------------------|------------------------|
| PHP                   | 8.1.x                  |
| Laravel               | 10.x.x                 |
| Composer              |                        |
| MySQL                 | 8.0                    |

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/doobie-droid/innoscripta-news-aggregator
cd innoscripta-news-aggregator
```

### 2. Install dependencies

```bash
composer install
```

### 3. Create environment files

```bash
cp .env.example .env
cp .env.example .env.testing
```

### 5. Configure database

Edit `.env.testing` file with your database credentials:

```env
DB_DATABASE=testing
```
### 6.Start Docker containers with laravel sail

```bash
sail up
```

### 7. Run migrations

```bash
sail artisan migrate
sail artisan migrate --env=testing
```

## API Documentation

### Base URL

```
http://localhost:8080/api
```

### Visit The Docs

```
http://localhost:8080/documentation
```

## Testing

### Run All Tests

```bash
sail artisan test
```