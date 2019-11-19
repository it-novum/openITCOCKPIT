<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.


App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Http;

/**
 * @author Patrick Nawracay <patrick.nawracay@it-novum.com>
 * @since  3.0
 */
class PackageManager {

    /* @var $sudoServerInterface SudoMessageInterface */
    protected $sudoServerInterface = null;
    /* @var $taskName string */
    protected $taskName = '';

    /**
     * @param $sudoServerInterface SudoMessageInterface
     * @param $taskName            string
     */
    public function __construct(SudoMessageInterface $sudoServerInterface, $taskName) {
        $this->sudoServerInterface = $sudoServerInterface;
        $this->taskName = $taskName;
    }

    /**
     * Installs a given package by URL and it's name.
     * The name has to be identical with the directory of the zip file.
     *
     * @param $url        string The URL of the archive file. Should be a ZIP file.
     * @param $pluginName string The name of the package and folder.
     *
     * @return boolean True if the package was installed successfully, false otherwise.
     */
    public function install($url, $pluginName) {
        if (empty($url) || empty($pluginName)) {
            $this->debugAndSend(sprintf('The given URL ("%s") or name ("%s") is not valid!', $url, $pluginName));

            return false;
        }
        $this->send('Parameter validated successfully.');

        // Fetch the archive
        $zipFile = $this->fetchFile($url);
        if (!is_object($zipFile)) {
            $this->debugAndSend('The file with URL "' . $url . '" couldn\'t get fetched!');

            return false;
        }
        $this->send('Archive file fetched successfully.');

        // Check if the downloaded file exists
        if (!$zipFile->exists()) {
            $this->debugAndSend('The downloaded archive does not exist!');

            return false;
        }
        $this->send('Validated extraction path.');

        // Create temp directory
        $tempDir = sys_get_temp_dir() . DS . $pluginName;
        @mkdir($tempDir, 0777, true);
        $this->send('Temp directory created.');

        // Extract the archive
        if (!$this->extractZip($zipFile, $tempDir)) {
            $this->debugAndSend('The file ' . $zipFile->path . ' couldn\'t get extracted!');

            return false;
        }
        $this->send('Plugin extracted successfully.');

        // Clean up - delete downloaded archive file
        if (!$zipFile->delete()) {
            $this->debugAndSend('Cannot delete file "' . $zipFile->path . '".');

            return false;
        }
        $this->send('Cleaning up...');

        // Move the archive to the plugin directory
        $pluginDir = new Folder(OLD_APP . 'Plugin');
        $tempDir = new Folder($tempDir);
        if (!$tempDir->move($pluginDir->path . DS . $pluginName)) {
            $msg = 'Moving archive to Plugin directory failed.' . "\n";
            $msg .= '$tempDir: ' . $tempDir . "\n";
            $msg .= '$pluginDir: ' . $pluginDir . "\n";

            //debug($msg);

            return false;
        }
        $this->send('Installation of Plugin "' . $pluginName . '" was successful.');

        // Run the install.php file of the package, if it exists
        $acceptedFileNames = ['setup.php', 'install.php'];
        foreach ($acceptedFileNames as $installFileName) {
            $fileName = OLD_APP . 'Plugin' . DS . $pluginName . DS . $installFileName;
            //debug($fileName);
            if (file_exists($fileName)) {
                $this->debugAndSend('Additional installation file found. Executing "' . $fileName . '"');
                require $fileName;
            }
        }

        return true;
    }

    /**
     * Alias for deletePackage until this feature may get implemented.
     */
    public function uninstall($name) {
        // Run the install.php file of the package, if it exists
        $acceptedFileNames = ['uninstall.php', 'delete.php'];
        foreach ($acceptedFileNames as $installFileName) {
            $fileName = OLD_APP . 'Plugin' . DS . $name . DS . $installFileName;
            //debug($fileName);
            if (file_exists($fileName)) {
                $this->debugAndSend('Additional uninstallation file found. Executing "' . $fileName . '"');
                require $fileName;
            }
        }

        return $this->delete($name);
    }

    /**
     * Deletes/Removes a package completly of the file system.
     * The name has to be identical with the folder's name in the Plugin directory.
     *
     * @param $name string The name of the package.
     *
     * @return boolean True on success, false otherwise.
     */
    public function delete($name) {
        if (empty($name)) {
            $this->debugAndSend('The given package name is empty!');

            return false;
        }

        $folder = new Folder(OLD_APP);
        if (!$folder->cd('Plugin') || !$folder->cd($name)) {
            $this->debugAndSend('The Plugin directory wasn\'t found!');

            return false;
        }
        $this->send('Changed directory to Plugin directory.');

        if (!$folder->delete()) {
            $this->debugAndSend('Could\'nt delete Plugin');

            return false;
        }
        $this->send('Plugin "' . $name . '" deleted successfully.');

        return true;
    }


    /**
     * Fetches a file by the given URL.
     * The file will be stored in a temporary folder. Uses itnovum\openITCOCKPIT\Core\Http internally.
     *
     * @param $url    string URL of a file which should get fetched.
     * @param $target string Path to store the file.
     *
     * @return false|\Utility\File Returns false on failure. Otherwise an CakePHP file object.
     */
    protected function fetchFile($url) {
        // Data validation
        if (!Validation::url($url, true)) {
            debug('The given URL is not valid!');

            return false;
        }

        $curlSettings = [];
        if (ENVIRONMENT == Environments::DEVELOPMENT) {
            $curlSettings = [
                'CURLOPT_SSL_VERIFYPEER' => false,
                'CURLOPT_SSL_VERIFYHOST' => false,
            ];
        }

        /** @var $Proxy App\Model\Table\ProxiesTable */
        $Proxy = TableRegistry::getTableLocator()->get('Proxies');

        $httpComponent = new Http($url, $curlSettings, $Proxy->getSettings());
        $httpComponent->sendRequest();

        if (empty($httpComponent->data)) {
            return false;
        }

        $tempDirectory = sys_get_temp_dir(); // Use tempnam() here ?
        $localFilePath = $tempDirectory . '/' . basename($url);
        file_put_contents($localFilePath, $httpComponent->data);

        return new File($localFilePath);
    }


    /**
     * Extract the ZIP file to the given path.
     *
     * @param $file \Utility\File CakePHP file object.
     *
     * @return boolean
     */
    protected function extractZip($file, $destination) {
        if (!$file->exists()) {
            return false;
        }
        @mkdir($destination, 0777, true);
        $zip = new ZipArchive();
        if ($zip->open($file->path) === true) {
            $success = $zip->extractTo($destination);
            $zip->close();

            return $success;
        }

        return false;
    }

    /**
     * Sends responses with the task name which was given on instantiation.
     * Wrapper method for SudoServerInterface::send.
     *
     * @param string $payload
     *
     * @return void
     */
    protected function send($payload, $category = 'notification') {
        $this->sudoServerInterface->send($payload, 'response', $this->taskName, $category);
    }

    /**
     */
    protected function exec($command, $options = []) {
        $options = [
            'task' => $this->taskName,
        ];
        $this->sudoServerInterface->exec($command, $options);
    }

    /**
     * Used internally to debug and send a response with one command.
     * Mostly used for error message.
     *
     * @param $message string
     */
    private function debugAndSend($message) {
        //debug($message);
        $this->send($message);
    }
}
