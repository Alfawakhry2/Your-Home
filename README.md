## Tech Stack:
#php (version 8.2.4)
#MySQL 
#composer (Composer version 2.8.6)
#Laravel 12 
#Filament (v3.3.0)
#Sanctum (php artisan install:api)=> will install sanctum
#Paymob API 
#RESTful APIs (Tested with Postman)
#Api Documentaion => https://documenter.getpostman.com/view/29335427/2sB34eHgxP
#GitHub 

# 1. Clone the project
git clone https://github.com/ahmed/your-home.git
cd estate

# 2. Install dependencies
composer install
npm install && npm run build

# 3. Copy .env file and edit it
cp .env.example .env
# ثم يعدل بيانات الاتصال بقاعدة البيانات و البريد مثلاً

# 4. Generate app key
php artisan key:generate

# 5. Run migrations and seeders 
php artisan migrate --seed
##  Admin Login
Email: admin@admin.com  
Password: 123456789  

# 6. Link storage 
php artisan storage:link

# 7. Run the server
php artisan serve

# 8.Run the npm 
npm run dev


