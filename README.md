```shell script
docker run --rm --interactive --tty --volume $PWD:/app composer install --ignore-platform-reqs --no-scripts
```

Set phpstorm simultaneous debugging sessions for more than 2 
or just stop listening to PHP Debug connections to run php in few threads

To run the app:
```shell script
DOCKER_USER=$(id -u):$(id -g) docker-compose -f "docker/docker-compose.yml" up -d
docker exec php_async_php bash -c "php async_search.php"
```