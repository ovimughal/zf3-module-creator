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

class ZF3ModuleCommand extends Command {

    /**
     * Configure 
     * 
     * In this method setup command, description and its parameters.
     * 
     * @author OwaisMughal <ovi.mughal@gmail.com>
     *
     * @access protected
     */
    protected function configure() {
        $moduleName = 'SkeletonModule';

        $this->setName('create:module');
        $this->setDescription('Creates ZF3 MVC Module.');
        $this->setDefinition([
            new InputOption('moduleName', 'm', InputOption::VALUE_OPTIONAL, 'Name of module to be created', $moduleName),
        ]);

        $this->setHelp(<<<EOT
Create ZF3 Module

Usage:

    <info>php app/console.php create:module -m ModuleName</info>
    e.g php app/console.php create:module -m Testmodule
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
    protected function execute(InputInterface $input, OutputInterface $output) {
        $moduleName = $input->getOption('moduleName');
        $path = shell_exec("pwd | tr -d '\n'");

        $project = $path;
        $composerJsonFile = $project . '/composer.json';
        $moduleConfigFile = $project . '/config/modules.config.php';

        list($err, $msg, $composerJsonData) = $this->composerProcess($composerJsonFile, $moduleName);

        if (!$err) {
            list($err, $msg) = $this->executeShell($path, $moduleName, $msg);

            if (!$err) {
                $this->loadModule($moduleConfigFile, $moduleName);
                //in case shell_exec fails composer file shouldn't be updated
                file_put_contents($composerJsonFile, json_encode($composerJsonData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                shell_exec('cd ' . $path . ';composer dump-autoload');
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
     * @param  string $modulename Will receive module name
     *
     * @return array
     * @access protected
     */
    protected function composerProcess($composerJsonFile, $modName) {
        $err = false;
        
        $moduleName = ucfirst(strtolower($modName));
        
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
     * Execute Shell
     * 
     * This method is used to execute shell commands
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
    protected function executeShell($path, $modName, $msg) {
        $err = false;

        $moduleName = ucfirst(strtolower($modName));
        if (is_dir($path . '/module')) {

            shell_exec('cd ' . $path . '/module;git clone https://github.com/zendframework/ZendSkeletonModule ' . $moduleName . ';cd ' . $moduleName . ';rm -Rf .git .gitignore;git remote remove origin;');

            //Rename all directories with upper name
            shell_exec('cd ' . $path . '/module/' . $moduleName . ';find . -depth -name ZendSkeletonModule -type d -execdir mv {} ' . $moduleName . ' \;');

            //Rename all directories with lower case
            shell_exec('cd ' . $path . '/module/' . $moduleName . ';find . -depth -name zend-skeleton-module -type d -execdir mv {} ' . strtolower($moduleName) . ' \;');

            shell_exec('cd ' . $path . '/module/' . $moduleName . ';find . -depth -name skeleton -type d -execdir mv {} ' . strtolower($moduleName) . ' \;');

            //Rename all files
            shell_exec('cd ' . $path . '/module/' . $moduleName . ';find . -depth -iname "Skeleton*" -execdir mv {} ' . $moduleName . 'Controller.php \;');

            //Rename within files with upper case
            shell_exec('cd ' . $path . '/module/' . $moduleName . ';grep -rl \'ZendSkeletonModule\' ./ | xargs sed -i \'s/ZendSkeletonModule/' . $moduleName . '/g\'');
            shell_exec('cd ' . $path . '/module/' . $moduleName . ';grep -rl \'SkeletonController\' ./ | xargs sed -i \'s/SkeletonController/' . $moduleName . 'Controller/g\'');

            //Rename within files with lower case
            shell_exec('cd ' . $path . '/module/' . $moduleName . ';grep -rl \'Literal\' ./ | xargs sed -i \'s/Literal/Segment/g\'');
            shell_exec('cd ' . $path . '/module/' . $moduleName . ';grep -rl \'module-name-here\' ./ | xargs sed -i \'s/module-name-here/' . strtolower($moduleName) . '/g\'');
            shell_exec('cd ' . $path . '/module/' . $moduleName . ';grep -rl \'module-specific-root\' ./ | xargs sed -i \'s/module-specific-root/' . strtolower($moduleName) . '\[\/\:action\]\[\/\]\[\:id\]/g\'');
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
    protected function loadModule($moduleConfigFile, $modName) {
        $moduleName = ucfirst(strtolower($modName));
        
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
    protected function displayResponse(OutputInterface $output, $err, $msg) {
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
