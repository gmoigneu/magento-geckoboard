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

class Nls_Geckoboard_Model_Geckoboard_Client
{
  /**
   * @var \Guzzle\Http\Client
   */
  protected $client;

  /**
   * @var string
   */
  protected $apiKey;

  /**
   * @var string
   */
  protected $baseUrl;

  /**
   * @var array
   */
  protected $widgets;

  /**
   * Construct a new Geckoboard Client
   */
  public function __construct()
  {
      $this->_apiKey = Mage::getStoreConfig('nlsgeckoboard/geckoboard/apikey', Mage::app()->getStore());
      $this->_baseUrl = Mage::getStoreConfig('nlsgeckoboard/geckoboard/baseurl', Mage::app()->getStore());
      $this->_widgets = array('DailyTurnover', 'MonthlyTurnover', 'OrdersCount', 'OrdersPendingCount', 'LastOrder', 'Subscribers');
  }

  public function update()
  {
    foreach($this->_widgets as $widget)
    {
      // Check if widget has an ID
      $id = Mage::getStoreConfig('nlsgeckoboard/geckoboard_widgets/'.strtolower($widget).'_widget', Mage::app()->getStore());
      if(strlen($id) > 2)
      {
        $class = 'Nls_Geckoboard_Model_Geckoboard_Widgets_'.$widget;
        if(class_exists($class)) {
          $this->push($id, new $class);
        }
      }
    }
  }

  /**
   * Send the widget info to Geckboard
   *
   * @param Widget $widget
   * @return $this
   */
  private function push($id, $widget)
  {
      $message = array(
          'api_key' => $this->_apiKey,
          'data' => $widget->getData()
      );

      $ch = curl_init($this->_baseUrl . $id);
      $call = curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT_MS => 1000,
        CURLOPT_POSTFIELDS => json_encode($message)
      ));
      curl_exec($ch);
      curl_close($ch);
  }
}
