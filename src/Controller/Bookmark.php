<?php

namespace teamiken\Fotoware\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\SessionInterface as Session;

class Bookmark extends AbstractController {

    /**
     * @Route("/api/fotoware/bookmarks/toggle", name="toggleBookmark", methods={"POST"})
     * @return string
     */
    public function toggleBookmark(Request $request, Session $session)
    {
        if(!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException("Only Ajax Requests");
        }

        $session->start();

        $asset = $request->request->get("asset");
        $bookmarks = $session->get("fotoware_bookmarks");

        if(null === $bookmarks) {
            $bookmarks = array();
        }

        $id = md5($asset["href"]);

        if($bookmarks[$id]) {
            unset($bookmarks[$id]);
        } else {
            $asset["count"] = 1;
            $bookmarks[$id] = $asset;
        }

        $session->set("fotoware_bookmarks", $bookmarks);

        return new JsonResponse($bookmarks);
    }

    /**
     * @Route("/api/fotoware/bookmarks", name="setBookmark", methods={"POST"})
     * @return string
     */
    public function setBookmark(Request $request, Session $session)
    {
        if(!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException("Only Ajax Requests");
        }

        $session->start();

        $asset = $request->request->get("asset");
        $count = $request->request->get("count");
        $bookmarks = $session->get("fotoware_bookmarks");

        if(null === $bookmarks) {
            $bookmarks = array();
        }

        $id = md5($asset["href"]);

        if ($count) {
            $asset["count"] = $count;
        }

        if (empty($asset["count"])) {
            $asset["count"] = 1;
        }

        $bookmarks[$id] = $asset;
        $session->set("fotoware_bookmarks", $bookmarks);

        return new JsonResponse($bookmarks);
    }



    /**
     * @Route("/api/fotoware/bookmarks/count/{count}", name="setBookmarkCountTo", methods={"POST"})
     * @return string
     */
    public function setBookmarkCountTo(Request $request, Session $session, $count)
    {
        if(!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException("Only Ajax Requests");
        }

        $session->start();
        $id = $request->request->get("id");
        $bookmarks = $session->get("fotoware_bookmarks");

        if(null === $bookmarks || empty($bookmarks[$id])) {
            return new Response("No Bookmark with this id");
        }

        $bookmarks[$id]["count"] = $count;

        $session->set("fotoware_bookmarks", $bookmarks);

        return new JsonResponse($bookmarks);
    }

    /**
     * @Route("/api/fotoware/bookmarks", name="getBookmark", methods={"GET"})
     * @return string
     */
    public function getBookmarks(Request $request, Session $session)
    {
        $session->start();
        $bookmarks = $session->get("fotoware_bookmarks");

        return new JsonResponse($bookmarks);
    }

    /**
     * @Route("/api/fotoware/bookmarks/{id}", name="removeBookmark", methods={"DELETE"})
     * @return string
     */
    public function removeBookmark(Request $request, Session $session, $id)
    {
        $session->start();
        $bookmarks = $session->get("fotoware_bookmarks");
        unset($bookmarks[$id]);
        $session->set("fotoware_bookmarks", $bookmarks);

        return new JsonResponse($bookmarks);
    }
}
