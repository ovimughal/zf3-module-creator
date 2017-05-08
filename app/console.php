<?php
/**
 * ZF3-Module-creator Console File.
 *
 * This is a simple file initializing Console Command 
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

// set to run indefinitely if needed
set_time_limit(0);

/* Optional. It’s better to do it in the php.ini file */
date_default_timezone_set('Asia/calcutta'); 

// include the composer autoloader
//This works when standalone vendor is in app
//require_once __DIR__ . '/../vendor/autoload.php'; 

//This is for use as acomposer package
require_once __DIR__ . '/../../../autoload.php';
// import the Symfony Console Application 


use Commands\OapiConfigServeCommand;
use Commands\ZF3ModuleCommand;
use Symfony\Component\Console\Application;

$app = new Application();
$app->add(new ZF3ModuleCommand());
$app->add(new OapiConfigServeCommand());
$app->run();
