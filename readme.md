The MiniBank online system (MBS) is a small bank for personal and small business. The banking
system enables customer to access their bank account and perform their everyday banking needs.
MBS has many customers, and each customer has one or more accounts. The MBS use the Euro as
currency. The smallest unit of the currency is the single Euro.
When customer open an account, they provide an information pack which contain unique email id,
personal code, and password information for login into the banking system. After that, Customers
can view their account information, perform banking operations online, such as displaying the
balance of an account or transferring money. The customers can also interact with the MBS to
perform common transactions such as performing withdrawals, deposit and transferring money.

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)


Clone the repository

    git clone git@github.com:Al-Amin-Ceazer/mini-banking-system.git

Switch to the repo folder

    cd mini-banking-system

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate --seed
#### Run the tests
    vendor\bin\phpunit

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone git@github.com:Al-Amin-Ceazer/mini-banking-system.git
    cd mini-banking-system
    composer install
    cp .env.example .env
    php artisan key:generate
    
**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate
    php artisan serve
## Database seeding

Run the database seeder and you're done

    php artisan db:seed

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh
