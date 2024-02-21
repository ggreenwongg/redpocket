## Installation

1. Add .env file
2. Run following command:
   ``` bash
      # Composer install
      composer install

      # Generate key
      php artisan key:generate
      
      # Migrate fresh
      php artisan migrate:fresh --seed 

      #RUN SWAGGER
      php artisan l5-swagger:generate

      #OPEN LOCAL 
      php artisan serve

      #add /api/documentation in ur local address
      http://127.0.0.1:8000/api/documentation


   ```
