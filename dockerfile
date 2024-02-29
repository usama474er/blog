# FROM php:5.6-apache
FROM php:5.6-apache

COPY . .

RUN docker-php-ext-install mysqli

RUN chown -R www-data:www-data /var/www

build_docker_image:
    name: Build Docker Image
    runs-on: ubuntu-latest

    steps:
    - name: Checkout code
      uses: actions/checkout@v2

    - name: Set up Docker Buildx
      uses: docker/setup-buildx-action@v1

    - name: Build Docker image
      run: docker build -t alimcs98/php-app:latest .

  push_docker_image:
    name: Push Docker Image
    runs-on: ubuntu-latest

    steps:
    - name: Log in to Docker Hub
      uses: docker/login-action@v1
      with:
        username: alimcs98
        password: alimcs98

    - name: Push Docker image to Docker Hub
      run: docker push alimcs98/php-app:latest
