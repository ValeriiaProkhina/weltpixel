<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

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
$storeRepository = $objectManager->get('\Magento\Store\Model\StoreRepository');
$helperBackend = $objectManager->get('\Magento\Backend\Helper\Data');
$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
$state = $objectManager->get('\Magento\Framework\App\State');

$homeUrl = $storeManager->getStore()->getBaseUrl();
$backendUrl = $helperBackend->getHomePageUrl();

$stores = $storeRepository->getList();
$storeList = array(
        "GLOBAL" => "GLOBAL"
);
foreach ($stores as $store) {
    $storeId = $store["code"];
    $storeName = $store["name"];
    $storeList[$storeId] = $storeName;
}
unset($storeList['admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Pearl Theme Installation">
    <meta name="author" content="WeltPixel">

    <title>Pearl Theme Installation</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <!-- Import Open Sans font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="jumbotron">
    <div class="container separator nopadding">
        <h1 class="text-uppercase no-mg-bottom big-title">Pearl. Theme Instalation.</h1>
        <p class="sub-title">This is a simple GUI wizard for the Pearl Theme installation and configuration.</p>
    </div>
</div>

<?php if ($state->getMode() === \Magento\Framework\App\State::MODE_PRODUCTION) : ?>
<div class="container nopadding">
    <div class="row">
        <div class="col-xs-12">
            <p class="notification-msg">The GUI is not working on production mode. <br/>
                Please set your magento installation to developer mode.</p>
        </div>
    </div>
</div>
<?php else:  ?>
<div class="container nopadding">
    <div class="row">
        <div class="col-xs-12">
            <p class="notification-msg">Please make sure that you have copied the source files and the sample data to your magento installation.</p>
        </div>
    </div>
    <!-- Example row of columns -->
    <div class="row step-descr">
        <div class="col-md-4">
            <h2>Step 1</h2>
            <p>In this step we make sure the Pearl Theme installation requirements are met.</p>
        </div>
        <div class="col-md-4">
            <h2>Step 2</h2>
            <p>In this step we run the upgrade scripts, clear the Magento cache and run indexes.</p>
        </div>
        <div class="col-md-4">
            <h2>Step 3</h2>
            <p>In this step we clear the cache and regenerate the theme specific less files. </p>
        </div>
    </div>
    <div class="row step-descr row-2">
        <div class="col-md-4">
            <h2>Step 4</h2>
            <p>In this step the Pearl theme can be activated for a desired store view, or globally, using the GLOBAL input. </p>
        </div>
        <div class="col-md-4">
            <h2>Step 5</h2>
            <p>In this step you can configure your store, import predefined demo versions.</p>
        </div>
        <div class="col-md-4">
            <h2>Step 6</h2>
            <p>In this step you can configure your store, using different configuration options.</p>
        </div>
    </div>
    <div class="row step-descr row-3">
        <div class="center-aligned col-md-4">
            <h2>Step 7</h2>
            <p>In this step we cleanup the cache and generate the theme specific files after the customizations.</p>
        </div>
    </div>


    <div class="container mg-top-sec-2">

        <div class="stepwizard col-xs-12">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step">
                    <a href="#step-1" type="button" class="btn btn-primary btn-circle step-1-link">1</a>
                    <p>Step 1</p>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                    <p>Step 2</p>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
                    <p>Step 3</p>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-4" type="button" class="btn btn-default btn-circle" disabled="disabled">4</a>
                    <p>Step 4</p>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-5" type="button" class="btn btn-default btn-circle" disabled="disabled">5</a>
                    <p>Step 5</p>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-6" type="button" class="btn btn-default btn-circle" disabled="disabled">6</a>
                    <p>Step 6</p>
                </div>
                <div class="stepwizard-step">
                    <a href="#step-7" type="button" class="btn btn-default btn-circle" disabled="disabled">7</a>
                    <p>Step 7</p>
                </div>
                <div class="stepwizard-step" style="display: none">
                    <a href="#step-8" type="button" class="btn btn-default btn-circle" disabled="disabled">8</a>
                    <p>Step 8</p>
                </div>
            </div>
        </div>

        <form role="form" action="" method="post">
            <div class="row setup-content" id="step-1">
                <div class="col-xs-12 col-md-7 col-md-offset-3">
                    <div class="col-md-12 title-cus">
                        <h3>Installation requirements check</h3>
                        <div class="content">
                            <p>This step will make sure the Pearl Theme installation requirements are met.</p>
                        </div>
                        <div class="text-center btn-wrapper">
                            <button class="btn btn-primary nextBtn btn-lg text-uppercase btn-proc-1" data-loading-text="<i class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></i> Processing" type="button" >Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row setup-content" id="step-2">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="col-md-12 title-cus">
                        <h3>Install Pearl Theme and selected extensions</h3>
                        <div class="content">
                            <p>This step will clean cache and run the upgrades for the selected modules.</p>
                            <ul id="modules_list"></ul>
                        </div>
                        <div class="text-center btn-wrapper">
                            <button class="btn btn-primary nextBtn btn-lg text-uppercase btn-proc-1" data-loading-text="<i class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></i> Processing" type="button" >Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row setup-content" id="step-3">
                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <div class="col-md-12 title-cus">
                        <h3>Theme specific less generation</h3>
                        <div class="content">
                            This step will clean cache and generate the theme specific less files.
                        </div>
                        <div class="checkbox text-center">
                            <label>
                                <input type="checkbox" name="skip" id="skip-step-3">Skip this step
                            </label>
                        </div>
                        <div class="text-center btn-wrapper">
                            <button class="btn btn-primary nextBtn btn-lg text-uppercase" data-loading-text="<i class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></i> Processing" type="button" >Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row setup-content" id="step-4">
                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <div class="col-md-12 title-cus">
                        <h3>Theme Activation</h3>
                        <div class="content">
                            Here you can activate the Pearl Theme for your store. If you don't want to activate it, you can skip this step. <br/><br/>
                            If you want to set the theme for all markets use <b>GLOBAL</b> otherwise specify the store code or store id.
                        </div>
                        <div class="form-group">
                            <label class="control-label">Store Code</label>
                            <select  name="theme-activation-store-code" id="theme-activation-store-code" class="form-control" required="required">
                                <?php foreach ($storeList as $code => $storeName) : ?>
                                    <option value="<?php echo $code ?>"><?php echo $storeName ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="checkbox text-center">
                            <label>
                                <input type="checkbox" name="skip" id="skip-step-4">Skip this step
                            </label>
                        </div>
                        <div class="text-center btn-wrapper">
                            <button class="btn btn-primary nextBtn btn-lg text-uppercase" data-loading-text="<i class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></i> Processing" type="button" >Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row setup-content" id="step-5">
                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <div class="col-md-12 title-cus">
                        <h3>Import Demo Configuration</h3>
                        <div class="form-group">
                            <label class="control-label">Store Code</label>
                            <select  name="demo-configuration-store-code" id="demo-configuration-store-code" class="form-control" required="required">
                                <?php foreach ($storeList as $code => $storeName) : ?>
                                    <option value="<?php echo $code ?>"><?php echo $storeName ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Demo version</label>
                            <select class="form-control" id="demo-configuration-version" name="demo-configuration-version" required="required">
                                <option value="v1">v1</option>
                                <option value="v2">v2</option>
                                <option value="v3">v3</option>
                                <option value="v4">v4</option>
                                <option value="v5">v5</option>
                                <option value="v6">v6</option>
                                <option value="v7">v7</option>
                                <option value="v8">v8</option>
                                <option value="v9">v9</option>
                                <option value="v10">v10</option>
                                <option value="v11">v11</option>
                                <option value="v12">v12</option>
                                <option value="v14">v14</option>
                                <option value="v15">v15</option>
                            </select>
                        </div>
                        <div class="checkbox text-center">
                            <label>
                                <input type="checkbox" name="skip" id="skip-step-5">Skip this step
                            </label>
                        </div>
                        <div class="text-center btn-wrapper">
                            <button class="btn btn-primary nextBtn btn-lg text-uppercase" data-loading-text="<i class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></i> Processing" type="button" >Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row setup-content" id="step-6">
                <div class="col-xs-12 col-md-3 col-lg-3">
                    <div class="col-md-12">
                        <h3>Design Options</h3>
                        <div class="form-group">
                            <label class="control-label">Store Code</label>
                            <select  name="theme-configuration-store-code" id="theme-configuration-store-code" class="form-control" required="required">
                                <?php foreach ($storeList as $code => $storeName) : ?>
                                    <option value="<?php echo $code ?>"><?php echo $storeName ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Header Version</label>
                            <select class="form-control" id="theme-configuration-header" name="theme-configuration-header" required="required">
                                <option value="">-- Please Select --</option>
                                <option value="v1">v1</option>
                                <option value="v2">v2</option>
                                <option value="v3">v3</option>
                                <option value="v4">v4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Home Page Version</label>
                            <select class="form-control" id="theme-configuration-home-page" name="theme-configuration-home-page" required="required">
                                <option value="">-- Please Select --</option>
                                <option value="v1">v1</option>
                                <option value="v2">v2</option>
                                <option value="v3">v3</option>
                                <option value="v4">v4</option>
                                <option value="v5">v5</option>
                                <option value="v6">v6</option>
                                <option value="v7">v7</option>
                                <option value="v8">v8</option>
                                <option value="v9">v9</option>
                                <option value="v10">v10</option>
                                <option value="v11">v11</option>
                                <option value="v12">v12</option>
                                <option value="v14">v14</option>
                                <option value="v15">v15</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Enable Pre-Footer</label>
                            <select class="form-control" id="theme-configuration-prefooter" name="theme-configuration-prefooter" required="required">
                                <option value="">-- Please Select --</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Footer Version</label>
                            <select class="form-control" id="theme-configuration-footer" name="theme-configuration-footer" required="required">
                                <option value="">-- Please Select --</option>
                                <option value="v1">v1</option>
                                <option value="v2">v2</option>
                                <option value="v3">v3</option>
                                <option value="v4">v4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Category Page Columns</label>
                            <select class="form-control" id="theme-configuration-category-columns" name="theme-configuration-category-columns" required="required">
                                <option value="">-- Please Select --</option>
                                <option value="2columns">2columns</option>
                                <option value="3columns">3columns</option>
                                <option value="4columns">4columns</option>
                                <option value="5columns">5columns</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Product Page Version</label>
                            <select class="form-control" id="theme-configuration-product-page" name="theme-configuration-product-page" required="required">
                                <option value="">-- Please Select --</option>
                                <option value="v1">v1</option>
                                <option value="v2">v2</option>
                                <option value="v3">v3</option>
                                <option value="v4">v4</option>
                            </select>
                        </div>
                        <div class="checkbox text-center">
                            <label>
                                <input type="checkbox" name="skip" id="skip-step-6">Skip this step
                            </label>
                        </div>
                        <div class="text-center btn-wrapper title-cus">
                            <button class="btn btn-primary nextBtn btn-lg text-uppercase mg-btm-step-5" data-loading-text="<i class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></i> Processing" type="button" >Proceed</button>
                        </div>
                    </div>
                </div>
                <div class="preview col-xs-12 col-md-9 col-lg-9">
                    <div class="image-container preview-header">
                        <img src="img/frame-center.png" />
                    </div>
                    <div class="preview-images">
                        <div class="image-container theme-configuration-header"><p>Header will preview here once selected from drop-down</p></div>
                        <div class="image-container page-preview"><p>Page body will preview here once selected from drop-down</p></div>
                        <div class="image-container theme-configuration-prefooter"><p>Pre-Footer will preview here once selected from drop-down</p></div>
                        <div class="image-container theme-configuration-footer"><p>Footer will preview here once selected from drop-down</p></div>
                    </div>
                    <div class="preview-footer"></div>
                </div>
            </div>
            <div class="row setup-content" id="step-7">
                <div class="col-xs-12 col-md-6 col-md-offset-3">
                    <div class="col-md-12">
                        <h3>Cache cleanup</h3>
                        <div class="content">
                            <p>In this step we cleanup the cache and generate the theme specific files after the customizations.</p>
                            <p><b>Don't skip this test if you made configuration changes.</b></p>
                        </div>
                        <h3>Installer removal options</h3>
                        <div class="form-group">
                            <p>At the end of the installation this GUI should be removed in order to prevent unauthorized access.</p>
                            <label class="control-label">Remove GUI files after installation</label>
                            <select class="form-control" id="delete-installer" name="delete-installer" required="required">
                                <option value="1">Yes (Recommended)</option>
                                <option value="0">No, I will manually remove GUI files</option>
                            </select>
                        </div>
                        <div class="checkbox text-center">
                            <label>
                                <input type="checkbox" name="skip" id="skip-step-7">Skip this step
                            </label>
                        </div>
                        <div class="text-center btn-wrapper title-cus">
                            <button class="btn btn-primary nextBtn btn-lg text-uppercase" data-loading-text="<i class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></i> Processing" type="button" >Proceed</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row setup-content" id="step-8">
                <div class="col-xs-6 col-md-offset-3">
                    <div class="col-md-12">
                        <div class="success-container">
                            <p class="congratulations">Congratulations!</p>
                            <p>Theme was installed and configured successfully.</p>
                            <a class="btn btn-primary succesBtn btn-lg text-uppercase" target="_blank" href="<?php echo $homeUrl ?>">Go to Homepage</a>
                            <a class="btn btn-primary succesBtn btn-lg text-uppercase admin-btn" target="_blank" href="<?php echo $backendUrl ?>">Go to Admin</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" >
                <h3 class="log-title">Installation log</h3>
                <div class="row result-container" >
                </div>
            </div>
        </form>

    </div>

    <footer>
        <p class="copyright">&copy; <?php echo date("Y"); ?> WeltPixel</p>
    </footer>
</div> <!-- /container -->
<?php endif; ?>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
