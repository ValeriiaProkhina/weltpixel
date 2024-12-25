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


if (!isset($_POST['storeCode'])) {
    $result['error'] = true;
    $result['msg'] = 'Please specify the store code!';
    echo json_encode($result);
    die;
}

if (!isset($_POST['homePage'])) {
    $result['error'] = true;
    $result['msg'] = 'Please specify the Home Page version!';
    echo json_encode($result);
    die;
}

if (!isset($_POST['header'])) {
    $result['error'] = true;
    $result['msg'] = 'Please specify the Header version!';
    echo json_encode($result);
    die;
}

if (!isset($_POST['categoryColumns'])) {
    $result['error'] = true;
    $result['msg'] = 'Please specify the Category Page columns!';
    echo json_encode($result);
    die;
}

if (!isset($_POST['productVersion'])) {
    $result['error'] = true;
    $result['msg'] = 'Please specify the Product Page version!';
    echo json_encode($result);
    die;
}

if (!isset($_POST['preFooter'])) {
    $result['error'] = true;
    $result['msg'] = 'Please specify if enable Pre-footer or not!';
    echo json_encode($result);
    die;
}

if (!isset($_POST['footer'])) {
    $result['error'] = true;
    $result['msg'] = 'Please specify the Footer version!';
    echo json_encode($result);
    die;
}

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();

$cli = $objectManager->get('WeltPixel\Command\Model\Console\Cli');
$cli->setAutoExit(false);

$applicationName = 'Pearl Installation';
$commands = [
    'weltpixel:theme:configurator' => [
        '--store='.$_POST['storeCode'],
        '--homePage='.$_POST['homePage'],
        '--header='.$_POST['header'],
        '--categoryPage='.$_POST['categoryColumns'],
        '--productPage='.$_POST['productVersion'],
        '--preFooter='.$_POST['preFooter'],
        '--footer='.$_POST['footer'],
    ],
    'cache:clean' => []
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
