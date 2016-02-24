<?php

namespace haringsrob\Icecat\Model;

use haringsrob\Icecat\Model\Icecat;
use haringsrob\Icecat\Model\IcecatFetcherInterface;

/**
 * Class IcecatFetcher
 *
 * This class acts as a communication helper to fetch data from icecat.
 */
class IcecatFetcher extends Icecat implements IcecatFetcherInterface
{
    /**
     * The address of the server to fetch data from.
     *
     * @var string
     */
    public $serveradres = 'http://data.icecat.biz/xml_s3/xml_server3.cgi';

    /**
     * The icecat username.
     *
     * @var string
     */
    private $username;

    /**
     * The plain text password.
     * @var string
     */
    private $password;

    /**
     * Errors we have gotten.
     *
     * @var array
     */
    public $errors;

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
     * @inheritdoc.
     */
    public function getServerAddress()
    {
        return $this->serveradres;
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
                ';lang=' . $this->language . ';output=productxml;';
        }
        if (!empty($this->sku) && !empty($this->brand)) {
            $checkurls[] = $this->getServerAddress() .
                '?prod_id=' . urlencode($this->sku) . ';lang=' . $this->language .
                ';output=productxml;vendor=' . $this->brand . ';';
        } elseif (!empty($this->sku)) {
            $checkurls[] = $this->getServerAddress() . '?ean_upc=' .
                urlencode($this->sku) . ';lang=' . $this->language .
                ';output=productxml';
        }
        return $checkurls;
    }

    /**
     * Returns if there were any errors.
     *
     * @return array|bool
     */
    public function hasErrors()
    {
        if (is_array($this->errors)) {
            return $this->errors['error'];
        }
        return false;
    }

    /**
     * Connects with the server and reads out the data.
     *
     * @return SimpleXML Object|bool
     */
    public function getBaseData()
    {
        // Our base return.
        $return = false;
        // Loop all our urls, if we get a result, return it.
        foreach ($this->getUrls() as $url) {
            $options = array(
                'http' => array(
                    'header' => "Authorization: Basic " . base64_encode($this->username . ":" . $this->password),
                ),
            );
            $context = stream_context_create($options);
            $data = file_get_contents($url, false, $context);
            $xml = simplexml_load_string($data);
            // Check for errors.
            if (!empty($xml->Product['ErrorMessage'])) {
                $this->errors['error'] = array(
                    'message' => $xml->Product['ErrorMessage']->__toString(),
                    'code' => $xml->Product['Code']->__toString(),
                    'type' => 'error',
                );
                return false;
            } elseif ($xml && !empty($xml)) {
                $this->icecat_data = $xml;
                return true;
            } else {
                $this->errors['error'] = array(
                    'message' => 'Empty response.',
                    'code' => 2,
                    'type' => 'error',
                );
                return false;
            }
        }
        // No loop, no data.
        $this->errors['error'] = array(
            'message' => 'No valid urls.',
            'code' => 2,
            'type' => 'error',
        );
        return false;
    }
}
