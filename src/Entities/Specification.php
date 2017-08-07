<?php

namespace haringsrob\Icecat\Entities;

class Specification
{

    /**
     * The raw specification data.
     *
     * @var \stdClass
     */
    private $rawSpecificationData;

    public function __construct(\stdClass $rawSpecificationData)
    {
        $this->rawSpecificationData = $rawSpecificationData;
    }

    public function getSignValue()
    {
        return $this->rawSpecificationData->LocalValue->Measure->Signs->Sign;
    }

    public function getValue()
    {
        return $this->rawSpecificationData->{'@attributes'}->Presentation_Value;
    }

}