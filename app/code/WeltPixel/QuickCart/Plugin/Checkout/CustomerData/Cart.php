<?php

namespace WeltPixel\QuickCart\Plugin\Checkout\CustomerData;

use WeltPixel\QuickCart\Helper\Data as QuickCartHelper;
use Magento\Framework\View\LayoutInterface;

class Cart
{
    /**
     * @var QuickCartHelper
     */
    protected $quickCartHelper;

    /**
     * @var LayoutInterface
     */
    protected $layout;

    /**
     * @param QuickCartHelper $quickCartHelper
     * @param LayoutInterface $layout
     */
    public function __construct(
        QuickCartHelper $quickCartHelper,
        LayoutInterface $layout
    ) {
        $this->quickCartHelper = $quickCartHelper;
        $this->layout = $layout;
    }

    /**
     * @param \Magento\Checkout\CustomerData\Cart $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, $result)
    {
        if (!$this->quickCartHelper->quicartIsEnabled()) {
            return $result;
        }

        $quickCartMessageEnabled = false;
        $quickCartMessageContent = '';
        if ($this->quickCartHelper->isQuickCartMessageEnabled()) {
            $quickCartMessageEnabled = true;
            $quickCartMessageContent = $this->quickCartHelper->getQuickCartMessageContentForDisplay();
        }

        $result['weltpixel_quickcart_message_enabled'] = $quickCartMessageEnabled;
        $result['weltpixel_quickcart_message_content'] = $quickCartMessageContent;

        if ($this->quickCartHelper->isCMSCsutomBlockEnabled()) {
            $cmsBlockContent = $this->layout
                ->createBlock('Magento\Cms\Block\Block')
                ->setBlockId($this->quickCartHelper->getCMSCustomBlockIdentifier())
                ->toHtml();

            $result['weltpixel_quickcart_cmsblock'] = $cmsBlockContent;
        }

        return $result;
    }
}
