<?php

/**
 * ZF3-Module-creator Commands File.
 *
 * This is a simple Command file containing methods to create Zend Framework 3 Module without 
 * any pain. :)
 *
 * PHP Version 5.5.9+ (php version)
 *
 * LICENSE: 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. 
 *
 * For details find LICENSE at root directory of this software.
 * 
 * @version   1.0
 * @author    OwaisMughal <ovi.mughal@gmail.com>
 * @copyright 2017
 * @license   MIT
 */

namespace Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ZF3ModuleCommand extends Command
{

    protected $modulePrefix = 'Oapi';

    /**
     * Configure 
     * 
     * In this method setup command, description and its parameters.
     * 
     * @author OwaisMughal <ovi.mughal@gmail.com>
     *
     * @access protected
     */
    protected function configure()
    {
        $moduleName = 'SkeletonModule';

        $this->setName('create:module');
        $this->setDescription('Creates ZF3 MVC Module.');
        $this->setDefinition([
            new InputOption('moduleName', 'm', InputOption::VALUE_OPTIONAL, 'Name of module to be created', $moduleName),
            new InputOption('moduleType', 't', InputOption::VALUE_OPTIONAL, 'Type of module (use only when type is oRest or oapi)', 'zf3'),
        ]);

        $this->setHelp(<<<EOT
Create ZF3 Module or Oapi Module (if you are using Oapiconfig)

options:
    - by not specifying -t default module type is zf3
    - by specifying -t as oRest, Awesome ZF3 Rest api module will be created 
        using Zend's AbstractActionController(module for oapiconfig)
    - by specifying -t as oapi, Oapi Rest module will be created using 
        Zend's AbstractRestfulController(module for oapiconfig)
EOT
        );
    }

    /**
     * Execute 
     * 
     * This method is the start & end point of all the execution.
     * 
     * @author OwaisMughal <ovi.mughal@gmail.com>
     * 
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * 
     * @return numeric value
     * @access protected
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $modName = $input->getOption('moduleName');
        $moduleType = $input->getOption('moduleType');

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $pathWin = shell_exec("pwd | tr -d '\n\n\n'");
            $path = str_replace('/c', 'C:', trim(preg_replace('/\s\s+/', ' ', $pathWin)));
        } else {
            $path = shell_exec("pwd | tr -d '\n'");
        }

        $project = $path;
        $composerJsonFile = $project . '/composer.json';
        $moduleConfigFile = $project . '/config/modules.config.php';

        if ($moduleType == 'oapi') {
            $moduleName = $this->modulePrefix . $modName;
            $moduleName = ucfirst(strtolower($moduleName));
        } else {
            $moduleName = ucfirst($modName);
        }

        list($err, $msg, $composerJsonData) = $this->composerProcess($composerJsonFile, $moduleName);

        if (!$err) {
            if ($moduleType == 'oRest') {
                list($err, $msg) = $this->executeZf3RestShell($path, $modName, $msg);
            } else {
                list($err, $msg) = $moduleType == 'oapi' ? $this->executeOapiShell($path, $modName, $msg) : $this->executeZf3Shell($path, $modName, $msg);
            }
            if (!$err) {
                $this->loadModule($moduleConfigFile, $moduleName);
                //in case shell_exec fails composer file shouldn't be updated
                file_put_contents($composerJsonFile, json_encode($composerJsonData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                shell_exec('cd ' . $path . ' && composer dump-autoload');
            }
        }

        $this->displayResponse($output, $err, $msg);
        // return value is important when using CI
        // to fail the build when the command fails
        // 0 = success, other values = fail
        return 0;
    }

    /**
     * Composer Process
     * 
     * This method is used to populate composer.json file with newly added module
     * 
     * @author OwaisMughal <ovi.mughal@gmail.com>
     * 
     * @param  string $composerJsonFile Will receive composer.json file path	
     * @param  string $modName Will receive module name
     *
     * @return array
     * @access protected
     */
    protected function composerProcess($composerJsonFile, $modName)
    {
        $err = false;

        $moduleName = $modName;//ucfirst(strtolower($modName)); //No Need, doing it in caller now

        if (file_exists($composerJsonFile)) {
            $composerJsonData = json_decode(file_get_contents($composerJsonFile), true);
            if (array_key_exists($moduleName . '\\', $composerJsonData['autoload']['psr-4'])) {
                $err = true;
                $msg = 'Module \'' . $moduleName . '\' already exists';
            } else {
                $composerJsonData['autoload']['psr-4'][$moduleName . '\\'] = 'module/' . $moduleName . '/src';
                //file_put_contents($composerJsonFile, json_encode($composerJsonData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                $msg = 'Module \'' . $moduleName . '\' created successfully';
            }
        } else {
            $err = true;
            $msg = '\'' . $composerJsonFile . '\' is not a valid path';
        }

        return [$err, $msg, $composerJsonData];
    }

