<?php

namespace WeltPixel\SpeedOptimization\Block\Html\Head;

use WeltPixel\SpeedOptimization\Helper\Data as SpeedHelper;
use WeltPixel\SpeedOptimization\Model\FileManager as BundleFileManager;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\RequireJs\Config as RequireJsConfig;
use Magento\Framework\App\State as AppState;
use Magento\Framework\View\Element\Context as ViewElementContext;
use Magento\Framework\View\Page\Config as PageConfig;
use Magento\RequireJs\Model\FileManager;

class Config extends \Magento\Framework\View\Element\AbstractBlock
{
    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var PageConfig
     */
    private $pageConfig;

    /**
     * @var AppState
     */
    private $appState;

    /**
     * @var DirectoryList
     */
    private $dir;

    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * @var SpeedHelper
     */
    private $speedHelper;

    /**
     * Config constructor.
     * @param ViewElementContext $context
     * @param AppState $appState
     * @param BundleFileManager $fileManager
     * @param PageConfig $pageConfig
     * @param DirectoryList $dir
     * @param HttpRequest $httpRequest
     * @param SpeedHelper $speedHelper
     * @param array $data
     */
    public function __construct(
        ViewElementContext $context,
        AppState $appState,
        BundleFileManager $fileManager,
        PageConfig $pageConfig,
        DirectoryList $dir,
        HttpRequest $httpRequest,
        SpeedHelper $speedHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->fileManager = $fileManager;
        $this->pageConfig = $pageConfig;
        $this->appState = $appState;
        $this->dir = $dir;
        $this->httpRequest = $httpRequest;
        $this->speedHelper = $speedHelper;
    }

    /**
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        if (!$this->speedHelper->isEnabled() || $this->appState->getMode() === AppState::MODE_DEVELOPER) {
            return parent::_prepareLayout();
        }

        $staticDir = $this->dir->getPath('static');
        $fullActionName = $this->httpRequest->getFullActionName();

        $assetCollection = $this->pageConfig->getAssetCollection();

        $shared = $this->fileManager->createSharedJsBundleAsset();
        $sharedBundleRelPath = $shared->getFilePath();
        $sharedBundleAbsPath = $staticDir . "/" . $sharedBundleRelPath;

        if (file_exists($sharedBundleAbsPath)) {
            $assetCollection->insert(
                $sharedBundleRelPath,
                $shared,
                RequireJsConfig::REQUIRE_JS_FILE_NAME
            );
        }

        $bundleConfig = $this->fileManager->createJsBundleAsset($fullActionName);
        $pageSpecificBundleRelPath = $bundleConfig->getFilePath();
        $pageSpecificBundleAbsPath = $staticDir . "/" . $pageSpecificBundleRelPath;

        if (file_exists($pageSpecificBundleAbsPath)) {
            $assetCollection->insert(
                $pageSpecificBundleRelPath,
                $bundleConfig,
                $sharedBundleRelPath
            );
        }

        return parent::_prepareLayout();
    }
}
