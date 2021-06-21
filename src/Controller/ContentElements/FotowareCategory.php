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
class FotowareCategory extends AbstractContentElementController {

    protected $api;
    protected $session;
    protected $fotobaseBaseUri;

    public function __construct(API $api, Session $session, $fotowareBaseUri) {
        $this->api = $api;
        $this->fotobaseBaseUri = $fotowareBaseUri;
        $this->session = $session;

        $this->session->start();
    }

    protected function getResponse(Template $template, ContentModel $model, Request $request): ?Response
    {
        if ($this->isBackend()) {
            $beTemplate = new BackendTemplate('be_wildcard');
            $beTemplate->title = "### FOTOWARE CATEGORY ###";
            return $beTemplate->getResponse();
        }

        $queryParams = $this->getQueryParams();

        $response = $this->api->get($model->fotoware_category . $queryParams);
        $template->fotowareBaseUrl = $this->fotobaseBaseUri;
        $template->baseUrl = $request->getPathInfo();
        $template->filterExclude = $this->getFilterExcludeIdsAsArray($model);

        $data = json_decode($response->getBody());
        $data = $this->addCountToTaxonomies($data);

        $template->data = $data;
        $template->selectedParams = $this->getSelectedQueryParams();
        $template->bookmarks = $this->getBookmarks($request);


        return $template->getResponse();
    }

    public function addCountToTaxonomies($data)
    {
        $metaDataCategory = "25";
        $arrTaxonomies = array();
        foreach($data->assets->data as $img) {
            foreach($img->metadata->{$metaDataCategory}->value as $tax) {
                $arrTaxonomies[] = $tax;
            }
        }
        $arrTaxonomies = array_count_values($arrTaxonomies);

        foreach($data->taxonomies as $taxonomy) {
            foreach($taxonomy->items as $item) {
                $item->count = $arrTaxonomies[$item->value] ?? 0;
            }
        }

        return $data;
    }

    public function isBackend()
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();
        return System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request);
    }

    protected function getQueryParams()
    {
        $queryString = "";
        foreach($_GET as $key=>$value) {
            if(!empty($value)) {
                $params[] = $key . "=" . $value;
            }
        }

        if(count($params) > 0) {
            $queryString = "?" . implode("&", $params);
        }

        return $queryString;
    }

    protected function getSelectedQueryParams()
    {
        $params = array();

        foreach($_GET as $key=>$value) {
            if(!empty($value)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    protected function getFilterExcludeIdsAsArray(ContentModel $model)
    {
        $filterExcludeList = \StringUtil::deserialize($model->fotoware_filter_exclude);

        if(!$filterExcludeList) {
            $ids = array();
        }

        foreach($filterExcludeList as $filter) {
            $ids[] = $filter["filterId"];
        }

        return $ids;
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

}
