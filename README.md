# applemusic-update

Fully responsive, based on Apple Music's stylesheet.

## TMP .env

```dotenv
# db
#DB_HOST="localhost"
DB_NAME="<DB_NAME>"
#DB_PORT="3306"
DB_USERNAME="<DB_USERNAME>"
DB_PWD="<DB_PWD>"

# apple
APPLE_AUTH_KEY="-----BEGIN PRIVATE KEY-----
...
-----END PRIVATE KEY-----"
APPLE_AUTH_KEY_FILE="<< or your key file name in server/keys/ >>.p8"
APPLE_TEAM_ID="<TEAM_ID>"
APPLE_KEY_ID="<KEY_ID>"

# defaults
ITUNES_DEFAULT_COUNTRY="fr"

# to be depreciated
STOREFRONT="fr"

# testing
#DEVELOPER_TOKEN="<< your testing token >>"
#MUSIC_KIT_TOKEN="<< your testing token >>"
```

---

## setup

Create a .env text file in the root directory with your DB logs :

```
DB_NAME="DB_name"
DB_USERNAME="DB_username"
DB_PORT="DB_PORT" # default 3306
DB_PWD="DB_password"
```

See [classes/DB.php](classes/DB.php) for more.

To setup an apple developer token, add :

```
EMAIL_USERNAME="xxxxx@xxxxx.xxx"
EMAIL_PWD="xxxxxx"
```

To setup email notifications, add :

```
DEVELOPER_TOKEN="eyJhbGciOiJFUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IldLVlIyUUJWQTYifQ.eyJpc3MiOiJHQzY1WDlQRDNRIiwiZXhwIjoxNjY1NzU3NDg2LCJpYXQiOjE2NDk5ODA0ODZ9.yh7Tq2wOOPipvChxdrBk3bTfCZCBi2IYqi5ytmhpO1lL_O_TgtMqVir4Jg3AMnHS6LU9aco5r1JqG4HwueBK1Q"
```

## cron task (every 30 mins)

Execute `crontab -e` and add this line (edit it as you wish) :

```
\*/30  * * * * php global.php refresh= nodisplay= >/dev/null 2>&1
```

*This command will check if new music is available every 30 minutes and for every users.*

---

## TODO

- [ ] total rebuild of the app
- [ ] MusicKit integration to automatically add new music in a selected playlist
- [ ] clean up code
- [ ] make a real API
- [ ] use the user token for API calls
- [ ] settings page
- [ ] fetch artists pics
- [ ] unit tests
