<!-- Make project -->
D:\xampp\php\php.exe D:\xampp\php\composer.phar create-project laravel/laravel core    

<!-- Migrate the project -->
D:\xampp\php\php.exe artisan migrate

<!-- Run the project -->
D:\xampp\php\php.exe artisan serve

<!-- Make admin panel -->
D:\xampp\php\php.exe D:\xampp\php\composer.phar require filament/filament:"^3.2" -W
D:\xampp\php\php.exe artisan filament:install --panels

<!-- Createsuperuser -->
D:\xampp\php\php.exe artisan make:filament-user

<!-- Clear Panel -->
D:\xampp\php\php.exe artisan route:clear