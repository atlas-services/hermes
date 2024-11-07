<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/api', name: 'api_')]
class FileUploadController
{

    public function __construct(
        private readonly ParameterBagInterface $params,
        private readonly UrlGeneratorInterface $router,
    )
    {
    }

    #[Route('/file/upload', name: 'file_upload', defaults: ['_format=json'], methods: ['post'])]
    public function fileUpload(Request $request): JsonResponse
    {
        // Handle the file upload. Ensure you validate the file (e.g., for type and size)
        $uploadedFile = $request->files->get('upload'); // 'upload' is the default field name used by CKEditor for uploads

        if (!$uploadedFile) {
            return new JsonResponse(['error' => ['message' => 'No file uploaded']], Response::HTTP_BAD_REQUEST);
        }

        // Perform file validation, e.g., file type and size

        /** @var string $projectDir */
        $projectDir = $this->params->get('kernel.project_dir');

        // Save the file to a directory. You can use services like Symfony's FileSystem or Flysystem for this
        $destination = $projectDir . '/public/'.$this->params->get('hermes_path_content_image_post');

        try {
            $uploadedFile->move($destination, $uploadedFile->getClientOriginalName());
        } catch (\Exception $exception) {
            return new JsonResponse(['error' => ['message' => 'Could not save file: '.$exception->getMessage()]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Return a JSON response that includes the URL to the uploaded file. This URL is used by CKEditor to reference the image
        $url = $request->getSchemeAndHttpHost().'/uploads/'.$uploadedFile->getClientOriginalName();

        return new JsonResponse(['url' => $url]);
    }


}
