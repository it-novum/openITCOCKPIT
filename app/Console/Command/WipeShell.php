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

class WipeShell extends AppShell
{
    public function main()
    {
        $this->parser = $this->getOptionParser();
        $this->out('<info>openITCOCKPIT file wiping shell</info>');
        if (isset($this->params['file'])) {
            $this->wipeFile($this->params['file']);
        }

        if (isset($this->params['dir'])) {
            $this->wipeDir();
        }
    }

    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'recursive' => ['short' => 'r', 'help' => 'Searching for files recursive', 'boolean' => true],
            'file'      => ['short' => 'f', 'help' => "The file that should be wiped"],
            'dir'       => ['short' => 'd', 'help' => "The directory that should be wiped"],
        ]);

        return $parser;
    }

    private function wipeFile($file)
    {
        if (!file_exists($file)) {
            $this->out('<error>File does not exists</error>');

            return false;
        }

        if ($this->params['verbose']) {
            $this->out('<comment>Wiping file: '.$file.'</comment>');
        }
        fclose(fopen($file, 'w+'));
    }

    private function wipeDir()
    {
        if (!is_dir($this->params['dir'])) {
            $this->out('<error>Directory does not exists</error>');

            return false;
        }

        App::uses('Folder', 'Utility');
        $dir = new Folder($this->params['dir']);

        if (!isset($this->params['recursive'])) {
            $files = $dir->find();
            foreach ($files as $file) {
                $this->wipeFile($dir->pwd().$file);
            }
        } else {
            // I want to wipe recursive
            $files = $dir->findRecursive();
            foreach ($files as $file) {
                $this->wipeFile($file);
            }
        }
    }
}