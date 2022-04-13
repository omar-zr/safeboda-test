# Safeboda case study for software eng
This project is to prove my programming skills and abilities, knowing that some functions were applied at a little length in order to prove the principles of SOLID, As it was possible to be written in the short and fastest way.


### Setup the project

You can Create the database and the schema using following command or import it from safeboda.sql.

```php
mysql -u root -p

CREATE DATABASE safeboda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE safeboda.promo_codes (
   id INT NOT NULL AUTO_INCREMENT ,
    type ENUM('percentage','fixed') NOT NULL ,
     amount INT NOT NULL ,
      is_active TINYINT NOT NULL ,
       code VARCHAR(190) NOT NULL ,
        long DOUBLE NOT NULL ,
         lat DOUBLE NOT NULL ,
          radius INT NOT NULL ,
           name VARCHAR(190) NULL ,
            details TEXT NULL ,
             exp_date DATE NOT NULL ,
              PRIMARY KEY (id)) ENGINE = InnoDB;
quit
```


Copy `.env.example` to `.env` file and enter your database deatils.

```bash
cp .env.example .env
```



Install the project dependencies and start the PHP server:

```bash
composer install
```


## Run the Project
```bash
php -S localhost:8000 -t api
```

## Your APIs

| API               |    CRUD    |                                Description |
| :---------------- | :--------: | -----------------------------------------: |
| GET /promos        |  **READ**  |        Get all the Promo Codes              |
| GET /promo/{id}    |  **READ**  |         Get Single Promo Code               |
| POST /promo        | **CREATE** |          Create a Promo Code                |
| POST /promo        | **READ**   |  Check if Promo Code Applicable on the RIDE |
| PUT /promo/{id}    | **UPDATE** |            Update Single Promo Code         |
| DELETE /promo/{id} | **DELETE** |            Delete Single Promo Code         |

Also There is a Postman Collection provided with the code

