<?php

namespace mccallister\console\controllers;

use Craft;
use craft\base\Element;
use craft\elements\Asset;
use craft\web\Controller;
use craft\web\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use mccallister\console\contracts\TransformsElements;
use mccallister\console\transformers\AssetTransformer;

class AssetsController extends Controller implements TransformsElements
{
    public $enableCsrfValidation = false;

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = ['index'];

    /**
     * The default element query to use on resources
     *
     * @var array
     */
    protected $defaultElementQuery = [
        'limit' => 10,
        'orderBy' => 'dateCreated ASC'
    ];

    protected $allowedParams = [
        'limit',
        'kind',
        'filename',
    ];

    /**
     * Show all the elements.
     *
     * @return Response
     */
    public function actionIndex(): Response
    {
        $params = Craft::$app->getRequest()->getQueryParams();

        if (!empty($params)) {
            $filtered = array_intersect_key($params, array_flip((array) $this->allowedParams));
        } else {
            $filtered = $this->defaultElementQuery;
        }

        $element = $this->getElement();

        return $this->asJson(
            $this->transformCollection($element->findAll($filtered), new AssetTransformer)
        );
    }

    /**
     * Shows a specific element by its UID.
     *
     * @return Response
     */
    public function actionShow(): Response
    {
        $id = Craft::$app->getRequest()->getSegment(3);
        if ($id == null) {
            return $this->asErrorJson("id not found");
        }

        $element = $this->getElement();

        return $this->asJson($this->transformItem(
            $element->findOne(['uid' => $id]),
            new AssetTransformer
        ));
    }

    protected function getElement(): Element
    {
        return new Asset();
    }

    public function transformCollection($resources, $transformer): array
    {
        $fractal = new Manager();

        return $fractal->createData(
            new Collection($resources, $transformer)
        )->toArray();
    }

    public function transformItem($resource, $transformer): array
    {
        $fractal = new Manager();

        return $fractal->createData(
            new Item($resource, $transformer)
        )->toArray();
    }
}
