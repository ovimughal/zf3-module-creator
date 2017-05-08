# zf3-module-creator (Additional Oapi-module-creator for Oapiconfig module)
Create ZF3 module with one console command

# Install
1. Open terminal
2. `cd /path/of/your/application` (e.g `cd /var/www/ZF3-App`)
3. `composer require ovimughal/zf3-module-creator`

# Start Using
From app root directory enter: <br>
`php vendor/ovimughal/zf3-module-creator/app/console.php create:module -m <Your-Module-Name>`

# For simplicity (Optional)
1. create a php file in you application root (e.g zf3-module.php)
2. Open it in your favourite text-editor
3. Paste following line <br>
    `<?php eval(base64_decode('cmVxdWlyZSBfX0RJUl9fLicvdmVuZG9yL292aW11Z2hhbC96ZjMtbW9kdWxlLWNyZWF0b3IvYXBwL2NvbnNvbGUucGhwJzs='));`<br>
   And save
4. Now from your terminal simply enter:<br>
    `php zf3-module.php create:module -m <Your-Module-Name>`<br>
   And your ZF3 Module is ready to use <br>
   #Options
   1. `-m <Module-Name>` (Default is `SkeletonModule`)
   2. `-t <Type>` (Type is either `zf3` or `oapi`, default is `zf3`, any other type other than `zf3` will consider `oapi`)
5. Test in your browser `http://hostname:port/yourapp/yourmodule` no configuration needed.
6. Enjoy :)

# For `Oapiconfig` Module users
1. For installation <a href='https://github.com/ovimughal/oapiconfig'>Oapiconfig</a>
2. Once you are up with installation, from your root directory type in following command in terminal<br>
    `zf3-module.php oapi:serve` (zf3-module.php is the file we created above in step 1)<br>
   This will serve Oapiconfig module & do all the necessary configurations automatically<br>
   Also some config files will be generated for you.
3. For `doctrine` to work properly we need to tell it the location of Entities<br>
   Paste following in any `module\<module-name>\config\module.config.php` return array
   `'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],`
4. You are Done :)
