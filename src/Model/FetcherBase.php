<?php

namespace haringsrob\Icecat\Model;

use GuzzleHttp\Client;

/**
 * Class FetcherBase
 *
 * This the base class for IcecatFetcher, providing the minimum required logic.
 */
abstract class FetcherBase implements FetcherInterface
{
    /**
     * The ean number of the product.
     *
     * @var integer
     */
    protected $ean;

    /**
     * The Language of the data we would like to get.
     *
     * @var string
     */
    protected $language;

    /**
     * Errors we have gotten.
     *
     * @var array
     */
    protected $errors = array();

    /**
     * The fetched data object.
     *
     * @var SimpleXML-object
     */
    protected $icecat_data;

    /**
     * The list of urls we can parse.
     *
     * @var array
     */
    protected $dataUrls = array();

    /**
     * The address of the server to fetch data from.
     *
     * @var string
     */
    protected $serveradres = 'https://data.icecat.biz';

    /**
     * @inheritdoc.
     */
    public function getServerAddress()
    {
        return $this->serveradres;
    }

    /**
     * @inheritdoc.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritdoc.
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritdoc.
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @inheritdoc.
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Gets the brand
     *
     * @return brand
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Gets the sku.
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Constructs a list of possible url's to fetch data from.
     *
     * @return array
     */
    public function generateUrls()
    {
        // Init the array to return.
        $checkurls = [];

        // Get the EAN code.
        $ean = $this->getEan();

        // Structure the url. There might be more urls available.
        if (!empty($ean)) {
            $checkurls[] = $this->getServerAddress() .
                '/xml_s3/xml_server3.cgi?ean_upc=' . urlencode($ean) .
                ';lang=' . $this->getLanguage() . ';output=productxml;';
        }
        if (!empty($this->getSku()) && !empty($this->getBrand())) {
            $checkurls[] = $this->getServerAddress() .
                '/xml_s3/xml_server3.cgi?prod_id=' . urlencode($this->getSku()) .
                ';lang=' . $this->getLanguage() .
                ';output=productxml;vendor=' . $this->getBrand() . ';';
        } elseif (!empty($this->getSku())) {
            $checkurls[] = $this->getServerAddress() .
                '/xml_s3/xml_server3.cgi?ean_upc=' .
                urlencode($this->getSku()) . ';lang=' . $this->getLanguage() .
                ';output=productxml';
        }
        $this->setUrls($checkurls);
    }

    /**
     * Sets the urls which will be used to fetch the data.
     *
     * @param $urls
     */
    public function setUrls($urls)
    {
        $this->dataUrls = $urls;
    }

    /**
     * Returns the available urls.
     *
     * @param $urls
     */
    public function getUrls()
    {
        if (empty($this->dataUrls)) {
            $this->generateUrls();
        }
        return $this->dataUrls;
    }


    /**
     * Connects with the server and reads out the data.
     *
     * @return SimpleXML Object|bool
     */
    public function fetchBaseData()
    {
        foreach ($this->getUrls() as $url) {
            $client = new Client();
            $response = $client->request('GET', $url, [
                'verify' => true,
                'auth' => [
                    $this->getUsername(),
                    $this->getPassword(),
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $this->setBaseData($response->getBody()->getContents());
                break;
            } else {
                $this->setError($response->getReasonPhrase(), $response->getStatusCode());
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getBaseData()
    {
        return $this->icecat_data;
    }

    /**
     * @inheritdoc
     */
    public function setBaseData($xml)
    {
        $this->icecat_data = $xml;
    }

    /**
     * @inheritdoc
     */
    public function setError($message, $code, $type = 'error')
    {
        $this->errors[] = [
            'message' => $message,
            'type' => $type,
            'code' => $code,
        ];
    }
}
