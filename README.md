<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

### Технологии

- БД Postgres в Docker
- Миграции для развертывания БД
- Сидеры для наполнения БД первоначальными данными
- Авторизация через ```Sanctum``` по токену для моб. приложений
- Реализация ```CRUD``` для сущностей: опции, товары
- Функционал загрузки изображений на сервер, использование ```symlink```
- Функционал связей ```ManyToMany``` для привязки изображений и опций к товарам 
- Выполнение задачи в ```Schedule``` по удалению просроченных токенов
- Использование ```Observers``` для отслеживания и чистки файлов при удалении товаров
- Использование ```Request``` классов для валидации запросов
- Использование ```Resource``` классов для отдачи данных
- Использование ```Scope``` фильтров для фильтрации запросов
- Использование ```DTO``` классов для передачи информации между слоями
- Использование стандартов отдачи ошибок через свои ```Exceptions``` классы
- Полное описание в ```Swagger``` всех эндпоинтов и схем

### Развертка

После скачивания репозитория требуется небольшая настройка

```
# Установка пакетов композера
$ composer install

# Создать свой .env файл на основе .env.example
$ mv -v .env.example .env
```

Теперь запустить docker для работы Postgres

```
# Запуск
docker-compose up -d
```

И последний этап

```
# Запуск сервера
$ php artisan serve

# Генерация Swagger документации
$ php artisan l5-swagger:generate

# Запуск миграций и сидеров
$ php artisan migrate --seed

# Создать симлинки
$ php artisan storage:link

# Запустить шедулер
$ php artisan schedule:work
```

### Рутовая учетка
- login: admin@test.com
- password: admin
