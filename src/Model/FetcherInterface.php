<?php

namespace haringsrob\Icecat\Model;

/**
 * Interface for icecat data object.
 */
interface FetcherInterface
{
    /**
     * Gets the url to use for getting data.
     *
     * @return string
     */
    public function getServerAddress();

    /**
     * Generate a list of possible data sheet urls.
     *
     * @return array
     */
    public function generateUrls();

    /**
     * Gets the Username required to connect with Icecat.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Gets the Password required to connect with Icecat.
     *
     * @return string
     */
    public function getPassword();

    /**
     * Gets the Language required to connect with Icecat.
     *
     * @return string
     */
    public function getLanguage();

    /**
     * Gets the defined EAN code.
     *
     * @return string
     */
    public function getEan();

    /**
     * Fetches and builds a SimpleXml Object from the response.
     *
     * @return SimpleXml Object
     */
    public function fetchBaseData();

    /**
     * Sets error messages.
     *
     * @param string $message
     * @param string $code
     * @param string $type
     */
    public function setError($message, $code, $type);
}
