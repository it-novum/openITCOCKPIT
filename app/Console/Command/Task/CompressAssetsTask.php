<?php

class CompressAssetsTask extends AppShell {

    /**
     * Main Action
     * @return void
     */
    public function execute() {
        $this->out('Combining and compressing assets');
        $this->_compileFrontendDependencies();
    }

    /**
     * Collects, combines and compresses all JS and CSS dependencies.
     * @return void
     */
    protected function _compileFrontendDependencies() {
        if (!is_writable(OLD_TMP)) {
            return $this->error(OLD_TMP . ' is not writeable, cannot compile.');
        }
        if (!is_writable(JS)) {
            return $this->error(JS . ' is not writeable, cannot compile.');
        }
        if (!is_writable(CSS)) {
            return $this->error(CSS . ' is not writeable, cannot compile.');
        }

        Configure::load('assets');
        $cssFiles = $this->_getCssFiles();
        $jsFiles = $this->_getJsFiles();

        $compressedCssFilename = $this->_compressCss($cssFiles);
        $compressedJsFilename = $this->_compressJs($jsFiles);
        $this->out("Combined and compressed CSS file: {$compressedCssFilename}");
        $this->out("Combined and compressed JS file: {$compressedJsFilename}");
        $this->out('Done');
    }

    /**
     * Compiles an array of the absolute paths to all JS files.
     * @return array
     */
    protected function _getJsFiles() {
        App::uses('View', 'View');
        App::import('Helper', 'Frontend.Frontend');
        $frontendHelper = new FrontendHelper(new View(null));

        $jsDependencies = array_merge(
            $frontendHelper->compileDependencies(),
            Configure::read('assets.js'),
            Configure::read('assets.jsFrontendPluginDependent')
        );

        $jsFiles = [];
        foreach ($jsDependencies as $jsFile) {
            if (substr($jsFile, -3) !== '.js') {
                $jsFile .= '.js';
            }
            // Plugin JS files
            if (substr($jsFile, 0, 1) == '/') {
                $parts = explode('/', $jsFile);
                $pluginName = Inflector::classify($parts[1]);
                $pluginPath = CakePlugin::path($pluginName);

                unset($parts[0], $parts[1]);
                $relPath = implode('/', $parts);
                $path = $pluginPath . 'webroot/' . $relPath;
                $jsFiles[] = $path;
            } else {
                $jsFiles[] = JS . $jsFile;
            }
        }

        return $jsFiles;
    }

    /**
     * Returns an array with the absolute paths of all CSS files to include.
     * @return array
     */
    protected function _getCssFiles() {
        $cssFiles = [];
        foreach (Configure::read('assets.css') as $cssFile) {
            if (substr($cssFile, -4) !== '.css') {
                $cssFile .= '.css';
            }
            if (substr($cssFile, 0, 1) == '/') {
                $cssFiles[] = WWW_ROOT . $cssFile;
            } else {
                $cssFiles[] = CSS . $cssFile;
            }
        }

        return $cssFiles;
    }

    /**
     * Combines and compresses the given js files
     *
     * @param array $jsFiles Array of absolute paths to JS files
     *
     * @return string            The full output file path.
     */
    protected function _compressJs(array $jsFiles) {
        $string = '';
        foreach ($jsFiles as $jsFile) {
            if (file_exists($jsFile)) {
                #$string.= "/* {$jsFile} */\n";
                $content = file_get_contents($jsFile);
                $string .= $content . "\n";
            } else {
                $this->error("JS dependency {$jsFile} doesn't exist!");
            }
        }
        $jsTmpFile = OLD_TMP . 'uncompressed_js.tmp.js';
        $outputFile = JS . 'app_build.js';

        if (file_put_contents($jsTmpFile, $string) !== false) {
            $this->out('Compressing JavaScript');
            $this->_runCompressor($jsTmpFile, $outputFile, 'js');
            $this->out('Done Compressing JavaScript');
            unlink($jsTmpFile);

            return $outputFile;
        } else {
            $this->error("JS temp file {$jsTmpFile} could not be written.");
        }
    }

    /**
     * Combines and compresses the given css files
     *
     * @param array $cssFiles Array of absolute paths to CSS files
     *
     * @return string            The full output file path.
     */
    protected function _compressCss(array $cssFiles) {
        $string = '';
        foreach ($cssFiles as $cssFile) {
            if (file_exists($cssFile)) {
                #$string.= "/* {$cssFile} */\n";
                $content = file_get_contents($cssFile);
                $string .= $content . "\n";
            } else {
                $this->error("CSS dependency {$cssFile} doesn't exist!");
            }
        }
        $cssTmpFile = OLD_TMP . 'uncompressed_css.tmp.css';
        $outputFile = CSS . 'app_build.css';

        if (file_put_contents($cssTmpFile, $string) !== false) {
            $this->out('Compressing CSS');
            $this->_runCompressor($cssTmpFile, $outputFile, 'css');
            $this->out('Done Compressing CSS');
            unlink($cssTmpFile);

            return $outputFile;
        } else {
            $this->error("CSS temp file {$cssTmpFile} could not be written.");
        }
    }

    /**
     * Runs the YUI compressor on the given $inputFile of type $assetType and
     * writes the output to $outputFile
     *
     * @param string $inputFile The input file name
     * @param string $outputFile The output file name
     * @param string $assetType 'css' or 'js'
     *
     * @return void
     */
    protected function _runCompressor($inputFile, $outputFile, $assetType) {
        $compressorPath = OLD_APP . 'Vendor/';
        echo system("java -jar {$compressorPath}yuicompressor-2.4.7.jar --type={$assetType} -o {$outputFile} {$inputFile}");
        #file_put_contents($outputFile, file_get_contents($inputFile));
    }
}