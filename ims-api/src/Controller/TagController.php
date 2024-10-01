<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Value\AccessingMember;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/tag')]
class TagController extends AbstractController
{
    #[Route('', name: 'tag_list', methods: ['GET'])]
    public function listTags(
        TagRepository   $tagRepository,
        AccessingMember $accessingMember,
    )
    {
        $membership = $accessingMember->membership;
        $tags = $tagRepository->findByOrganization($membership->getOrganization());

        return new JsonResponse([
            'tags' => array_map(fn (Tag $tag) => ['id' => $tag->getId(), 'name' => $tag->getName()], $tags)
        ]);
    }

    #[Route('/{id}', name: 'tag_update', methods: ['POST'])]
    public function updateTag(
        TagRepository   $tagRepository,
        AccessingMember $accessingMember,
        Request         $request,
        int             $id
    )
    {
        $membership = $accessingMember->membership;
        $tag = $tagRepository->findOneByIdAndOrganization($id, $membership->getOrganization());

        if (!$tag) {
            throw $this->createNotFoundException();
        }

        $payload = $request->getPayload();
        $tagName = $payload->get('name');
        $tag->setName($tagName);
        $tagRepository->save($tag);

        return new JsonResponse([
            'product' => ['id' => $tag->getId(), 'name' => $tag->getName()],
        ]);
    }

    #[Route('', name: 'tag_create', methods: ['POST'])]
    public function createTag(
        Request         $request,
        AccessingMember $accessingMember,
        TagRepository   $tagRepository,
    ): JsonResponse {
        $payload = $request->getPayload();
        $tagName = $payload->get('name');

        $membership = $accessingMember->membership;
        $tag = new Tag($membership->getOrganization(), $tagName);
        $tagRepository->save($tag);

        return new JsonResponse(
            [
                'tag' => [
                    'id' => $tag->getId()
                ]
            ]
        );
    }

}
