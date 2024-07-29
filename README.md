# Parameter_api
## Описание
___
    Спецификация
    Сущности:
    • Parameter
    Реализовано:
    HTTP API:
    • Просмотр списка параметров, к которым можно подгрузить изображения(/api/getUpLoadableParameters)
    • Просмотр уже загруженных изображений.(/api/getLoadableParameters)
    • Поиск по id и title.(/api/findByIdOrTitle/{key})
    • Возможность добавить одно или два изображения к параметру.(/api/addPictures)
    • Возможность заменить загруженные изображения на другие.(/api/updatePictures)
    • Возможность удалить изображения из параметра.(/api/deletePictures/{id})
    • API в котором можно получить все параметры к которым можно подгрузить картинки со
        списком подгруженных картинок в формате json. Список подгруженных имеет
        исходное имя, путь для просмотра картинок и отметку для понимания что есть icon, а что icon_gray.(/api/getCustomList)

    Laravel команды:
    • Команда по наполнению БД тестовыми данными (без картинок).

   
## Установка
___
### Docker
    Перед тем как начать, убедитесь, что у вас установлен Docker. Вы можете скачать 
    Docker с официального сайта 
[Docker](https://www.docker.com/get-started)


### Проверка установки Docker
___
    Для проверки, установлен ли Docker, выполните следующую команду в терминале:
```bash
    docker --version
```
    Если Docker установлен правильно, вы увидите сообщение с версией Docker, например:
    Docker version 20.10.7, build f0df350
___
### Запуск
___
    1. Клонируйте репозиторий 
    2. Соберите контейнеры Docker
```bash
    docker compose up --build
```
    3.Проверьте статус контейнеров

```bash
    docker ps
``` 
    Eсли контейнеры запущены правильно, вы увидите сообщение с информацией о контейнерах, например:
    mfo-php-1           mfo-php       "docker-php-entrypoi…"   php           13 hours ago   Up 13 hours   0.0.0.0:8000->8000/tcp, 9000/tcp
    mfo-postgres_db-1   postgres:16   "docker-entrypoint.s…"   postgres_db   13 hours ago   Up 13 hours   0.0.0.0:5432->5432/tcp

    4. Установите и обновите все зависимости composer (В контейнере PHP)
```bash
    docker compose exec php bash -c "composer update"
    
```
    5.Cоздайте файл .env 
```bash
touch .env 

```
    6. Заполните .env содержимым .env.example
```bash
cat .env.example > .env

```
    6. Выполните миграцию
```bash
    docker compose exec php bash -c "php artisan migrate"
```
    • Команда по наполнению БД тестовыми данными.
```bash
    docker compose exec php bash -c "php artisan db:seed --class=ParameterSeeder"
    
```
___
    Документация к провекту
[/api/documentation](http://localhost/api/documentation#/)
___
