<?php

namespace teamiken\Fotoware\Controller\ContentElements;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\Template;
use teamiken\Fotoware\API\API;

/**
 * @ContentElement(category="texts")
 */
class ContentFotowareCategory extends AbstractContentElementController {

    public function __construct(API $api) {
        $this->api = $api;
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        $response = $this->api->get('archives/5014-Highlights-des-Monats');
        $template->archive = json_decode($response->getBody());

        return $template->getResponse();
    }
}
