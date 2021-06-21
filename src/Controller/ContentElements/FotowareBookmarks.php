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
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;

/**
 * @ContentElement(category="texts")
 */
class FotowareBookmarks extends AbstractContentElementController {

    protected $session;

    public function __construct(Session $session) {
        $this->session = $session;

        $this->session->start();
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        if ($this->isBackend()) {
            $beTemplate = new BackendTemplate('be_wildcard');
            $beTemplate->title = "### FOTOWARE MERKLISTE ###";
            return $beTemplate->getResponse();
        }

        $template->bookmarks = $this->getBookmarks($request);

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
            $arrReturn[] = $bookmark;
        }

        return $arrReturn;
    }

}
