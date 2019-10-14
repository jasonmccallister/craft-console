<?php

namespace mccallister\console\controllers;

use yii\web\Response;
use craft\web\Controller;
use mccallister\console\models\Token;

class TokensController extends Controller
{
    /**
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): Response
    {
        $this->requireAdmin(true);

        $token = new Token();
        $token->name = 'testing';
        $token->accessToken = '32rffds';

        $tokens = [$token];

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

        $token = new Token();
        $token->name = "this";
        $token->accessToken = "12356768";

        return $this->renderTemplate('console/tokens/_edit', [
            'title' => 'Create a new console token',
            'token' => $token,
        ]);
    }
}
