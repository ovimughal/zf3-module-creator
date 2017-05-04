# zf3-module-creator
Create ZF3 module with one console command

# Install
1. Open terminal
2. `cd /path/of/your/application` (e.g `cd /var/www/ZF3-App`)
3. `composer require ovimughal/zf3-module-creator`

# Start Using
From app root directory enter: <br>
`php vendor/ovimughal/zf3-module-creator/app/console.php module:create -m <Your-Module-Name>`

# For simplicity (Optional)
1. create a php file in you application root (e.g zf3-module.php)
2. Open it in your favourite text-editor
3. Paste following line <br>
    `<?php eval(base64_decode('cmVxdWlyZSBfX0RJUl9fLicvdmVuZG9yL292aW11Z2hhbC96ZjMtbW9kdWxlLWNyZWF0b3IvYXBwL2NvbnNvbGUucGhwJzs='));`<br>
   And save
4. Now from your terminal simply enter:<br>
    `php zf3-module.php create:module -m <Your-Module-Name>`<br>
   And your ZF3 Module is ready to use
5. Test in your browser `http://hostname:port/yourapp/yourmodule` no configuration needed.
6. Enjoy :)