    /**
     * checkConnection 
     * 
     * This method checks whether connected to Internet or not
     * 
     * @author OwaisMughal <ovi.mughal@gmail.com>
     * 
     * @return boolean
     * @access protected
     */
    protected function checkConnection()
    {
        $connected = @fsockopen("google.com", 80, $errno, $errstr, 5);
        //website, port  (try 80 or 443)
        if ($connected) {
            $is_conn = true; //action when connected
            fclose($connected);
        } else {
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }

    /**
     * Execute Zf3 Shell
     * 
     * This method is used to execute shell commands for Zf3 Module
     * 
     * @author OwaisMughal <ovi.mughal@gmail.com>
     * 
     * @param  string $path Will receive the project root path	
     * @param  string $modName Will receive module name
     * @param  string $msg Will receive existing error or success message
     *
     * @return array
     * @access protected
     */
    protected function executeZf3Shell($path, $modName, $msg)
    {
        $err = false;

        $moduleName = ucfirst(strtolower($modName));
        if (is_dir($path . '/module')) {

            if ($this->checkConnection()) {
                shell_exec('cd ' . $path . '/module && git clone https://github.com/zendframework/ZendSkeletonModule ' . $moduleName . ' && cd ' . $moduleName . ' && rm -Rf .git .gitignore && git remote remove origin');
            } else {
                shell_exec(
                        'cd vendor/ovimughal/zf3-module-creator && '
                        . 'cp -R ZendSkeletonModule ' . $path . '/module/' . $moduleName
                );
            }

            //Rename all directories with upper name
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && find . -depth -name ZendSkeletonModule -type d -execdir mv {} ' . $moduleName . ' \;');

            //Rename all directories with lower case
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && find . -depth -name zend-skeleton-module -type d -execdir mv {} ' . strtolower($moduleName) . ' \;');

            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && find . -depth -name skeleton -type d -execdir mv {} ' . strtolower($moduleName) . ' \;');

            //Rename all files
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                shell_exec('cd ' . $path . '/module/' . $moduleName . '/src/Controller && mv SkeltonController.php ' . ucfirst($moduleName) . 'Controller.php');
            } else {
                //Rename all files
                shell_exec('cd ' . $path . '/module/' . $moduleName . ' && find . -depth -iname "Skeleton*" -execdir mv {} ' . $moduleName . 'Controller.php \;');
            }

            //Rename within files with upper case
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'ZendSkeletonModule\' ./ | xargs sed -i \'s/ZendSkeletonModule/' . $moduleName . '/g\'');
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'SkeletonController\' ./ | xargs sed -i \'s/SkeletonController/' . $moduleName . 'Controller/g\'');

