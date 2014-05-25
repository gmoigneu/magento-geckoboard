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

class Nls_Geckoboard_Model_Geckoboard_Widgets_MonthlyTurnover extends Nls_Geckoboard_Model_Geckoboard_Widgets_Abstract
{
  public function getData() {

    $orderCollection = Mage::getModel('sales/order')->getCollection()
      ->addFieldToFilter('state', 'processing')
      ->addFieldToFilter('created_at', array('from' => date('Y-m-01') . ' 00:00:00', 'date' => true));

    $turnover = 0;

    foreach ($orderCollection as $order) {
      $turnover += $order->getGrandTotal();
    }

    $lastMonthOrderCollection = Mage::getModel('sales/order')->getCollection()
      ->addFieldToFilter('state', 'processing')
      ->addFieldToFilter('created_at', array('from' => date("Y-m-01", strtotime("first day of previous month")), 'to' => date("Y-m-t", strtotime("first day of previous month")), 'date' => true));

    $lastMonthTurnover = 0;

    foreach ($lastMonthOrderCollection as $order) {
      $lastMonthTurnover += $order->getGrandTotal();
    }

    $data = array(
      "item" => array(
        array(
          "text" => '',
          "value" => $turnover,
          "prefix" => '€'
        ),
        array(
          "text" => '',
          "value" => $lastMonthTurnover,
          "prefix" => '€'
        )
      )
    );

    return $data;

  }
}
