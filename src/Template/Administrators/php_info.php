<!-- php info -->
<div id="phpinfo">
    <?php
    ob_start();
    phpinfo();
    $phpinfo = ob_get_contents();
    ob_end_clean();
    $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
    echo "
    <style type='text/css'>
        #phpinfo {}
        #phpinfo pre {margin: 0; font-family: monospace;}
        #phpinfo a:link {color: #009; text-decoration: none; background-color: #fff;}
        #phpinfo a:hover {text-decoration: underline;}
        #phpinfo table {border-collapse: collapse; border: 0; width: 100%; box-shadow: 1px 2px 3px #f6f6f6;}
        #phpinfo .center {text-align: center;}
        #phpinfo .center table {margin: 1em auto; text-align: left;}
        #phpinfo .center th {text-align: center !important;}
        #phpinfo td, th {border: 1px solid #eee; font-size: 0.8125rem; vertical-align: baseline; padding: 4px 5px;}
        #phpinfo h1 {font-size: 150%;}
        #phpinfo h2 {font-size: 125%;}
        #phpinfo .p {text-align: left;}
        #phpinfo .e {color:#000;width: 300px; font-weight: bold;}
        #phpinfo .h {font-weight: bold;}
        #phpinfo .v {color: #000;max-width: 300px; overflow-x: auto; word-wrap: break-word;}
        #phpinfo .v i {color: #000;}
        #phpinfo img {float: right; border: 0;}
        #phpinfo hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
        #phpinfo tbody tr:nth-of-type(odd) {background-color: #FBFCFC !important;}
        #phpinfo tbody tr:nth-of-type(even) {background-color: #FFFFFF !important;}

        /* Dark Theme */
        body.dark-theme {
            #phpinfo table {box-shadow: 1px 2px 3px rgba(11, 81, 197, 0.13);}
            #phpinfo tbody tr:nth-of-type(odd) {background-color: #212631 !important;}
            #phpinfo tbody tr:nth-of-type(even) {background-color: #323640 !important;}
            #phpinfo td, th {border: 1px solid #51555e;}
            #phpinfo .e {color:#e3e4e5;}
            #phpinfo .v {color: #e3e4e5;}
            #phpinfo .v i {color: #e3e4e5;}
        }

    </style>
    <div id='phpinfo'>
        $phpinfo
    </div>
    ";
    ?>
</div>