            //Rename within files with lower case
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'Literal\' ./ | xargs sed -i \'s/Literal/Segment/g\'');
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'module-name-here\' ./ | xargs sed -i \'s/module-name-here/' . strtolower($moduleName) . '/g\'');
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'module-specific-route\' ./ | xargs sed -i \'s/module-specific-root/' . strtolower($moduleName) . '\[\/\:action\]\[\/\]\[\:id\]/g\'');
        } else {
            $err = true;
            $msg = '\'' . $path . '/module' . '\' does not exist';
        }

        return [$err, $msg];
    }
    
    /**
     * Execute Zf3 Rest Shell
     * 
     * This method is used to execute shell commands for Zf3 Rest Module
     * 
     * @author OwaisMughal <ovi.mughal@gmail.com>
     * 
     * @param  string $path Will receive the project root path	
     * @param  string $modName Will receive module name
     * @param  string $msg Will receive existing error or success message
     *
     * @return array
     * @access protected
     */
    protected function executeZf3RestShell($path, $modName, $msg)
    {
        $err = false;

        $moduleName = ucfirst($modName);
        if (is_dir($path . '/module')) {
            
                shell_exec(
                        'cd vendor/ovimughal/zf3-module-creator && '
                        . 'cp -R ZendSkeletonRestModule ' . $path . '/module/' . $moduleName
                );

            //Rename all directories with upper name
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && find . -depth -name ZendSkeletonRestModule -type d -execdir mv {} ' . $moduleName . ' \;');

            //Rename all directories with lower case
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && find . -depth -name zend-skeleton-module -type d -execdir mv {} ' . strtolower($moduleName) . ' \;');

            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && find . -depth -name skeleton -type d -execdir mv {} ' . strtolower($moduleName) . ' \;');

            //Rename all files
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                shell_exec('cd ' . $path . '/module/' . $moduleName . '/src/Controller && mv SkeltonController.php ' . $moduleName . 'Controller.php');
                shell_exec('cd ' . $path . '/module/' . $moduleName . '/src/Handler && mv RestmodHandler.php ' . $moduleName . 'Handler.php');
                shell_exec('cd ' . $path . '/module/' . $moduleName . '/src/Model && mv OapirestmodModel.php ' . $moduleName . 'Model.php');
            } else {
                //Rename all files
                shell_exec('cd ' . $path . '/module/' . $moduleName . ' && find . -depth -iname "Skeleton*" -execdir mv {} ' . $moduleName . 'Controller.php \;');
                shell_exec('cd ' . $path . '/module/' . $moduleName . ' && find . -depth -iname "RestmodHandler*" -execdir mv {} ' . $moduleName . 'Handler.php \;');
                shell_exec('cd ' . $path . '/module/' . $moduleName . ' && find . -depth -iname "OapirestmodModel*" -execdir mv {} ' . $moduleName . 'Model.php \;');
            }
            //Rename within files with upper case
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'ZendSkeletonModule\' ./ | xargs sed -i \'s/ZendSkeletonModule/' . $moduleName . '/g\'');
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'SkeletonController\' ./ | xargs sed -i \'s/SkeletonController/' . $moduleName . 'Controller/g\'');
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'RestmodHandler\' ./ | xargs sed -i \'s/RestmodHandler/' . $moduleName . 'Handler/g\'');
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'OapirestmodModel\' ./ | xargs sed -i \'s/OapirestmodModel/' . $moduleName . 'Model/g\'');
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'Oapirestmod\' ./ | xargs sed -i \'s/Oapirestmod/' . $moduleName . '/g\'');

            //Rename within files with lower case
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'module-name-here\' ./ | xargs sed -i \'s/module-name-here/' . strtolower($moduleName) . '/g\'');
            shell_exec('cd ' . $path . '/module/' . $moduleName . ' && grep -rl \'module-specific-route\' ./ | xargs sed -i \'s/module-specific-route/' . strtolower($moduleName) . '/g\'');
        } else {
            $err = true;
            $msg = '\'' . $path . '/module' . '\' does not exist';
        }

        return [$err, $msg];
    }

    /**
     * Execute Oapi Shell
     * 
     * This method is used to execute shell commands for Oapi Module
     * 
     * @author OwaisMughal <ovi.mughal@gmail.com>
     * 
     * @param  string $path Will receive the project root path	
     * @param  string $modName Will receive module name
     * @param  string $msg Will receive existing error or success message
     *
     * @return array
     * @access protected
     */
    protected function executeOapiShell($path, $modName, $msg)
    {
        $err = false;
        $modulePrefix = $this->modulePrefix;
        $moduleName = strtolower($modName);

        if (is_dir($path . '/module')) {
            shell_exec(
                    'cd vendor/ovimughal/zf3-module-creator && '
                    . 'cp -R Oapirestmod ' . $path . '/module/' . $modulePrefix . $moduleName
            );

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                shell_exec('cd ' . $path . '/module/' . $modulePrefix . $moduleName . '/src/Model && mv OapirestmodModel.php ' . $modulePrefix . $moduleName . 'Model.php');
                shell_exec('cd ' . $path . '/module/' . $modulePrefix . $moduleName . '/src/Controller && mv RestmodController.php ' . ucfirst($moduleName) . 'Controller.php');
                shell_exec('cd ' . $path . '/module/' . $modulePrefix . $moduleName . '/src/Handler && mv RestmodHandler.php ' . ucfirst($moduleName) . 'Handler.php');
            } else {



                //Rename all files
                shell_exec('cd ' . $path . '/module/' . $modulePrefix . $moduleName . ' && find . -depth -iname "OapirestmodModel*" -execdir mv {} ' . $modulePrefix . $moduleName . 'Model.php \;');
                shell_exec('cd ' . $path . '/module/' . $modulePrefix . $moduleName . ' && find . -depth -iname "RestmodController*" -execdir mv {} ' . ucfirst($moduleName) . 'Controller.php \;');
                shell_exec('cd ' . $path . '/module/' . $modulePrefix . $moduleName . ' && find . -depth -iname "RestmodHandler*" -execdir mv {} ' . ucfirst($moduleName) . 'Handler.php \;');
            }
            //Rename within files with upper case
            shell_exec('cd ' . $path . '/module/' . $modulePrefix . $moduleName . ' && grep -rl \'Oapirestmod\' ./ | xargs sed -i \'s/Oapirestmod/' . $modulePrefix . $moduleName . '/g\'');
            shell_exec('cd ' . $path . '/module/' . $modulePrefix . $moduleName . ' && grep -rl \'oapirestmod\' ./ | xargs sed -i \'s/oapirestmod/' . strtolower($modulePrefix . $moduleName) . '/g\'');

            //Rename within files with lower case        
            shell_exec('cd ' . $path . '/module/' . $modulePrefix . $moduleName . ' && grep -rl \'Restmod\' ./ | xargs sed -i \'s/Restmod/' . ucfirst($moduleName) . '/g\'');
            shell_exec('cd ' . $path . '/module/' . $modulePrefix . $moduleName . ' && grep -rl \'restmod\' ./ | xargs sed -i \'s/restmod/' . $moduleName . '/g\'');
        } else {
            $err = true;
            $msg = '\'' . $path . '/module' . '\' does not exist';
        }

        return [$err, $msg];
    }

    /**
     * Load Module
     * 
     * This method is used to load module into config/modules.config.php file
     * 
     * @author OwaisMughal <ovi.mughal@gmail.com>
     * 
     * @param  string $moduleConfigFile Will receive the modules.config.php file path
     * @param  string $moduleName Will receive module name
     *
     * @access protected
     */
    protected function loadModule($moduleConfigFile, $modName)
    {
        $moduleName = $modName;//ucfirst(strtolower($modName));No need, doing it in caller now

        $loadedModules = require ($moduleConfigFile);
        $loadedModules[] = $moduleName;

        $moduleStr = implode(",", $loadedModules);
        $formatedModulesStr = "'" . str_replace(",", "',\n'", $moduleStr) . "'";

        file_put_contents($moduleConfigFile, "<?php \n return[ \n" . $formatedModulesStr . "\n];");
    }

    /**
     * Display response
     * 
     * This method is used display response on console
     * 
     * @author OwaisMughal <ovi.mughal@gmail.com>
     * 
     * @param  OutputInterface $output
     * @param  boolean $err
     * @param  string $msg Will receive error or success message
     *
     * @access protected
     */
    protected function displayResponse(OutputInterface $output, $err, $msg)
    {
        $color = 'green';
        if ($err) {
            $color = 'red';
        }
        $header_style = new OutputFormatterStyle('white', $color, array('bold'));
        $output->getFormatter()->setStyle('header', $header_style);

        $output->writeln(sprintf(
                        '<header>' . $msg . '</header>'
        ));
    }

}
