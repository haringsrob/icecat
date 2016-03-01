<?php

namespace haringsrob\Icecat\Model;

class Result implements ResultInterface
{
    /**
     * The actual data we fetched. To get the data you can use @see haringsrob\Icecat\Controller\IcecatFetcher
     *
     * @var json
     */
    public $data;


    /**
     * Icecat Constructor.
     *
     * @todo: validation.
     *
     * @param Json $data
     */
    public function __construct($data)
    {
        $this->data = json_decode(json_encode($data));
    }

    /**
     * @inheritdoc
     */
    public function setBaseData($data)
    {
        $this->data = json_decode(json_encode($data));
    }

    /**
     * @inheritdoc
     */
    public function getBaseData()
    {
        return $this->data;
    }

    /**
     * Gets all attributes.
     */
    public function getAttributes()
    {
        return $this->getProductData()->{"@attributes"};
    }

    /**
     * Gets a specific attribute.
     *
     * @param string $attribute
     *
     * @return string
     */
    public function getAttribute($attribute)
    {
        return $this->getAttributes()->$attribute;
    }

    /**
     * Gets the supplier.
     *
     * @return string
     */
    public function getSupplier()
    {
        return $this->getProductData()->Supplier->{"@attributes"}->Name;
    }

    /**
     * Gets the long description.
     *
     * @return string
     */
    public function getLongDescription()
    {
        return $this->getProductData()->ProductDescription->{"@attributes"}->LongDesc;
    }

    /**
     * Gets the short description.
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->getProductData()->ProductDescription->{"@attributes"}->ShortDesc;
    }

    /**
     * Gets the product category.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->getProductData()->Category->Name->{"@attributes"}->Value;
    }

    /**
     * Gets an array of images.
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

                $attr = $img->{"@attributes"};
                $images[$imgcount - 1]['high'] = $attr->Pic;
                $images[$imgcount - 1]['low'] = $attr->LowPic;
                $images[$imgcount - 1]['thumb'] = $attr->ThumbPic;

                // If we got all data. Stop.
                if ($imgcount == $limit && $limit !== 0) {
                    break;
                }

                // Count up.
                $imgcount++;
            }
        } else {
            // So our base did not have images. Lets try and fetch the main image.
            if (!empty($this->getProductData()->{"@attributes"}->HighPic)) {
                $images[$imgcount - 1]['high'] = $this->getProductData()->{"@attributes"}->HighPic;
                $images[$imgcount - 1]['low'] = $this->getProductData()->{"@attributes"}->LowPic;
                $images[$imgcount - 1]['thumb'] = $this->getProductData()->{"@attributes"}->ThumbPic;
            }
        }

        return $images;
    }

    /**
     * Gets an array of specifications.
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
            $spec[$speccount]['name'] = $feature->Feature->Name->{"@attributes"}->Value;
            $spec[$speccount]['data'] = $feature->{"@attributes"}->Presentation_Value;

            // Count up.
            $speccount++;
        }

        return $spec;
    }

    /**
     * Gets all product data.
     *
     * @return object
     */
    public function getProductData()
    {
        return $this->data->Product;
    }
}
