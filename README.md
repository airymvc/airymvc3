(1) Export or checkout the airymvc3 in the airymvc folder

(2) Apache setting
<VirtualHost *:80>
DocumentRoot "/your/webroot/project_folder"
ServerName YourServerName

<Directory "/your/webroot/project_folder">
Options Indexes FollowSymLinks
AllowOverride None
Order allow,deny
Allow from all
DirectoryIndex index.html index.htm index.php

RewriteEngine On
RewriteCond %{REQUEST_URI} ^/(js|images|css)/ [OR]
RewriteCond %{REQUEST_FILENAME} -s [OR]
RewriteCond %{REQUEST_FILENAME} -l [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^.*$ - [NC,L]
RewriteRule ^.*$ index.php [NC,L]

</Directory>
</VirtualHost>

(3) Inside the /your/webroot/folder
link or copy the airymvc folder. Then, the folder will be /your/webroot/project_folder/airymvc

(4) Download the v3example.zip
inside the “modules” folder  there is a “v3demo" folder
copy or link the v3demo folder to the project_folder folder
So, the folder structure will be

project_folder\airymvc
              \v3demo

(5) Create a index.php file inside the /your/webroot/project_folder folder by linking to Init.php
ln -s airymvc/Init.php index.php

project_folder\airymvc
              \v3demo
              \index.php

(6) Put the demo sql file into Mysql DB
The sql file is in the v3example\sql folder

(7) Show demo
demo1: http://YourServerName/demoshorturl
demo2: http://YourServerName/demotpl
demo3: http://YourServerName/project_folder/v3demo/index/db/tbl/test
