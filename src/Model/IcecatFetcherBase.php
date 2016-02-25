<?php

namespace haringsrob\Icecat\Model;

/**
 * Class IcecatFetcherBase
 *
 * This the base class for IcecatFetcher, providing the minimum required logic.
 */
abstract class IcecatFetcherBase extends Icecat implements IcecatFetcherInterface
{
    /**
     * The ean number of the product.
     *
     * @var integer
     */
    public $ean;

    /**
     * The Language of the data we would like to get.
     *
     * @var string
     */
    public $language;

    /**
     * Errors we have gotten.
     *
     * @var array
     */
    public $errors = array();

    /**
     * The address of the server to fetch data from.
     *
     * @var string
     */
    public $serveradres = 'http://data.icecat.biz/xml_s3/xml_server3.cgi';

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
     * Constructs a list of possible url's to fetch data from.
     *
     * @return array
     */
    public function getUrls()
    {
        $checkurls = array();
        if (!empty($this->ean)) {
            $checkurls[] = $this->getServerAddress() .
                '?ean_upc=' . urlencode($this->ean) .
                ';lang=' . $this->getLanguage() . ';output=productxml;';
        }
        if (!empty($this->sku) && !empty($this->brand)) {
            $checkurls[] = $this->getServerAddress() .
                '?prod_id=' . urlencode($this->sku) . ';lang=' . $this->getLanguage() .
                ';output=productxml;vendor=' . $this->brand . ';';
        } elseif (!empty($this->sku)) {
            $checkurls[] = $this->getServerAddress() . '?ean_upc=' .
                urlencode($this->sku) . ';lang=' . $this->getLanguage() .
                ';output=productxml';
        }
        return $checkurls;
    }

    /**
     * Connects with the server and reads out the data.
     *
     * @return SimpleXML Object|bool
     */
    public function fetchBaseData()
    {
        $auth_string = "Authorization: Basic " . base64_encode($this->getUsername() . ":" . $this->getPassword());
        // Our base return.
        $return = false;
        // Loop all our urls, if we get a result, return it.
        foreach ($this->getUrls() as $url) {
            $options = array(
                'http' => array(
                    'header' => $auth_string,
                ),
            );

            // @todo: Take a different approach.
            try {
                $context = stream_context_create($options);
                $data = @file_get_contents($url, false, $context);
                $xml = simplexml_load_string($data);
            } catch (Exception $e) {
                return false;
            }

            // Check for errors.
            if (!empty($xml->Product['ErrorMessage'])) {
                $this->setError($xml->Product['ErrorMessage']->__toString(), $xml->Product['Code']->__toString());
                return false;
            } elseif (is_object($xml)) {
                $this->setBaseData($xml);
                return true;
            } else {
                $this->setError('Empty response.', 3);
                return false;
            }
        }
        // No loop, no data.
        $this->setError('No valid urls.', 2);
        return false;
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
