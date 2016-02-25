<?php

namespace haringsrob\Icecat\Model;

/**
 * Interface for icecat data object.
 */
interface IcecatFetcherInterface extends IcecatInterface
{
    /**
     * Returns the url to use for getting data.
     *
     * @return string
     */
    public function getServerAddress();

    /**
     * Constructs a list of possible data sheet urls.
     *
     * @return array
     */
    public function getUrls();

    /**
     * Returns the Username required to connect with Icecat.
     *
     * @return string
     */
    public function getUsername();

    /**
     * Returns the Password required to connect with Icecat.
     *
     * @return string
     */
    public function getPassword();

    /**
     * Returns the Language required to connect with Icecat.
     *
     * @return string
     */
    public function getLanguage();

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
