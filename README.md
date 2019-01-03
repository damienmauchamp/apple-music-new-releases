# applemusic-update

Create a .env text file in the root directory with your DB logs :  
DB_name:DB_username:DB_password  

See [classes/DB.php](classes/DB.php) for more.

## cron task
\*/30  * * * * php global.php refresh= nodisplay=
