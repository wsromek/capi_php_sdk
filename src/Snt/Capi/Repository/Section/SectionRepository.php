<?php

namespace Snt\Capi\Repository\Section;

use Snt\Capi\Http\Exception\HttpException;
use Snt\Capi\Http\HttpClientInterface;
use Snt\Capi\Repository\Section\Exception\CouldNotFetchSectionRepositoryException;

class SectionRepository implements SectionRepositoryInterface
{
    const SECTION_PATH_PATTERN = 'publication/%s/sections';

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(FindParameters $findParameters)
    {
        try {
            $sectionsRawData = json_decode(
                $this->httpClient->get(
                    $this->buildPath($findParameters)
                ),
                true
            );
        } catch (HttpException $exception) {
            throw new CouldNotFetchSectionRepositoryException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        return isset($sectionsRawData['sections']) ? $sectionsRawData['sections'] : [];
    }

    private function buildPath(FindParameters $findParameters)
    {
        return sprintf(self::SECTION_PATH_PATTERN, $findParameters->getPublicationId());
    }
}
