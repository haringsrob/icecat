<?php

namespace haringsrob\Icecat\Model;

/**
 * Interface for icecat data object.
 */
interface ResultInterface
{
    /**
     * Get the base data object.
     *
     * @return SimpleXML-Object $xml
     */
    public function getBaseData();
}
