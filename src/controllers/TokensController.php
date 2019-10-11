<?php

namespace mccallister\console\controllers;

use Craft;
use craft\web\Controller;
use yii\web\Response;

class TokensController extends Controller
{
    /**
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): Response
    {
        $this->requireAdmin(true);

        // hardcode the tokens
        $tokens = [
            [
                'id' => 'dddddd',
                'name' => 'Example Token',
                'enabled' => true,
                'lastUsed' => null,
                'expiryDate' => null,
            ],
            [
                'id' => 'dddddd',
                'name' => 'Another Token',
                'enabled' => false,
                'lastUsed' => null,
                'expiryDate' => null,
            ]
        ];

        return $this->renderTemplate('console/tokens/_index', [
            'tokens' => $tokens,
        ]);
    }

    /**
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionEdit(): Response
    {
        $this->requireAdmin(true);

        return $this->renderTemplate('console/tokens/_edit', [
            'title' => 'Create a new console token',
            'token' => [
                'id' => 'ddddd',
                'uid' => 'ddddd',
                'name' => 'ddddd',
            ],
        ]);
    }
}
