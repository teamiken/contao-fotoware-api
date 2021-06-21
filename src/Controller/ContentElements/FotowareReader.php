<?php

namespace teamiken\Fotoware\Controller\ContentElements;

use Contao\BackendTemplate;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\Template;
use teamiken\Fotoware\API\API;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;

/**
 * @ContentElement(category="texts")
 */
class FotowareReader extends AbstractContentElementController {

    protected $api;
    protected $fotobaseBaseUri;

    public function __construct(API $api, Session $session, $fotobaseBaseUri) {
        $this->api = $api;
        $this->session = $session;
        $this->fotobaseBaseUri = $fotobaseBaseUri;

        $this->session->start();
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        if ($this->isBackend()) {
            $beTemplate = new BackendTemplate('be_wildcard');
            $beTemplate->title = "### FOTOWARE READER ###";
            return $beTemplate->getResponse();
        }

        $q = \Input::get("asset");

        $template->bookmarks = $this->getBookmarks($request);

        try {
            $response = $this->api->get($q);
            $template->data = json_decode($response->getBody());
            $template->data->count = $this->getCountFromSession($template->data);
        } catch(\Exception $e) {
            $template->error = true;
        }

        $template->baseUrl = $this->fotobaseBaseUri;

        return $template->getResponse();
    }

    public function isBackend()
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();
        return System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request);
    }

    protected function getBookmarks(Request $request)
    {
        $bookmarks = $this->session->get("fotoware_bookmarks");

        $arrReturn = array();
        foreach($bookmarks as $bookmark) {
            $arrReturn[] = $bookmark["href"];
        }

        return $arrReturn;
    }

    protected function getCountFromSession($data)
    {
        $bookmarks = $this->session->get("fotoware_bookmarks");

        if(empty($bookmarks)) {
            return 1;
        }

        foreach($bookmarks as $k=>$bookmark) {
            if($k == md5($data->href)) {
                return $bookmark["count"];
            }
        }

        return 1;
    }

}
