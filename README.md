# applemusic-update
Fully responsive, based on Apple Music's stylesheet.

## setup
Create a .env text file in the root directory with your DB logs :  
DB_name:DB_username:DB_password  

See [classes/DB.php](classes/DB.php) for more.

## cron task (every 30 mins)
\*/30  * * * * php [global.php](global.php) refresh= nodisplay=

*This command will check if new music is available every 30 minutes and for every users.*
