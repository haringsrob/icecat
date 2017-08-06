<?php

namespace haringsrob\Icecat\Model;

interface ResultInterface
{
    /**
     * Get the base data object.
     *
     * @return SimpleXML-Object $xml
     */
    public function getBaseData();
}
