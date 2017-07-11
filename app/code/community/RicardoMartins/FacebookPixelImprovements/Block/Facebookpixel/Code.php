<?php
/**
 * @author: Ricardo Martins
 * Date: 11/07/17
 */ 
class RicardoMartins_FacebookPixelImprovements_Block_Facebookpixel_Code
    extends Remmote_Facebookpixel_Block_Code
{
    /**
     * Return View Content event track with price
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    public function getViewContentEvent(){
        $pageSection = $this->_getSection();

        //Check if event is enabled
        if(Mage::helper('remmote_facebookpixel')->viewContentEnabled()){
            if($pageSection == 'catalog_product_view'){
                $extra = $this->getPriceInfo();
                $extra = json_encode($extra);
                return "fbq('track', 'ViewContent', $extra);";
            }
        }
    }

    /**
     * Return AddToCart event track with price
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    public function getAddToCartEvent(){
        //Check if event is enabled
        if(Mage::helper('remmote_facebookpixel')->addToCartEnabled()){

            $pixelEvent = Mage::getModel('core/session')->getPixelAddToCart();
            if($pixelEvent){
                //Unset event
                Mage::getModel('core/session')->unsPixelAddToCart();
                $price = Mage::getModel('customer/session')->getLastAddedProductFinalPrice();
                $extra = $this->getPriceInfo(false, $price);
                $extra = json_encode($extra);
                Mage::getModel('customer/session')->unsetData('last_added_product_final_price');
                return "fbq('track', 'AddToCart', $extra);";
            }
        }
    }

    /**
     * @param bool|false $product
     *
     * @param bool       $price
     *
     * @return array
     */
    protected function getPriceInfo($product = false, $price = false)
    {
        $product = !$product ? Mage::registry('current_product') : $product;
        $currency = Mage::app()->getStore()->getCurrentCurrencyCode();
        $extra = array(
            'value' => !$price ? $product->getFinalPrice() : $price,
            'currency' => $currency
        );
        return $extra;
    }

    /**
     * Get store current section
     * @return [type]
     * @author edudeleon
     * @date   2016-10-11
     */
    private function _getSection(){
        $pageSection  = Mage::app()->getFrontController()->getAction()->getFullActionName();
        return  $pageSection;
    }
}