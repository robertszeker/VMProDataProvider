<?php

namespace MovingImage\DataProvider;

use MovingImage\Client\VMPro\Entity\VideosRequestParameters;
use MovingImage\Client\VMPro\Interfaces\ApiClientInterface;
use MovingImage\DataProvider\Interfaces\DataProviderInterface;
use MovingImage\DataProvider\Wrapper\Video;

/**
 * Class VideoManagerPro.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class VideoManagerPro implements DataProviderInterface
{
    /**
     * @var ApiClientInterface
     */
    private $apiClient;

    /**
     * VideoManagerPro constructor.
     *
     * @param ApiClientInterface $apiClient
     */
    public function __construct(ApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(array $options)
    {
        return $this->apiClient->getVideos($options['vm_id'], $this->createVideosRequestParameters($options));
    }

    /**
     * {@inheritdoc}
     */
    public function getOne(array $options)
    {
        $video = $this->apiClient->getVideo($options['vm_id'], $options['id']);
        $embedCode = $this->apiClient->getEmbedCode($options['vm_id'], $options['id'], $options['embed_code_id']);

        return new Video($video, $embedCode);
    }

    /**
     * CURRENTLY NOT IMPLEMENTED.
     *
     * @param array $options
     *
     * @return int
     */
    public function getCount(array $options)
    {
        return 0;
    }

    /**
     * Converts array into VideosRequestParameters.
     *
     * @param array $options
     *
     * @return VideosRequestParameters
     */
    private function createVideosRequestParameters(array $options)
    {
        $parameters = new VideosRequestParameters();

        $queryMethods = [
            'limit' => 'setLimit',
            'order' => 'setOrder',
            'search_term' => 'setSearchTerm',
            'search_field' => 'setSearchInField',
            'channel_id' => 'setChannelId',
            'order_property' => 'setOrderProperty',
        ];

        foreach ($queryMethods as $key => $method) {
            if (isset($options[$key])) {
                $parameters->$method($options[$key]);
            }
        }

        return $parameters;
    }
}
