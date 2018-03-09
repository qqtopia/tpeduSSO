[臺北市教育人員單一身份驗證服務平台](https://ldap.tp.edu.tw)，為臺北市政府教育局所發展教育人員單一簽入的管理平台，由臺北市老師自主研發，若您為臺北市教師且對程式開發感興趣，請與我聯繫！目前服務平台提供之功能，包含：一般教育人員帳號管理介面、學校管理人員管理介面、Oauth2 驗證服務、教育開放資料介接 Data API。平台使用 [laravel](https://github.com/laravel/laravel) 進行開發，所有程式碼開源，歡迎各縣市網路中心自行下載使用。

## 開發模式

請在開發工作站上安裝 [docker](https://github.com/docker/docker-ce)，請用 docker pull [leejoneshane/laravel](https://github.com/leejoneshane/laravel) 下載開發容器，有關容器的詳細使用方法請看 github 專頁。

請使用 git clone https://github.com/leejoneshane/tpeduLdap.git 下載本專案到開發工作站上，使用
```
docker run -v htdocs:/var/www/localhost/htdocs -d leejoneshane/laravel
```
啟動容器，此指令會將本專案資料夾掛載到容器中執行，只要修改程式碼，就會立即從網頁看到執行結果。

## 關於 docker

Docker Community Edition (CE) is ideal for developers and small teams looking to get started with Docker and experimenting with container-based apps. Available for many popular infrastructure platforms like desktop, cloud and open source operating systems, Docker CE provides an installer for a simple and quick install so you can start developing immediately. Docker CE is integrated and optimized to the infrastructure so you can maintain a native app experience while getting started with Docker. Build the first container, share with team members and automate the dev pipeline, all with Docker Community Edition.

- The latest Docker version with integrated tooling to build, test and run container apps
- Available for free with software maintenance for the latest shipping version
- Integrated and optimized for developer desktops, Linux servers and clouds.
- Monthly Edge and quarterly Stable release channels available
- Native desktop or cloud provider experience for easy onboarding
- Unlimited public and one free private repo storage as a service *
- Automated builds as a service *
- Image scanning and continuous vulnerability monitoring as a service *

## 關於 Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications.