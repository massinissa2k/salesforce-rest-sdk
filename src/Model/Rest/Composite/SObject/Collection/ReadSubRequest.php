<?php
/**
 * Created by PhpStorm.
 * User: alex.boyce
 * Date: 9/19/18
 * Time: 2:26 PM
 */

namespace AE\SalesforceRestSdk\Model\Rest\Composite\SObject\Collection;

use AE\SalesforceRestSdk\Model\Rest\Composite\GetSubRequest;
use AE\SalesforceRestSdk\Model\Rest\Composite\SubRequest;
use AE\SalesforceRestSdk\Rest\Composite\CompositeClient;
use JMS\Serializer\Annotation as Serializer;

class ReadSubRequest extends GetSubRequest implements CompositeCollectionSubRequestInterface
{
    /**
     * @var string
     * @Serializer\Exclude()
     */
    private $sObjectType;

    /**
     * @var array
     * @Serializer\Exclude()
     */
    private $ids = [];

    /**
     * @var array
     * @Serializer\Exclude()
     */
    private $fields = ["Id"];

    public function __construct(
        string $sObjectType,
        array $ids = [],
        array $fields = ["Id"],
        ?string $referenceId = null
    ) {
        parent::__construct($referenceId);

        $this->sObjectType = $sObjectType;
        $this->ids         = $ids;
        $this->fields      = $fields;
    }

    /**
     * @return string
     */
    public function getSObjectType(): string
    {
        return $this->sObjectType;
    }

    /**
     * @return array
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param array $ids
     *
     * @return ReadSubRequest
     */
    public function setIds(array $ids): ReadSubRequest
    {
        $this->ids = $ids;

        return $this;
    }

    /**
     * @param array $fields
     *
     * @return ReadSubRequest
     */
    public function setFields(array $fields): ReadSubRequest
    {
        $this->fields = $fields;

        return $this;
    }

    final public function setBody($body): SubRequest
    {
        return $this;
    }

    final public function setUrl(string $url): SubRequest
    {
        return $this;
    }

    /**
     * @Serializer\PreSerialize()
     */
    public function preSerialize()
    {
        if (empty($this->ids)) {
            throw new \RuntimeException("Cannot Get a Collection without any IDs");
        }

        $this->url = CompositeClient::BASE_PATH.'/'.$this->sObjectType.'?'.http_build_query(
            [
                'ids'    => implode(",", $this->ids),
                'fields' => implode(",", $this->fields),
            ]
        );
    }
}