# Run steps
1. Install docker.
2. Run:
```bash
$ docker-compose up -d
```
> If you are in windows change the ${PWD} for %cd%
3. If you need execute the gulp in development to check your changes in live execute the follow command:
```bash
$ docker-compose run npm-cli gulp
```
4. Change your hosts file (in linux /etc/hosts) and add the line
```
127.0.0.1 your-awesome-site.com
```

##Stop the project
To stop the project you can execute the follow command
```bash
$ docker-compose stop
```
Also if you like destroy the instance you can execute the follow command
```bash
$ docker-compose down
```

##Dump the data.
To create a backup to our database execute the follow command:
```bash
$ docker-compose exec dbmysql sh -c 'export MYSQL_PWD="$MYSQL_ROOT_PASSWORD";exec mysqldump -uroot site' > ./scripts/database.sql
```

## Using an existent mysqldb
Execute the follow commands to create a new database an user:
```bash
$ docker-compose exec dbmysql sh -c 'export MYSQL_PWD="$MYSQL_ROOT_PASSWORD"; mysql'
```
after execute the follow comands:
```sql
mysql> CREATE DATABASE IF NOT EXISTS YOUR_DATABASE;
mysql> GRANT ALL ON `YOUR_DATABASE`.* TO 'YOUR_USER'@'%' IDENTIFIED BY 'YOUR_PASSWORD';
mysql> FLUSH PRIVILEGES ;
```
