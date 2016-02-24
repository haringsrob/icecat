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
     * Fetches and builds a SimpleXml Object from the response.
     *
     * @return SimpleXml Object
     */
    public function fetchBaseData();
}
