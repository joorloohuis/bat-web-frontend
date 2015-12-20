# export repo
# composer update
# run requirements.php
# create database, add user
# create [common|api|console|frontend]/config/[main|params]-local.php and edit

yii migrate
yii migrate --migrationPath=@yii/rbac/migrations/
yii rbac/maintenance/init

# set up webserver

# register an admin user on the command line (yii user/add)
# assign admin role (yii rbac/maintenance/assign)
