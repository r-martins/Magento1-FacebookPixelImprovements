<?php
/**
 * @author: Ricardo Martins
 * Date: 11/07/17
 */ 
class RicardoMartins_FacebookPixelImprovements_Model_Facebookpixel_Observer
    extends Remmote_Facebookpixel_Model_Observer
{
    /**
     * Set a flag in session when a product is added to the cart
     * @param  [type]     $observer
     * @return [type]
     * @author edudeleon
     * @date   2016-10-12
     */
    public function logPixelAddToCart($observer) {

        $product = $observer->getProduct();
        //Logging event
        Mage::getModel('core/session')->setPixelAddToCart(true);
        Mage::getModel('customer/session')->setLastAddedProductFinalPrice($product->getFinalPrice());
    }
}