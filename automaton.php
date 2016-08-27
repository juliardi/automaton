<?php

use automaton\HostGenerator;
use automaton\VhostGenerator;

require_once 'vendor/autoload.php';

if (trim(shell_exec('whoami')) != 'root') {
    echo "Must be root!\n";
    exit(1);
}

$dotenv = new Dotenv\Dotenv(dirname(__FILE__));
$dotenv->load();

$path_not_found = true;

$server_name = readline('Input virtual host server name : ');
while ($path_not_found) {
    $document_root = readline('Input document root path : ');
    if (file_exists($document_root)) {
        $path_not_found = false;
    } else {
        echo "Path not found. Try again!\n";
    }
}

$sure = readline("Are you sure to create '$server_name' for '$document_root' ? (Y/N) : ");

if ($sure == 'Y') {
    $vhost = new VhostGenerator([
        'template_dir' => __DIR__.DIRECTORY_SEPARATOR.getenv('TEMPLATE_DIR'),
        'apache_conf_file' => getenv('APACHE_CONF_FILE'),
        'vhost_conf_dir' => getenv('VHOST_CONF_DIR'),
        'log_dir' => getenv('LOG_DIR'),
        'server_name' => $server_name,
        'document_root' => $document_root,
    ]);
    $vhost->run();

    $host_ip = readline('Input host ip : ');
    $host_name = readline('Input host name : ');
    $host_file = getenv('HOST_FILE');

    $sure = readline("Are you sure to add '$host_ip' as '$host_name' to '$host_file' ? (Y/N) : ");

    if ($sure == 'Y') {
        $hostGen = new HostGenerator(compact([
            'host_ip', 'host_name', 'host_file',
        ]));

        $hostGen->run();
    }
}

function readline($prompt)
{
    echo $prompt;
    $result = rtrim(fgets(STDIN));

    return $result;
}
