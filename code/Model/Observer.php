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

class Nls_Geckoboard_Model_Observer
{


    /**
     * Send notification when an order is placed
     * @param   Varien_Event_Observer $observer
     */
    public function sendOrderSuccessNotification($observer)
    {
      $event = $observer->getEvent();
      $order = $observer->getEvent()->getOrder();

      $items = $order->getItemsCollection();
      $str = "";
      foreach($items as $item) {
        $str .= $item->getName() . ' (' . $item->getSku() . ') <br />';
      }

      $data = array(
        "api_key" => $this->_apikey,
        "data" => array(
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
        )
      );
      $this->sendUpdate($data, '61242-310db7c7-f4b6-466c-baa0-f14d4876f44b');
    }


    /**
     * Generic curl call to Geckoboard
     * @param  string $message The message to send
     */
    public function sendUpdate($message, $widget)
    {
      $ch = curl_init($this->_baseurl.$widget);
      $call = curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT_MS => 1000,
        CURLOPT_POSTFIELDS => json_encode($message)
      ));
      curl_exec($ch);
      curl_close($ch);
    }
}
