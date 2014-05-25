<?php
/**
 * The MIT License (MIT) *
 *
 * Copyright (c) 2014 Guillaume Moigneu *
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions: *
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software. *
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @category    Nls
 * @package     Nls_Geckoboard
 * @copyright   Copyright (c) 2014 nls.io
 * @license     The MIT License (MIT)
 */

class Nls_Geckoboard_Model_Geckoboard_Widgets_LastOrder extends Nls_Geckoboard_Model_Geckoboard_Widgets_Abstract
{
  public function getData() {

    $status = explode(',', Mage::getStoreConfig('nlsgeckoboard/geckoboard/order_status', Mage::app()->getStore()));

    $ordersCollection = Mage::getModel('sales/order')->getCollection()
      ->addFieldToFilter('state', 'processing')
      ->addFieldToFilter('created_at', array('from' => date('Y-m-d') . ' 00:00:00', 'date' => true))
      ->addAttributeToSort('created_at', 'DESC')
      ->addFieldToFilter('status', array('in' => $status))
      ->setPageSize(1);

    $order = $ordersCollection->getFirstItem();

    $items = $order->getItemsCollection();
    $str = "";
    foreach($items as $item) {
      $str .= $item->getName() . ' (' . $item->getSku() . ') <br />';
    }

    $data = array(
      "item" => array(
        "text" =>  __("#%s à %s<br />Total : %s<br />%s<br />Merci à %s de %s !",
          $order->getIncrementId(),
          $order->getCreatedAt(),
          $order->getGrandTotal().' '.$order->getOrderCurrencyCode(),
          $str,
          $order->getCustomerFirstname().' '.$order->getCustomerLastname(),
          $order->getBillingAddress()->getCity()
        )
      )
    );

    return $data;

  }
}
