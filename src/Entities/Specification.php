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

    public function getPresentationValue()
    {
        return $this->rawSpecificationData->{'@attributes'}->Presentation_Value;
    }

    public function getValue()
    {
        return $this->rawSpecificationData->{'@attributes'}->Value;
    }

    public function getSignValue()
    {
        if (!empty($this->rawSpecificationData->LocalValue->Measure->Signs->Sign)) {
            return $this->rawSpecificationData->LocalValue->Measure->Signs->Sign;
        }
        return null;
    }

    public function getName()
    {
        return $this->rawSpecificationData->Feature->Name->{'@attributes'}->Value;
    }

}