<?php

namespace haringsrob\Icecat\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use haringsrob\Icecat\Exceptions\InvalidDataSheetException;
use haringsrob\Icecat\Exceptions\InvalidResponseException;

/**
 * Minimum requirement to implement in order to fetch data from Icecat.
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
     * The fetched data object.
     *
     * @var SimpleXML-object
     */
    protected $result;

    /**
     * The list of urls we can parse.
     *
     * @var array
     */
    protected $dataUrls = [];

    /**
     * The address of the server to fetch data from.
     *
     * @var string
     */
    protected $serverAddress = 'https://data.icecat.biz';

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
     *
     * @var string
     */
    protected $password;

    /**
     * IcecatFetcher constructor.
     *
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
        return $this->serverAddress;
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
     * @return string
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
     */
    public function generateUrls()
    {
        $urls = [];

        $prefix = $this->getServerAddress() . '/xml_s3/xml_server3.cgi';
        $suffix = ';lang=' . $this->getLanguage() . ';output=productxml;';

        // Structure the url. There might be more urls available.
        if (!empty($this->ean)) {
            $urls[] = $prefix . '?ean_upc=' . urlencode($this->getEan()) . $suffix;
        }
        if (!empty($this->getSku()) && null !== $this->getBrand()) {
            $urls[] = $prefix . '?prod_id=' . urlencode($this->getSku()) . ';vendor=' . $this->getBrand() . $suffix;
        }
        $this->setUrls($urls);
    }

    /**
     * Sets the urls which will be used to fetch the data.
     *
     * @param array $urls
     */
    public function setUrls($urls)
    {
        $this->dataUrls = $urls;
    }

    /**
     * Returns the available urls.
     *
     * @return array
     *   The array containing the possible fetching urls.
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
     * @param \GuzzleHttp\Handler\MockHandler|null $handler
     *   Used for testing, the mockHandler is used for emulating web requests.
     *
     * @return void
     * @throws InvalidDataSheetException
     */
    public function fetchBaseData(MockHandler $handler = null)
    {
        foreach ($this->getUrls() as $url) {
            $icecatRequest = new Client(['handler' => $handler]);
            $icecatRequestResult = $icecatRequest->request(
                'GET',
                $url,
                [
                    'verify' => true,
                    'auth' => [
                        $this->getUsername(),
                        $this->getPassword(),
                    ],
                ]
            );

            if ($this->urlHasValidResponseCode($icecatRequestResult)) {
                $xml = simplexml_load_string($icecatRequestResult->getBody()->getContents());

                if ($this->icecatXmlHasValidData($xml)) {
                    $this->setBaseData($xml);
                    break;
                }
            }
            $this->handleInvalidRequestError($icecatRequestResult);
        }
    }

    /**
     * @param \GuzzleHttp\Psr7\Response $response
     *
     * @return bool
     */
    private function urlHasValidResponseCode(Response $response)
    {
        return $response->getStatusCode() === 200;
    }

    /**
     * @param \SimpleXMLElement $xml
     *
     * @return bool
     * @throws InvalidDataSheetException
     */
    private function icecatXmlHasValidData(\SimpleXMLElement $xml)
    {
        if (isset($xml->Product['ErrorMessage'])) {
            $this->handleInvalidXmlDataError($xml);
        }

        return true;
    }

    /**
     * @param \SimpleXMLElement $xml
     *
     * @throws InvalidDataSheetException
     */
    private function handleInvalidXmlDataError(\SimpleXMLElement $xml)
    {
        $errorCode = $xml->Product['Code']->__toString();
        $errorMessage = $xml->Product['ErrorMessage']->__toString();

        throw new InvalidDataSheetException(
            'Icecat could not get a valid data sheet for this product.',
            $errorMessage,
            $errorCode
        );
    }

    private function handleInvalidRequestError(Response $icecatRequestResult)
    {
        throw new InvalidResponseException(
            $icecatRequestResult->getReasonPhrase(),
            $icecatRequestResult->getStatusCode()
        );
    }

    /**
     * @inheritdoc
     */
    public function getBaseData()
    {
        return $this->result;
    }

    /**
     * The json string to set as data.
     *
     * @param string $json
     */
    public function setBaseData($json)
    {
        $this->result = $json;
    }
}
