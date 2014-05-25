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

class Nls_Geckoboard_Model_Geckoboard_Widgets_OrdersCount extends Nls_Geckoboard_Model_Geckoboard_Widgets_Abstract
{
  public function getData() {

    // Get status
    $status = explode(',', Mage::getStoreConfig('nlsgeckoboard/geckoboard/order_status', Mage::app()->getStore()));

    $ordersCollection = Mage::getModel('sales/order')->getCollection()
      ->addFieldToFilter('created_at', array('from' => date('Y-m-d') . ' 00:00:00', 'date' => true))
      ->addFieldToFilter('status', array('in' => $status));

    $orders = $ordersCollection->getSize();

    $week = array();
    $ordersCollection = Mage::getModel('sales/order')->getCollection()
      ->addFieldToFilter('created_at', array('from' => date('Y-m-d', strtotime('-7 day')), 'date' => true))
      ->addFieldToFilter('status', array('in' => $status));


    $date = new Zend_Date();
    $date->addDay(-7);
    for($i = 0; $i < 7; $i++) {
      $week[$date->toString(Zend_Date::DAY_OF_YEAR) + $i] = 0;
    }

    foreach ($ordersCollection as $order) {
      $orderDate = new Zend_Date($order->getCreatedAt(), Zend_Date::ISO_8601);
      $orderDate = $orderDate ->toString(Zend_Date::DAY_OF_YEAR);
      
      if(!isset($week[$orderDate])) {
        $week[$orderDate] = 1;
      } else {
        $week[$orderDate] += 1;
      }
    }


    $data = array(
      "item" => array(
        array(
          "text" => '',
          "value" => $orders,
          "prefix" => ''
        ),
        array_values($week)
      )
    );

    return $data;

  }
}
