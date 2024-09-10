## Technologies
* Backend: Laravel10
* Database: MySQL 
## Contact
* **Email**: hau.nguyenbk8786@gmail.com

## SETUP DOCKER
* Create `.env` add `APP_SERVICE=laravel_api`, `DB_HOST=<ip_address>`
* Run docker desktop
```javascript
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs

alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'

sail up -d
sail composer install
sail npm i
sail artisan migrate
sail artisan passport:install
sail artisan db:seed
```

## API documentation
### Auth
* Sign up user into the system
    ```http
    POST /api/v1/auth/register
    ```

    | Parameter | Type | Description |
    | :--- | :--- | :--- |
    | `name` | `string` | **Required** |
    | `email` | `string email unique` | **Required, email, unique, ends_with:hcmut.edu.vn** |
    | `password` | `string` | **Required** |
    | `password_confirmation` | `string` | **Required, same:password** |

    ```javascript
    code 200
    {
        "id": integer,
        "name": string,
        "email": string,
        "role": {
            "name": string
        },
        "avatar": string,
        "created_at": timestamp,
        "updated_at": timestamp
    }
    ```

* Log in user into the system
    ```http
    POST /api/v1/auth/login
    ```

    | Parameter | Type | Description |
    | :--- | :--- | :--- |
    | `email` | `string email` | **Required, email, ends_with:hcmut.edu.vn** |
    | `password` | `string` | **Required** |

    ```javascript
    code 200
    {
        "access_token": string,
        "user": {
            "id": integer,
            "name": string,
            "email": string,
            "avatar": string
            "role": integer
        }
    }

    code 401 
    {
        "error": "Invalid username or Password"
    }
    ```

* Get current user logged
    ```http
    POST /api/v1/auth/me
    ```

    ```javascript
    code 200
    {
        "id": integer,
        "name": string,
        "email": string,
        "role": {
            "name": string
        },
        "avatar": string,
        "created_at": timestamp,
        "updated_at": timestamp
    }

    code 401 
    {
        "message": "Unauthorized",
        "status": 401
    }
    ```

* Log out current logged (invoke token)
    ```http
    DELETE /api/v1/auth/logout
    ```

    ```javascript
    code 204
    {

    }
    ```



* Change an avatar
    ```http
    POST /api/v1/auth/avatar
    ```

    | Parameter | Type | Description |
    | :--- | :--- | :--- |
    | `img_file` | `file,png,jpg,jpeg,gif ` | **Required, file, png,jpg,jpeg,gif** |

    ```javascript
    code 200
    {
        "message": "upload avatar successfully"
    }
    ```

### Teacher

* Create a new TeacherToSubject (subject registration)
    ```http
    POST /api/v1/teacher/store.teacher_to_subject
    ```

    | Parameter | Type | Description |
    | :--- | :--- | :--- |
    | `subject_id` | `required ` | **Required** |
    | `semester` | `required ` | **Required** |
    | `year` | `required ` | **Required** |

    ```javascript
    code 200
    {
        "id": integer,
        "subject_id": integer,
        "semester": string,
        "year": string,
        "teacher_id": integer,
        "updated_at": timestamp,
        "created_at": timestamp,
    }

    code 401
    {
        "message": "Unauthorized",
        "status": 401
    }

    code 403
    {
        "message": "Forbidden",
        "status": 403
    }
    ```

* Get all subjects
    ```http
    POST /api/v1/teacher/getAllSubjects
    ```

    ```javascript
    code 200
    {
        "data": [
            {
                "id": integer,
                "name": string,
                "code": string
            },
            {
                "id": integer,
                "name": string,
                "code": string
            }
        ]
    }

    code 401
    {
        "message": "Unauthorized",
        "status": 401
    }

    code 403
    {
        "message": "Forbidden",
        "status": 403
    }
    ```

* Set mark for report
    ```http
    PUT /api/v1/teacher/report.setMark/{reportId}
    ```

    ```javascript
    code 200
    {
        "message": "update mark successfully"
    }

    code 401
    {
        "message": "Unauthorized",
        "status": 401
    }

    code 403
    {
        "message": "Forbidden",
        "status": 403
    }
    ```

### Student 

