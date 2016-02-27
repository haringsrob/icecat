<?php

namespace haringsrob\Icecat\Model;

/**
 * Interface for icecat data object.
 */
interface ResultInterface
{
    /**
     * Set the base data that other methods will use..
     *
     * @param SimpleXML-Object $xml
     */
    public function setBaseData($xml);

    /**
     * Get the base data object.
     *
     * @return SimpleXML-Object $xml
     */
    public function getBaseData();
}
