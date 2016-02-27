<?php

namespace haringsrob\Icecat\Model;

use haringsrob\Icecat\Model\Result;

/**
 * Class Fetcher
 *
 * This class acts as a communication helper to fetch data from icecat.
 */
class Fetcher extends FetcherBase
{
    /**
     * The sku (product number) of the product.
     *
     * @var string
     */
    protected $sku;

    /**
     * The brand of the product.
     *
     * @var string
     */
    protected $brand;

    /**
     * The icecat username.
     *
     * @var string
     */
    protected $username;

    /**
     * The plain text password.
     * @var string
     */
    protected $password;

    /**
     * IcecatFetcher constructor.
     * @param $username
     * @param $password
     * @param $ean
     * @param $language
     */
    public function __construct($username, $password, $ean, $language)
    {
        $this->username = $username;
        $this->password = $password;
        $this->ean = $ean;
        $this->language = $language;
    }

    /**
     * Sets the language to download data in.
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Sets the product ean.
     *
     * @param $ean
     */
    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    /**
     * Sets the product sku.
     *
     * @param $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * Sets the product brand.
     *
     * @param $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * Gets errors if there are any.
     *
     * @return array|bool
     */
    public function getErrors()
    {
        if (!empty($this->errors)) {
            return $this->errors;
        }
        return false;
    }
}
