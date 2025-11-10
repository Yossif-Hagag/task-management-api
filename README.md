# Authentication Sanctum and Task Management Api

## Prerequisites

> This Project Required Composer To Be Installed And PHP 8.2 Or Above

-   PHP 8.2 Or Above
-   [Composer](https://getcomposer.org/)

## Online API Docs

[Postman API Docs](https://documenter.getpostman.com/view/17493797/2sB3WtsJYj)

## Installation

### Clone The Project

```bash
git clone https://github.com/Yossif-Hagag/task-management-api.git
cd task-management-api
```

### Install Composer Dependencies

```bash
composer install

```

### Create .env Then Edit It

```bash
cp .env.example .env
```

### Generate Laravel Key

```bash
php artisan key:generate
```

### Migrate The DB

```bash
php artisan migrate
```

OR

### Migrate The DB With Seed

```bash
php artisan migrate --seed
```

### Link Storage

```bash
php artisan storage:link
```

### Run The Server

```bash
php artisan serve
```
