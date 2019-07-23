
# applemusic-update
Fully responsive, based on Apple Music's stylesheet.

## setup
Create a .env text file in the root directory with your DB logs :  
```
DB_NAME="DB_name"
DB_USERNAME="DB_username"
DB_PWD="DB_password"
```

To setup email notifications, add :
```
EMAIL_USERNAME="xxxxx@xxxxx.xxx"
EMAIL_PWD="xxxxxx"
```

See [classes/DB.php](classes/DB.php) for more.

## cron task (every 30 mins)
Execute `crontab -e` and add this line (edit it as you wish) :
```
\*/30  * * * * php /c/wamp64/www/tests/utils/amu/global.php refresh= nodisplay= >/dev/null 2>&1
```
*This command will check if new music is available every 30 minutes and for every users.*
