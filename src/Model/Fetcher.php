<?php

namespace haringsrob\Icecat\Model;

/**
 * Fetches data from Icecat by parsing the xml.
 */
class Fetcher extends FetcherBase
{

    /**
     * Sets the language to download data in.
     *
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Sets the product ean.
     *
     * @param string $ean
     */
    public function setEan($ean)
    {
        $this->ean = $ean;
    }

    /**
     * Sets the product sku.
     *
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * Sets the product brand.
     *
     * @param string $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }
}
