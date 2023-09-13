# Technical Test API Presences and PaySlips

This repository contains the Laravel API presences and payslips internal employee for the technical test.

## Features

- Presences to clock in
- Presences to clock out
- Get PaySlips by Year and month

## Setup
1. Rename .env.example to .env
2. Make connection database base on .env
3. Install the dependencies:
```bash
composer install
```
6. Navigate to the migrations and seed the database:
```bash
php artisan migrate --seed
```
7. Start the development server:
```bash
php artisan serve
```
