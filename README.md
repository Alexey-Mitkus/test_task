## О проекте

Проектное сообщество – сайт, состоящий из главной страницы, написанной на html+css+jQuery и личного кабинета, написаного с использованием Laravel и Vue js.

## API паспорта
Api состоит из 5 базовых запросов:
```
Route::get('/api/passports', 'PassportController@index');
Route::get('/api/passports/{passport}', 'PassportController@show');
Route::post('/api/passports', 'PassportController@store');
Route::put('/api/passports/{passport}', 'PassportController@update');
Route::delete('/api/passports/{passport}', 'PassportController@destroy');
```
Каждый запрос обращается к контроллеру **PassportController**

В самом контроллере базовые функции для отображения, создания, обновления и удаления модели.

### ВАЖНО!
В базе данных мы храним JSON данные, чтобы перевести js объект нужно использовать функцию **json_encode** при каждом сохранении или обновлении колонки json

И соответственно при выводе данных мы используем функцию **json_decode**

Это было сделано прямо в модели **Passport** в функции **getJsonAttribute**
```
public function getJsonAttribute($data){
    return json_decode($data);
}
```

#### Все запросы из vue js передаём с помощью axios с соответственным типом запроса get, post, put, delete и нужными данными

Данные для колонки json передаём без изменений js объектом

## Забираем себе проект
```git clone https://github.com/rushadaev/community.git```

## Устанавливаем зависимости Laravel
```composer install```
## Устанавливаем зависимости node
```npm install```
## Создаём файл конфигурации
```cp .env.example .env```
## Создаём ключ приложения
```php artisan key:generate```
## Создаём симлинк на хранилище
```php artisan storage:link```
## В файле .env укажи доступы к базе данных
```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=community
    DB_USERNAME=root
    DB_PASSWORD=password
```
## А также укажи почтовый сервер (ctrl+c,ctrl+v): 
```
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=64ede7e254ac68
    MAIL_PASSWORD=9c0f41732f9ea9
    MAIL_FROM_ADDRESS=example@gmail.com
    MAIL_FROM_NAME=Name
```
## Копируем структуру базы 
```php artisan migrate (миграция базы)```

## Запускаем сервер
```php artisan serve```

## Работаем!



