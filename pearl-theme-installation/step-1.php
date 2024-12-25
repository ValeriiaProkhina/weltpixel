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

$result = ['error' => true, 'msg' => 'unknown error occurred!'];
$directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
$appPath = $directory->getPath('app') . DIRECTORY_SEPARATOR;
$codeDir = 'code';
$codePath = $appPath . $codeDir . DIRECTORY_SEPARATOR;

if (!file_exists($codePath)) {
    $result['msg'] = 'Directory "' . $codeDir . '" does not exist under ' . $appPath;

    echo json_encode($result);
    die;
}

$nameSpaces = ['WeltPixel', 'WeSupply'];
$modulesList = 'Module(s):';
$mandatory = [
    'Backend',
    'FrontendOptions',
    'DesignElements',
    'CustomHeader',
    'CustomFooter',
    'ProductPage',
    'CategoryPage',
    'Command',
    'SampleData',
    'LazyLoading',
    'OwlCarouselSlider',
];
$modules = [];
$resultMsg = '';

try {
    $moduleManager = $objectManager->get('\Magento\Framework\Module\Manager');
    $modulesListManager = $objectManager->get('\Magento\Framework\Module\FullModuleList');
    $resourceInterface = $objectManager->get('\Magento\Framework\Module\ResourceInterface');

    // delete generated/code directory and its content
    $generatedDir = [
        $directory->getPath('generated') . DIRECTORY_SEPARATOR . 'code',
        $directory->getPath('generated') . DIRECTORY_SEPARATOR . 'metadata'
    ];

    $fileManager = $objectManager->get('\Magento\Framework\Filesystem\Io\File');
    foreach ($generatedDir as $dir) {
        $fileManager->rmdir($dir, true);
    }

    $isNewCount = 0;
    $modulesCount = 0;
    foreach ($nameSpaces as $space) {
        $spacePath = $codePath . $space;
        if (file_exists($spacePath) && is_dir($spacePath)) {
            $ignore = ['.', '..'];
            $directories = scandir($spacePath);

            foreach ($directories as $module) {
                if (in_array($module, $ignore)) {
                    continue;
                }
                $modulePath = $spacePath . DIRECTORY_SEPARATOR . $module;
                if (is_dir($modulePath)) {

                    $moduleName = $space . '_' . $module;

                    $isNew = '0';
                    if (
                        $modulesListManager->has($moduleName) &&
                        !$resourceInterface->getDataVersion($moduleName)
                    ) {
                        $isNew = '1';
                        $isNewCount++;
                    }

                    $isActive = '0';
                    if (
                        $moduleManager->isOutputEnabled($moduleName) &&
                        $moduleManager->isEnabled($moduleName)
                    ) {
                        $isActive = '1';
                    }

                    $modulesList .= ' ' . $space . '_' . $module . ',';
                    $modules[$module] = [
                        'isNew' => $isNew,
                        'active' => $isActive,
                        'selectable' => in_array($module, $mandatory) ? '1' : '0',
                        'name' => $space == 'WeSupply' ? str_replace('_', ' ', $moduleName) : $module,
                        'value' => $moduleName
                    ];
                }
                $modulesCount++;
            }
        }
    }

    // move WeltPixel_Backend module at the top of array
    if (array_key_exists('Backend', $modules)) {
        $backend = $modules['Backend'];
        unset($modules['Backend']);
        $modules = array_values($modules);
        array_unshift($modules, $backend);
    }

    if (!$modules) {
        $result['msg'] = 'We couldn\'t find any module(s) uploaded under ' . $codePath;

        echo json_encode($result);
        die;
    }

    $allNew = $isNewCount == $modulesCount ? '1' : '0';

    $result['error'] = false;
    $result['msg'] = $modulesList;
    $result['modules'] = $modules;
    $result['allNew'] = $allNew;

} catch (Exception $ex) {
    $result['msg'] = $ex->getMessage();
    $result['error'] = true;
}

echo json_encode($result);
die;