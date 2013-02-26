#ホストOSのhostsファイルに以下を追加
192.168.56.103  library-system.local  
192.168.56.103  staff.library-system.local  
  
#OS:
SL-63-i386-2012-08-02-Install-DVD.iso (Desktop)  
  
#Apache:
Server version: Apache/2.2.15 (Unix)  
Server built:   Feb 14 2012 09:47:24  
  
#PHP:
PHP 5.3.3 (cli) (built: Jun 27 2012 14:13:38)  
Copyright (c) 1997-2010 The PHP Group  
Zend Engine v2.3.0, Copyright (c) 1998-2010 Zend Technologies  
  
#MySQL:
mysql  Ver 14.14 Distrib 5.1.67, for redhat-linux-gnu (i386) using readline 5.1  
  
#以下のディレクトリを作成
/web/library-system/  
/web/staff.library-system/  
  
#以下のconfファイルを、/etc/httpd/conf.dに作成
vh.conf  
staff-vu.conf  
library-system-vh.conf  

[vh.conf]

    NameVirtualHost *:80

[staff-vh.conf]

    <VirtualHost _default_:80>
         DocumentRoot     /web/staff.library-system/web
         ServerName     staff.library-system.local
         ErrorLog     "/var/log/httpd/staff_vh_error_log"
    </VirtualHost>

    <Directory "/web/staff.library-system/web">
         AllowOverride All
         Order deny,allow
         deny from all
         allow from 127.0.0.1
         allow from 192.168.56.1
    </Directory>

[library-system-vh.conf]

    <VirtualHost _default_:80>
         DocumentRoot     /web/library-system/web
         ServerName     library-system.local
         ErrorLog     "/var/log/httpd/library-system_vh_error_log"
    </VirtualHost>
 
    <Directory "/web/library-system/web">
        AllowOverride All
    </Directory>

#php.iniに以下を設定
    date.timezone = Asia/Tokyo
