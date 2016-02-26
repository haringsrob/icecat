<?php

namespace haringsrob\Icecat\Model;

class Icecat implements IcecatInterface
{
    /**
     * The actual data we fetched. To get the data you can use @see haringsrob\Icecat\Controller\IcecatFetcher
     *
     * @var object
     */
    public $icecat_data;


    /**
     * Icecat Constructor.
     *
     * @todo: validation.
     *
     * @param SimpleXML-object $data
     */
    public function __construct($data)
    {
        $this->icecat_data = $data;
    }

    /**
     * @inheritdoc
     */
    public function setBaseData($data)
    {
        $this->icecat_data = $data;
    }

    /**
     * @inheritdoc
     */
    public function getBaseData()
    {
        return $this->icecat_data;
    }

    /**
     * Returns all attributes.
     */
    public function getAttributes()
    {
        return $this->getProductData()->attributes();
    }

    /**
     * Returns a specific attribute.
     *
     * @param string $attribute
     * @return string
     */
    public function getAttribute($attribute)
    {
        return $this->getAttributes()->$attribute->__toString();
    }

    /**
     * Returns the supplier.
     *
     * @return string
     */
    public function getSupplier()
    {
        return $this->getProductData()->Supplier->attributes()->Name->__toString();
    }

    /**
     * Returns product long description.
     *
     * @return string
     */
    public function getLongDescription()
    {
        if (is_object($this->getProductData()->ProductDescription->attributes()->LongDesc)) {
            return $this->getProductData()->ProductDescription->attributes()->LongDesc->__toString();
        }
        return $this->getShortDescription();
    }

    /**
     * Returns product short description.
     *
     * @return string
     */
    public function getShortDescription()
    {
        if (is_object($this->getProductData()->ProductDescription->attributes()->ShortDesc)) {
            return $this->getProductData()->ProductDescription->attributes()->ShortDesc->__toString();
        }
        return false;
    }

    /**
     * Gets the product category.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->getProductData()->Category->Name->attributes()->Value->__toString();
    }

    /**
     * Returns an array of images.
     *
     * @return array
     */
    public function getImages($limit = 0)
    {

        // Init our list.
        $images = array();

        // We also count. For our limit.
        $imgcount = 1;

        // Loop our data.
        // Here we check if the gallery is available.
        // If not we just take the main image only.
        if (!empty($this->getProductData()->ProductGallery)) {
            foreach ($this->getProductData()->ProductGallery->ProductPicture as $img) {

                $attr = $img->attributes();
                $images[$imgcount - 1]['high'] = $attr->Pic->__toString();
                $images[$imgcount - 1]['low'] = $attr->LowPic->__toString();
                $images[$imgcount - 1]['thumb'] = $attr->ThumbPic->__toString();

                // If we got all data. Stop.
                if ($imgcount == $limit && $limit !== 0) {
                    break;
                }

                // Count up.
                $imgcount++;
            }
        } else {
            // So our base did not have images. Lets try and fetch the main image.
            if (!empty($this->getProductData()->attributes()->HighPic)) {
                $images[$imgcount - 1]['high'] = $this->getProductData()->attributes()->HighPic->__toString();
                $images[$imgcount - 1]['low'] = $this->getProductData()->attributes()->LowPic->__toString();
                $images[$imgcount - 1]['thumb'] = $this->getProductData()->attributes()->ThumbPic->__toString();
            }
        }

        return $images;
    }

    /**
     * Returns an array of specifications.
     *
     * @return array
     */
    public function getSpecs()
    {

        // Init our list.
        $specs = array();

        // Gotta count here to.
        $speccount = 0;

        // Loop our data.
        foreach ($this->getProductData()->ProductFeature as $feature) {
            $spec[$speccount]['name'] = $feature->Feature->Name->attributes()->Value->__toString();
            $spec[$speccount]['data'] = $feature->attributes()->Presentation_Value->__toString();

            // Count up.
            $speccount++;
        }

        return $spec;
    }

    /**
     * Returns all product data.
     *
     * @return object
     */
    public function getProductData()
    {
        return $this->getBaseData()->Product;
    }
}
