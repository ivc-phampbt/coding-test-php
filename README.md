## Get Started

This guide will walk you through the steps needed to get this project up and running on your local machine.

### Prerequisites

Before you begin, ensure you have the following installed:

- Docker
- Docker Compose

### Building the Docker Environment

Build and start the containers:

```
docker-compose up -d --build
```

### Installing Dependencies

```
docker-compose exec app sh
composer install
```

### Database Setup

Set up the database:

```
bin/cake migrations migrate
```

### Accessing the Application

The application should now be accessible at http://localhost:34251

## How to check

### Authentication
I created 2 accounts with the following information (Please run the migration again if you have run it before):
```
User1
email: pham.bqt@gmail.com
pass: P@ss1234tai

User2
email: pham.bqt2@gmail.com
pass: P@ss1234tai2
```

#### To get access token (login):
* About the Authentication feature, I use the [cakephp/authentication plugin](https://book.cakephp.org/authentication/2/en/index.html).
* About the Authorization feature, I use the [cakephp/authorization plugin](https://book.cakephp.org/authorization/2/en/index.html).

Ex: How to get access token from this system.
* Request:
```
POST http://localhost:34251/login
{
    "email": "pham.bqt@gmail.com",
    "password": "P@ss1234tai"
}
```
* Response:<br>

**- In case of successful login.**

```
HTTP Status Code: 200
```
```
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJteWFwcCIsInN1YiI6MSwiZXhwIjoxNzEwOTI2NjQ3fQ.Hw12qMLfvEf51wVgEVT1OupSgRRgLyrAUxJugcY36_yMST6NpoNfZ7N0-b7DYYPbaCWQ5evqJf0_ZB6abREgJ0dFYfGano3e7kikIOUB_90sIeo7LBlBJCXvVGLZdtDFw_Qxs_Qqd0rtXiDM3NQ04uFiovVkKZz0DxYVe6y2fA8"
}
```

**- In case of unsuccessful login.**
```
HTTP Status Code: 401
```
```
{
    "message": "The Username or Password is Incorrect"
}
```

The received access token will be used to authenticate requests that require authentication. 
Access token is generated by JWT(JSON Web Tokens). You can get more details at https://jwt.io/.


**Note:**
- The access token validity period is 60 minutes.

### Article Management
#### 1, Retrieve All Articles (GET).
|Title|endpoints|remark|
|---|---|---|
|Retrieve All Articles (GET)|/articles.json|Can only be used by all users. <br>Users can see information about articles and the total number of likes for each article.|
* Request:
```
GET http://localhost:34251/articles.json
```
* Response:<br>

**- In case data exists.**
```
HTTP Status Code: 200
```
```
{
  "articles": [
    {
      "total_likes": 1,
      "id": 1,
      "user_id": 1,
      "title": "title article 113 update",
      "body": "body article 1",
      "created_at": "2024-03-20T03:41:22+00:00",
      "updated_at": "2024-03-20T08:25:17+00:00"
    },
    {
      "total_likes": 1,
      "id": 2,
      "user_id": 1,
      "title": "title article 3 update",
      "body": "body article 2",
      "created_at": "2024-03-20T03:41:22+00:00",
      "updated_at": "2024-03-20T06:14:48+00:00"
    },
    .
    .
    .
   {
      "total_likes": 0,
      "id": 6,
      "user_id": 1,
      "title": "title article 7",
      "body": "body article 7",
      "created_at": "2024-03-20T08:59:19+00:00",
      "updated_at": "2024-03-20T09:01:20+00:00"
    }
  ]
}
```

**- In case data does not exist.**
```
HTTP Status Code: 200
```
```
{
    "articles": []
}
```

#### 2, Retrieve a Single Article (GET)
|Title|endpoints|remark|
|---|---|---|
|Retrieve a Single Article (GET)|/articles/{id}.json|Can only be used by all users. <br>Users can see article information and the article's total number of likes.|

In this example, I retrieve data from the article table with id = 4.
- Request:
```
GET http://localhost:34251/articles/4.json
```
- Response:<br>

**- In case data exists.**
```
HTTP Status Code: 200
```
```
{
  "article": {
    "total_likes": 2,
    "id": 4,
    "user_id": 1,
    "title": "title article 7",
    "body": "body article 7",
    "created_at": "2024-03-20T06:29:18+00:00",
    "updated_at": "2024-03-20T09:01:04+00:00"
  }
}
```

**- In case data does not exist.**
```
HTTP Status Code: 404
```
```
{
    "message": "Not Found",
    "url": "/articles/8.json",
    "code": 404
}
```


#### 3, Create an Article (POST)
|Title|endpoints|remark|
|---|---|---|
|Create an Article (POST)|/articles.json|Can only be used by authenticated users.|

* Request:
```
POST http://localhost:34251/articles.json
Content-Type: application/json
Authorization: <access token>
{
  "title": "title article 6",
  "body" : "body article 6"
}
```
* Response:

**- In case there are no validation errors and the save is successful.**
```
HTTP Status Code: 200
```
```
{
  "message": "The article has been saved.",
  "article": {
    "title": "title article 6",
    "body": "body article 6",
    "user_id": 1,
    "created_at": "2024-03-20T08:59:19+00:00",
    "updated_at": "2024-03-20T08:59:19+00:00",
    "id": 6
  }
}
```

**- In case of validation check error.**
```
HTTP Status Code: 200
```
```
{
    "message": "The article could not be saved. Please, try again.",
    "errors": {
        "title": {
            "_required": "This field is required"
        }
    }
}
```

**- In case the access token is not authenticated or has expired.**
```
HTTP Status Code: 401
```
```
{
    "message": "Authentication is required to continue",
    "url": "/articles.json",
    "code": 401
}
```


**Note:**
In this function, we also implemented the below validation checks:
* Validate the requirements of the request parameters.
* Validate the max length of the request parameters.

#### 4, Update an Article (PUT)
|Title|endpoints|remark|
|---|---|---|
|Update an Article (PUT)|/articles/{id}.json|Can only be used by authenticated article writer users.|

In this example, I update data for the title and body columns for an article with id = 6.
* Request:
```
PUT http://localhost:34251/articles/6.json
Content-Type: application/json
Authorization: <access token>
{
  "title": "title article 7",
  "body": "body article 7"
}
```
* Response:

**- In case there are no validation errors and the save is successful.**
```
HTTP Status Code: 200
```
```
{
  "message": "The article has been saved.",
  "article": {
    "id": 6,
    "user_id": 1,
    "title": "title article 7",
    "body": "body article 7",
    "created_at": "2024-03-20T08:59:19+00:00",
    "updated_at": "2024-03-20T09:01:20+00:00"
  }
}
```

**- In case the data does not exist.**
```
HTTP Status Code: 404
```
```
{
    "message": "Not Found",
    "url": "/articles/8.json",
    "code": 404
}
```

**- In case of validation check error.**
```
HTTP Status Code: 200
```
```
{
    "message": "The article could not be saved. Please, try again.",
    "errors": {
        "title": {
            "_empty": "This field cannot be left empty"
        }
    }
}
```

**- In case the access token is not authenticated or has expired.**
```
HTTP Status Code: 401
```
```
{
    "message": "Authentication is required to continue",
    "url": "/articles/6.json",
    "code": 401
}
```

**- In case there is no update permission.**
```
HTTP Status Code: 403
```
```
{
    "message": "Permission deined",
    "url": "/articles/6.json",
    "code": 403
}
```

**Note:**
In this function, we also implemented the below validation checks:
* Validate the requirements of the request parameters.
* Validate the max length of the request parameters.
* Checking the ID existing in DB before updating.

#### 5, Delete an Article (DELETE)
|Title|endpoints|remark|
|---|---|---|
|Delete an Article (DELETE)|/articles/{id}.json|Can only be used by authenticated article writer users.|

In this example, I delete an article with id is 6.
* Request:
```
http://localhost:34251/articles/6.json
Authorization: <access token>
```

* Response:

**- In case the deletion is successful.**
```
HTTP Status Code: 200
```
```
{
  "message": "The article has been deleted."
}
```

**- In case the data does not exist.**
```
HTTP Status Code: 404
```
```
{
    "message": "Not Found",
    "url": "/articles/8.json",
    "code": 404
}
```

**- In case the access token is not authenticated or has expired.**
```
HTTP Status Code: 401
```
```
{
    "message": "Authentication is required to continue",
    "url": "/articles/6.json",
    "code": 401
}
```

**- In case there is no update permission.**
```
HTTP Status Code: 403
```
```
{
    "message": "Permission deined",
    "url": "/articles/6.json",
    "code": 403
}
```
**Note:**
In this function, we also implemented the below validation check:
* Checking the ID existing in DB before deleting.

### Like Feature
#### Additional Table for the Like Feature
table name: **likes**
|column|type
|---|---|
|id|integer|
|user_id|integer|
|article_id|integer|
|created_at|datetime|
|updated_at|datetime|

#### Like Restful API Endpoints
|Title|endpoints|remark|
|---|---|---|
|Like an article (POST)|/articles/{article_id}/likes.json|Authenticated users can like all articles, including their own.<br>Authenticated users can like an article only once.<br>Authenticated users can’t cancel like|
|See like count on an article(GET)|/articles/{article_id}/likes.json|All users can see like count on an article.|

#### 1, Like an article (POST).
In this example, I like an article with id is 2.
* Request:
```
POST http://localhost:34251/articles/2/likes.json
Authorization: <access token>
```

* Response:

**- In case [like] is successful.**
```
HTTP Status Code: 200
```
```
{
  "message": "Like success!!!"
}
```

**- In case of [like] many times.**
```
HTTP Status Code: 200
```
```
{
    "message": "You have liked this article!"
}
```

**- In case the access token is not authenticated or has expired.**
```
HTTP Status Code: 401
```
```
{
    "message": "Authentication is required to continue",
    "url": "/articles/10/likes.json",
    "code": 401
}
```

**Note:**
In this function, we also implemented the below validation check:
* Checking the ID existing in DB before updating.

#### 2, See like count on an article(GET).
- Request:
```
GET http://localhost:34251/articles/2/likes.json
```
- Response:
```
HTTP Status Code: 200
```
```
{
  "total_likes": 2
}
```
