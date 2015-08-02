(1) Export or checkout the airymvc3 in the airymvc folder <br/>

(2) Apache setting <br/>
<VirtualHost *:80> <br/>
DocumentRoot "/your/webroot/project_folder" <br/>
ServerName YourServerName <br/>

<Directory "/your/webroot/project_folder"> <br/>
Options Indexes FollowSymLinks <br/>
AllowOverride None <br/>
Order allow,deny <br/>
Allow from all <br/>
DirectoryIndex index.html index.htm index.php <br/>
<br/>
RewriteEngine On <br/>
RewriteCond %{REQUEST_URI} ^/(js|images|css)/ [OR] <br/>
RewriteCond %{REQUEST_FILENAME} -s [OR] <br/>
RewriteCond %{REQUEST_FILENAME} -l [OR] <br/>
RewriteCond %{REQUEST_FILENAME} -d <br/>
RewriteRule ^.*$ - [NC,L] <br/>
RewriteRule ^.*$ index.php [NC,L] <br/>
<br/>
</Directory> <br/>
</VirtualHost> <br/>
<br/>
(3) Inside the /your/webroot/folder <br/>
link or copy the airymvc folder. Then, the folder will be /your/webroot/project_folder/airymvc <br/>
<br/>
(4) Download the v3example.zip <br/>
inside the “modules” folder  there is a “v3demo" folder <br/>
copy or link the v3demo folder to the project_folder folder <br/>
So, the folder structure will be <br/>
<br/>
project_folder\airymvc <br/>
              \v3demo <br/>
<br/>
(5) Create a index.php file inside the /your/webroot/project_folder folder by linking to Init.php <br/>
ln -s airymvc/Init.php index.php <br/>
<br/>
project_folder\airymvc <br/>
              \v3demo <br/>
              \index.php <br/>
<br/>
(6) Put the demo sql file into Mysql DB <br/>
The sql file is in the v3example\sql folder <br/>
<br/>
(7) Show demo <br/>
demo1: http://YourServerName/demoshorturl <br/>
demo2: http://YourServerName/demotpl <br/>
demo3: http://YourServerName/project_folder/v3demo/index/db/tbl/test <br/>
<br/>
