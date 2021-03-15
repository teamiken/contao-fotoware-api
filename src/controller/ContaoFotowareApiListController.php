<?php

namespace teamiken\ContaoFotowareApiController\controller;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\Template;
use GuzzleHttp\Client;
use GuzzleHttp\Middleware;

class ContentFotowareList extends AbstractContentElementController {
    function __construct(Fotoware $api) {}
    function __invoke(Template $template, Model $model) {
         $template->fotos = $this->api->getPhotosFromCategory($model->category));
     }
 }
