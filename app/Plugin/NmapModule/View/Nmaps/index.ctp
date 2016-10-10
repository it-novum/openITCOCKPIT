<?php
//Print results
foreach ($hosts as $key => $host) {
    echo '<br>';
    echo '<b>Hostname: ' . $host->getHostname() . "</b><br>\n";
    echo 'Address: ' . $host->getAddress() . "<br>\n";
    echo 'OS: ' . $host->getOS() . "<br>\n";
    echo 'Status: ' . $host->getStatus() . "<br>\n";
    $services = $host->getServices();
    echo 'Number of discovered services: ' . count($services) . "<br>\n";
    foreach ($services as $key => $service) {
        echo "<br>";
        echo 'Service Name: ' . $service->name . "<br>\n";
        echo 'Port: ' . $service->port . "<br>\n";
        echo 'Protocol: ' . $service->protocol . "<br>\n";
        echo 'Product information: ' . $service->product . "<br>\n";
        echo 'Product version: ' . $service->version . "<br>\n";
        echo 'Product additional info: ' . $service->extrainfo . "<br>\n";
    }
}