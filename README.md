<div align="center">
  <h1>Natural Twenty BGC</h1>
</div>

## Requirements
* PHP ^8.2
* MySQL 8.0.33
* PHP Extensions based on the Laravel Deployment page
## Installation
Install PHP dependencies:
```bash
composer install
```
Setup configuration:
```bash
cp .env.example .env
```
Generate the application key:
```bash
php artisan key:generate
```
Run the migration and seed the database:
```bash
php artisan migrate --seed
```
Create a symlink to the storage:
```bash
php artisan storage:link
```