server {

    listen 443;
    server_name www.wxappr.com;

    root /srv/www/wxappr/public;
    index index.php index.html;

    server_tokens on;

    ssl on;
    ssl_certificate /etc/nginx/1_www.wxappr.com_bundle.crt;
    ssl_certificate_key /etc/nginx/2_www.wxappr.com.key;

    ssl_session_timeout 5m;

    ssl_protocols TLSv1 TLSv1.1 TLSv1.2; #按照这个协议配置
    ssl_ciphers ECDHE-RSA-AES128-GCM-SHA256:HIGH:!aNULL:!MD5:!RC4:!DHE;#按照这个套件配置
    ssl_prefer_server_ciphers on;

    client_max_body_size 64M;
    charset              utf-8;

    add_header X-UA-Compatible "IE=Edge,chrome=1";
    add_header X-Frame-Options DENY;
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block;";

    location / {
        if ($http_user_agent ~* (nmap|nikto|wikto|sf|sqlmap|bsqlbf|w3af|acunetix|havij|appscan)) {
            return 403;
        }

        try_files $uri $uri/ @rewrite;
    }

    location ~* \.(eot|ttf|woff)$ {
        add_header Access-Control-Allow-Origin '*';
    }

    location @rewrite {
        rewrite ^/(.*)$ /index.php?_url=/$1 last;
    }

    location ~ \.php {
        include fastcgi_params;

        try_files $uri =404;
        fastcgi_intercept_errors on;

        fastcgi_split_path_info ^(.+\.php)(/.+)$;

        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_param   SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        fastcgi_param   PATH_INFO        $fastcgi_path_info;
        fastcgi_param   PATH_TRANSLATED  $document_root$fastcgi_path_info;
        fastcgi_param   HTTP_REFERER     $http_referer;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;

        include fastcgi_params;
    }

    # Blocking access to all the hidden files, (.htaccess, .git, .svn etc.)
    location ~ /\. {
        return 403;
    }
}

server {
    listen 80;
    server_name www.wxappr.com;
    return 301 https://www.wxappr.com$request_uri;
}

server {
    listen 443;
    server_name api.wxappr.com;
    return 301 https://www.wxappr.com/api/$request_uri;
}

server {
   listen 443;
   server_name wxappr.com;
   return 301 https://www.wxappr.com$request_uri;
}

server {
    listen 80;
    server_name wxappr.com;
    return 301 https://www.wxappr.com$request_uri;
}
