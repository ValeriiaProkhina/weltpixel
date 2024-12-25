<?php
set_time_limit(0);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

$result = array(
    'msg' => '',
    'error' => false
);


try {
    require $bootstrapPath;
} catch (\Exception $e) {
    echo <<<HTML
<div style="font:12px/1.35em arial, helvetica, sans-serif;">
    <div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;">
        <h3 style="margin:0;font-size:1.7em;font-weight:normal;text-transform:none;text-align:left;color:#2f2f2f;">
        Autoload error</h3>
    </div>
    <p>{$e->getMessage()}</p>
</div>
HTML;
    exit(1);
}

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();

$cli = $objectManager->get('WeltPixel\Command\Model\Console\Cli');
$cli->setAutoExit(false);

$applicationName = 'Pearl Installation';
$commands = [
    'weltpixel:less:generate' => [],
    'cache:clean' => [],
    'weltpixel:cleanup' => []
];

$resultMsg = '';

try {
    foreach ($commands as $command => $params) {
        $argvInputParams = [$applicationName, $command];
        foreach ($params as $param) {
            $argvInputParams[] = $param;
        }

        $input = new \Symfony\Component\Console\Input\ArgvInput($argvInputParams);

        $output = new Symfony\Component\Console\Output\BufferedOutput();
        $cli->run($input, $output);

        $content = $output->fetch();

        $resultMsg .= str_replace(PHP_EOL, "<br/>", $content);
    }

    if (isset($_POST['deleteInstaller']) && $_POST['deleteInstaller'] == '1') {
        $filesToDelete = [
            'index.php',
            'step-1.php',
            'step-2.php',
            'step-3.php',
            'step-4.php',
            'step-5.php',
            'step-6.php',
        ];
        foreach ($filesToDelete as $file) {
            shell_exec('rm ' . __DIR__ . '/' . $file);
        }

        shell_exec('mv ' . __DIR__ . '/index.php.noinstall ' . __DIR__ . '/index.php');

        $resultMsg .= 'This GUI wizard has been deleted.';
    }

    $result['msg'] = $resultMsg;
} catch (Exception $ex) {
    $result['msg'] = $ex->getMessage();
    $result['error'] = true;
}

if (strpos($result['msg'], 'Exception') !== false) {
    $result['error'] = true;
}


echo json_encode($result);
die;
