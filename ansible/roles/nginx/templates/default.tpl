server {
    listen                      80;
    server_tokens               off;
    return 301                  https://$host$request_uri;
}

server {
    client_max_body_size        16M;
    listen                      443 ssl;
    server_tokens               off;
    ssl_certificate             /etc/ssl/certs/oitc.crt;
    ssl_certificate_key         /etc/ssl/private/oitc.key;

    ssl_protocols               TLSv1 TLSv1.1 TLSv1.2;
    ssl_ciphers                 "EECDH+AESGCM:EDH+AESGCM:ECDHE-RSA-AES128-GCM-SHA256:AES256+EECDH:DHE-RSA-AES128-GCM-SHA256:AES256+EDH:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-SHA256:ECDHE-RSA-AES256-SHA:ECDHE-RSA-AES128-SHA:DHE-RSA-AES256-SHA256:DHE-RSA-AES128-SHA256:DHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA:ECDHE-RSA-DES-CBC3-SHA:EDH-RSA-DES-CBC3-SHA:AES256-GCM-SHA384:AES128-GCM-SHA256:AES256-SHA256:AES128-SHA256:AES256-SHA:AES128-SHA:DES-CBC3-SHA:HIGH:!aNULL:!eNULL:!EXPORT:!DES:!MD5:!PSK:!RC4";
    ssl_prefer_server_ciphers   on;
    ssl_session_cache           shared:SSL:10m;

    root                        {{ nginx.doc_root }}/app/webroot/;
    index                       index.php;

    access_log                  /var/log/nginx/oitc_access.log;
    error_log                   /var/log/nginx/oitc_error.log;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        try_files       $uri =404;
        include         /etc/nginx/fastcgi_params;
        fastcgi_pass    unix:/var/run/php5-fpm.sock;
        fastcgi_index   index.php;
        fastcgi_param   SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    #Proxy for WebSockets over HTTPS (firewalls bypass)
    location /sudo_server {
        proxy_pass http://127.0.0.1:8081;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }

    #Proxy for WebSockets over HTTPS (firewalls bypass)
    location /chat_server {
        proxy_pass http://127.0.0.1:8080;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }

    #pnp4nagios
    #Many thanks to: http://sourceforge.net/p/pnp4nagios/mailman/message/26979157/
    location /pnp4nagios {
        alias /opt/openitc/nagios/share/pnp4nagios;
        #auth_basic "Authentication Challenge Title"; auth_basic_user_file /path_to_password_file/htpasswd.users;
        index index.php;
        # if we have e.g. /pnp4nagios/media/css/common.css
        # nginx will check /usr/local/png4nagios/share/media/css/common/css
        # if it can't find a matching file even adding a trailing / the request is handled to the @pnp4nagios location
        try_files $uri $uri/ @pnp4nagios;
    }

    location ~ ^(/pnp4nagios.*\.php)(.*)$ {
        root /opt/openitc/nagios/share/pnp4nagios;
        include /etc/nginx/fastcgi_params;
        add_header    Cache-Control  no-cache;
        add_header    Pragma         no-cache;
        if ($request_uri !~ /pnp4nagios/share/(.*)) {
            rewrite ^/pnp4nagios/(.*)$ /$1; break;
        }
        fastcgi_split_path_info ^(.+\.php)(.*)$;
        fastcgi_param   SCRIPT_FILENAME     /usr/local/pnp4nagios/share$fastcgi_script_name;
        fastcgi_param   SCRIPT_FILENAME     $document_root$fastcgi_script_name;
        fastcgi_param   PATH_INFO           $fastcgi_path_info;
        fastcgi_pass    unix:/var/run/php5-fpm.sock;
        fastcgi_index   index.php;
    }
}