* Create the student information
    ```http
    POST /api/v1/student/studentInfo.store
    ```

    | Parameter | Type | Description |
    | :--- | :--- | :--- |
    | `last_name` | `string` | **Required** |
    | `first_name` | `string ` | **Required** |
    | `student_code` | `string unique` | **Required, unique** |
    | `department` | `string ` | **Required** |
    | `faculty` | `string ` | **Required** |
    | `address` | `string ` | **Required** |
    | `phone` | `string ` | **Required** |

    ```javascript
    code 200
    {       
        "id": integer,
        "last_name": string,
        "first_name": string,
        "student_code": string,
        "department": string,
        "faculty": string,
        "address": string,
        "phone": string,
        "updated_at": timestamp,
        "created_at": timestamp
    }

    code 401
    {
        "message": "Unauthorized",
        "status": 401
    }

    code 403
    {
        "message": "Forbidden",
        "status": 403
    }
    ``` 

* Update the student information
    ```http
    PUT /api/v1/student/studentInfo.update
    ```

    | Parameter | Type | Description |
    | :--- | :--- | :--- |
    | `last_name` | `string` | **Required** |
    | `first_name` | `string ` | **Required** |
    | `address` | `string ` | **Required** |
    | `phone` | `string ` | **Required** |

    ```javascript
    code 200
    {       
        "id": integer,
        "last_name": string,
        "first_name": string,
        "student_code": string,
        "department": string,
        "faculty": string,
        "address": string,
        "phone": string,
        "updated_at": timestamp,
        "created_at": timestamp
    }

    code 401
    {
        "message": "Unauthorized",
        "status": 401
    }

    code 403
    {
        "message": "Forbidden",
        "status": 403
    }
    ``` 

* Upload the report
    ```http
    POST /api/v1/student/report.store
    ```

    | Parameter | Type | Description |
    | :--- | :--- | :--- |
    | `teacher_to_subject_id` | `integer` | **Required** |
    | `title` | `string ` | **Required** |
    | `file` | `file, pdf,doc,docx` | **Required, pdf,doc,docx** |

    ```javascript
    code 200
    {       
       "message": "upload report successfully"
    }

    code 401
    {
        "message": "Unauthorized",
        "status": 401
    }

    code 403
    {
        "message": "Forbidden",
        "status": 403
    }
    ``` 

### Report: only student and teacher

*  View the report
    ```http
    GET /api/v1/report/view/{reportId}
    ```

    ```javascript
    code 200
    {       
       <file content here>
    }

    code 403
    {
        "message": "Forbidden",
        "status": 403
    }
    ```
*  Download the report
    ```http
    GET /api/v1/report/download/{reportId}
    ```

    ```javascript
    code 200
    {       
       
    }

    code 403
    {
        "message": "Forbidden",
        "status": 403
    }
    ```


### Admin: only admin and supervisor
* Import a teacher list into the system.
    ```http
    POST /api/v1/admin/import.teacher
    ```

    | Parameter | Type | Description |
    | :--- | :--- | :--- |
    | `csv_import` | `file: csv,txt` | **Required, file:csv,txt** |

    ```javascript
    code 200
    {       
        "message": "Tải file thành công, đang chờ xử lý"
    }

    code 401
    {
        "message": "Unauthorized",
        "status": 401
    }

    code 403
    {
        "message": "Forbidden",
        "status": 403
    }
    ``` 

* Import a student list into the system.
    ```http
    POST /api/v1/admin/import.student
    ```

    | Parameter | Type | Description |
    | :--- | :--- | :--- |
    | `csv_import` | `file: csv,txt` | **Required, file:csv,txt** |

    ```javascript
    code 200
    {       
        "message": "Tải file thành công, đang chờ xử lý"
    }

    code 401
    {
        "message": "Unauthorized",
        "status": 401
    }

    code 403
    {
        "message": "Forbidden",
        "status": 403
    }
    ``` 

* Import a subject list into the system.
    ```http
    POST /api/v1/admin/import.subject
    ```

    | Parameter | Type | Description |
    | :--- | :--- | :--- |
    | `csv_import` | `file: csv,txt` | **Required, file:csv,txt** |

    ```javascript
    code 200
    {       
        "message": "Tải file thành công, đang chờ xử lý"
    }

    code 401
    {
        "message": "Unauthorized",
        "status": 401
    }

    code 403
    {
        "message": "Forbidden",
        "status": 403
    }
    ``` 






<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
