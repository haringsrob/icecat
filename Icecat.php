<?php
/**
 * Icecat Class
 * Author: Harings Rob
 * Homepage: http://harings.be
 * version: 1.1
 * Icecat datagrabber communications class
 */
namespace Icecat\Icecat;

class Icecat
{

  /*
   * Our custom variables.
   */
  private $username;
  private $password;
  private $serveradres = 'http://data.icecat.biz/xml_s3/xml_server3.cgi';
  public $language;

  /*
   * Our icecat variables.
   */
  public $ean;
  public $sku;
  public $brand;
  /*
   * Store our productinfo.
   */
  public $icecat_data;
  public $errors;

  /**
   * Set our config data.
   */
  public function setConfig($username, $password) {
    $this->username = $username;
    $this->password = $password;
  }

  /**
   * Sets the language to download data in.
   */
  public function setLanguage($language) {
    $this->language = $language;
  }

  /**
   * Set our product data.
   */
  public function setProductInfo($ean = NULL, $sku = NULL, $brand = NULL) {
    $this->ean = $ean;
    $this->sku = $sku;
    $this->brand = $brand;
  }

  /*
   * Return the error if there is one. FALSE if none.
   */
  public function hasErrors() {
    if (!$this->getBaseData()) {
      return $this->errors['error'];
    }
    return FALSE;
  }

  /**
   * Downloads the xml if available, Error array when no data is available.
   */
  private function getBaseData() {

    // Our base return.
    $return = FALSE;

    // Loop all our urls, if we get a result, return it.
    foreach ($this->getUrls() as $url) {
      $options = array(
        'http' => array(
          'header' => "Authorization: Basic " . base64_encode($this->username . ":" . $this->password),
        ),
      );
      $context = stream_context_create($options);
      $data = file_get_contents($url, FALSE, $context);
      $xml = simplexml_load_string($data);
      // Check for errors.
      if (!empty($xml->Product['ErrorMessage'])) {
        $this->errors['error'] = array(
          'message' => $xml->Product['ErrorMessage']->__toString(),
          'code' => $xml->Product['Code']->__toString(),
          'type' => 'error',
        );
        return FALSE;
      }
      elseif ($xml && !empty($xml)) {
        $this->icecat_data = $xml;
        return TRUE;
      }
      else {
        $this->errors['error'] = array(
          'message' => 'Empty response.',
          'code' => 2,
          'type' => 'error',
        );
        return FALSE;
      }
    }
    // No loop, no data.
    $this->errors['error'] = array(
      'message' => 'No valid urls.',
      'code' => 2,
      'type' => 'error',
    );
    return FALSE;
  }

  /**
   * Gets the possible data urls, we have to check them after.
   */
  private function getUrls() {

    $checkurls = array();

    if (!empty($this->ean)) {
      $checkurls[] = $this->serveradres . '?ean_upc=' . urlencode($this->ean) . ';lang=' . $this->language . ';output=productxml;';
    }
    if (!empty($this->sku) && !empty($this->brand)) {
      $checkurls[] = $this->serveradres . '?prod_id=' . urlencode($this->sku) . ';lang=' . $this->language . ';output=productxml;vendor=' . $this->brand . ';';
    } elseif (!empty($this->sku)) {
      $checkurls[] = $this->serveradres . '?ean_upc=' . urlencode($this->sku) . ';lang=' . $this->language . ';output=productxml';
    }

    return $checkurls;
  }

  /**
   * Returns all attributes.
   */
  public function getAttributes() {
    return $this->icecat_data->Product->attributes();
  }

  /**
   * Returns a specific attribute.
   */
  public function getAttribute($attribute) {
    return $this->icecat_data->Product->attributes()->$attribute->__toString();
  }

  /**
   * Returns the supplier.
   */
  public function getSupplier() {
    return $this->icecat_data->Product->Supplier->attributes()->Name->__toString();
  }

  /**
   * Returns product long description.
   */
  public function getLongDescription() {
    if (is_object($this->icecat_data->Product->ProductDescription->attributes()->LongDesc)) {
      return $this->icecat_data->Product->ProductDescription->attributes()->LongDesc->__toString();
    }
    return $this->getShortDescription();
  }

  /**
   * Returns product short description.
   */
  public function getShortDescription() {
    if (is_object($this->icecat_data->Product->ProductDescription->attributes()->ShortDesc)) {
      return $this->icecat_data->Product->ProductDescription->attributes()->ShortDesc->__toString();
    }
    return FALSE;
  }

  /**
   * Gets the product category.
   */
  public function getCategory() {
    return $this->icecat_data->Product->Category->Name->attributes()->Value->__toString();
  }

  /**
   * Returns an array of images.
   */
  public function getImages($limit = 0) {

    // Init our list.
    $images = array();

    // We also count. For our limit.
    $imgcount = 1;

    // Loop our data.
    // Here we check if the gallery is available.
    // If not we just take the main image only.
    if (!empty($this->icecat_data->Product->ProductGallery)) {
      foreach ($this->icecat_data->Product->ProductGallery->ProductPicture as $img) {

        $attr = $img->attributes();
        $images[$imgcount-1]['high'] = $attr->Pic->__toString();
        $images[$imgcount-1]['low'] = $attr->LowPic->__toString();
        $images[$imgcount-1]['thumb'] = $attr->ThumbPic->__toString();

        // If we got all data. Stop.
        if ($imgcount == $limit && $limit !== 0) {
          break;
        }

        // Count up.
        $imgcount++;

      }
    }
    else {
      // So our base did not have images. Lets try and fetch the main image.
      if (!empty($this->icecat_data->Product->attributes()->HighPic)) {
        $images[$imgcount-1]['high'] = $this->icecat_data->Product->attributes()->HighPic->__toString();
        $images[$imgcount-1]['low'] = $this->icecat_data->Product->attributes()->LowPic->__toString();
        $images[$imgcount-1]['thumb'] = $this->icecat_data->Product->attributes()->ThumbPic->__toString();
      }
    }

    return $images;
  }

  public function getSpecs() {

    // Init our list.
    $specs = array();

    // Gotta count here to.
    $speccount = 0;

    // Loop our data.
    foreach ($this->icecat_data->Product->ProductFeature as $feature) {
      $spec[$speccount]['name'] = $feature->Feature->Name->attributes()->Value->__toString();
      $spec[$speccount]['data'] = $feature->attributes()->Presentation_Value->__toString();

      // Count up.
      $speccount++;
    }

    return $spec;

  }

  /**
   * Returns all product data.
   */
  public function getProductData() {
    return $this->icecat_data->Product;
  }

}
?>
