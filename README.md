#BAT Web Frontend

The BAT Web Frontend is a web based interface for providing the Binary Analysis Tool (BAT)
with files that need to be scanned, and provide access to the results that are produced. The
BAT Web Frontend is based on the Yii 2 framework, using the Yii 2 Advanced Application
Template. The Yii 2 Advanced Application Template is a skeleton application best for
developing complex Web applications with multiple tiers. The appliation includes three tiers:
front end, API, and console, each of which is a separate Yii application.

##DIRECTORY STRUCTURE

```
api
    assets/              contains application assets such as JavaScript and CSS
    config/              contains api configurations
    controllers/         contains api controller classes
    models/              contains api-specific model classes
    modules/             contains versioned models and controllers
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
common
    components/          contains shared components
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in the application tiers
    modules/             contains additional application modules
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
tests                    contains various tests for the advanced application
    codeception/         contains tests developed with Codeception PHP Testing Framework
```

##REQUIREMENTS

To run the BAT web frontend you will need the following software:

* A web server with support for PHP 5.4 or newer (tested with nginx/php-fpm)
* a database system (tested with MySQL 5.5 or newer and PostgreSQL 9.0 or newer)
* sufficient storage to accomodate the archive files you intend to scan. Currently only
locally mounted filesystems can be used for storage.


##INSTALLATION

Get the code by cloning or exporting the github repository into the desired location.

Install dependencies

1. Install composer ([https://getcomposer.org/]) if you don't yet have it available
2. Go into the root of the code repository and pull in the required packages

```
composer update
```

The exact command for running composer depends on the way you installed it. See the composer website for details.


##GETTING STARTED

After you have installed the application, you have to conduct the following steps to initialize
the installed application. You only need to do these once for all tiers.

1. Go into the application root.
2. Use the requirements.php script to determine whether all required PHP components are installed.
3. Run command `init` to initialize the application with a specific environment. Currently the 'dev' and 'prod'
environments are identical.
4. Create a new database and a user with sufficient access to create and alter tables and to perform CRUD operations.
5. Update the local configurations in `common/config`, `api/config`, `console/config`, and `frontend/config`.
For added security you may create an additional database user that only has CRUD rights, and use this in the
database configuration for the api and frontend tiers. Only the console tier needs elevated rights to alter the database.
6. Apply migrations (database schema changes) using the `yii` console command:

```
# list all available commands
yii help

# at installation:
yii migrate
yii migrate --migrationPath=@yii/rbac/migrations/
yii rbac/maintenance/init
```
These commands will set up the database schema, add tables for the RBAC module, and prime the RBAC system with the
required data. The `yii` command is used extensively in the maintenance of the application.

7. Set document roots of your Web server. For example, when using nginx with php-fpm:

```
# example nginx config, tested on vanilla Ubuntu 14.04LTS
server {
        listen <ip-address> default_server;
        server_name frontend.example.com;

        root /path/to/bat-web-frontend/frontend/web;
        index index.php;

        location / {
                try_files $uri $uri/ /index.php?$args;
        }

        error_page 404 /404.html;
        # redirect server error pages to the static page /50x.html
        error_page 500 502 503 504 /50x.html;
        location = /50x.html {
                root /path/to/bat-web-frontend/frontend/web;
        }

        # pass the PHP scripts to FastCGI server listening on socket
        location ~ \.php$ {
                try_files $uri =404;
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_pass unix:/var/run/php5-fpm.sock;
                fastcgi_index index.php;
                include fastcgi.conf;
        }

        # deny access to dot files
        location ~ /\. {
                deny all;
        }
}
server {
        listen <ip-address>;
        server_name api.example.com;

        root /path/to/bat-web-frontend/api/web;
        index index.php;

        location / {
                try_files $uri $uri/ /index.php?$args;
        }

        error_page 404 /404.html;
        # redirect server error pages to the static page /50x.html
        error_page 500 502 503 504 /50x.html;
        location = /50x.html {
                root /path/to/bat-web-frontend/api/web;
        }

        # pass the PHP scripts to FastCGI server listening on socket
        location ~ \.php$ {
                try_files $uri =404;
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_pass unix:/var/run/php5-fpm.sock;
                fastcgi_index index.php;
                include fastcgi.conf;
        }

        # deny access to dot files
        location ~ /\. {
                deny all;
        }
}
```

To access the application, access the URL that provides access to the frontend tier of the application.
You may register for an account here, or use the console to create accounts.

See the additional documentation for daily management of the application.


