<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Http\Requests\AddPictureRequest;
use App\Http\Requests\UpdatePicturePostRequest;
use App\Service\ParameterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;

#[Info(
    version: '3.0.0',
    description: 'Parameter API',
    title: 'Parameter API',
)]
class ParameterController extends Controller
{
    public function __construct(
        private readonly ParameterService $parameterService
    ) {}

    /**
     * @throws NotFoundException
     */
    #[Get(
        path: '/getUpLoadableParameters',
        description: 'Get up loadable parameters',
        summary: 'Show parameters id where icon or icon_gray is null',
        tags: ['GET']
    )]
    #[Response(
        response: 200,
        description: 'Successful',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'parameter №1',
                        type: 'array',
                        items: new Items(
                            properties: [
                                new Property(
                                    property: 'id',
                                    type: 'integer',
                                    example: 1
                                ),
                                new Property(
                                    property: 'title',
                                    type: 'string',
                                    example: 'OOOm1acm3M'
                                ),
                                new Property(
                                    property: 'type',
                                    type: 'int',
                                    example: 2
                                ),
                                new Property(
                                    property: 'icon',
                                    type: 'string',
                                    example: null
                                ),
                                new Property(
                                    property: 'icon_gray',
                                    type: 'string',
                                    example: null
                                ),
                                new Property(
                                    property: 'file_icon_name',
                                    type: 'string',
                                    example: null
                                ),
                                new Property(
                                    property: 'file_icon_gray_name',
                                    type: 'string',
                                    example: null
                                ),
                            ]
                        ),
                    ), ]
            )
        )
    )]
    #[Response(
        response: 404,
        description: 'Not found',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'error',
                        type: 'string',
                        example: 'Not found Exception 404'
                    ),
                ]
            ))
    )]
    public function getUpLoadableParameters(): JsonResponse
    {
        try {
            $data = $this->parameterService->getUpLoadableParameters();
        } catch (NotFoundException $e) {
            throw new NotFoundException($e->getMessage(), $e->getCode());
        }

        return response()->json($data);
    }

    #[Get(
        path: '/getLoadableParameters',
        description: 'Get loadable parameters',
        summary: 'Show parameters where icon is not null',
        tags: ['GET']
    )]
    #[Response(
        response: 404,
        description: 'Not found',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'error',
                        type: 'string',
                        example: 'Not found Exception 404'
                    ),
                ]
            ))
    )]
    #[Response(
        response: 200,
        description: 'Successful',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'parameter №1',
                        type: 'array',
                        items: new Items(
                            properties: [
                                new Property(
                                    property: 'id',
                                    type: 'integer',
                                    example: 1
                                ),
                                new Property(
                                    property: 'title',
                                    type: 'string',
                                    example: 'OOOm1acm3M'
                                ),
                                new Property(
                                    property: 'type',
                                    type: 'int',
                                    example: 2
                                ),
                                new Property(
                                    property: 'icon',
                                    type: 'string',
                                    example: 'pictures/fyva_3.jpg'
                                ),
                                new Property(
                                    property: 'icon_gray',
                                    type: 'string',
                                    example: null
                                ),
                                new Property(
                                    property: 'file_icon_name',
                                    type: 'string',
                                    example: 'fyva_3.jpg'
                                ),
                                new Property(
                                    property: 'file_icon_gray_name',
                                    type: 'string',
                                    example: null
                                ),
                            ]
                        ),
                    ), ]
            )
        )
    )]
    public function getLoadableParameters(): JsonResponse
    {
        try {
            $data = $this->parameterService->getLoadableParameters();
        } catch (NotFoundException $e) {
            throw new BadRequestException($e->getMessage(), $e->getCode());
        }

        return response()->json($data);
    }

    #[Get(
        path: '/findByIdOrTitle/{key}',
        description: 'Find parameter by id or title',
        summary: 'Key can be id or title',
        tags: ['GET']
    )]
    #[Response(
        response: 200,
        description: 'Successful',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'id',
                        type: 'integer',
                        example: 1
                    ),
                    new Property(
                        property: 'title',
                        type: 'string',
                        example: 'OOOm1acm3M'
                    ),
                    new Property(
                        property: 'type',
                        type: 'int',
                        example: 2
                    ),
                    new Property(
                        property: 'icon',
                        type: 'string',
                        example: 'pictures/fyva_3.jpg'
                    ),
                    new Property(
                        property: 'icon_gray',
                        type: 'string',
                        example: null
                    ),
                    new Property(
                        property: 'file_icon_name',
                        type: 'string',
                        example: 'fyva_3.jpg'
                    ),
                    new Property(
                        property: 'file_icon_gray_name',
                        type: 'string',
                        example: null
                    ),
                ]
            )
        )
    )]
    #[Response(
        response: 404,
        description: 'Not found',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'error',
                        type: 'string',
                        example: 'Not found Exception 404'
                    ),
                ]
            ))
    )]
    public function findByIdOrTitle($key): JsonResponse
    {
        try {
            $data = $this->parameterService->findByIdOrTitle($key);
        } catch (NotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        return response()->json($data);
    }

    #[Post(
        path: '/addPictures',
        description: 'Add pictures to parameter',
        summary: 'Add pictures to parameter',
        tags: ['POST']
    )]
    #[RequestBody(
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'id',
                        type: 'integer',
                        example: 1
                    ),
                    new Property(
                        property: 'icon',
                        type: 'image'
                    ),
                    new Property(
                        property: 'icon_gray',
                        type: 'image|null'
                    ),
                ]
            )),
    )]
    #[Response(
        response: 200,
        description: 'Successful',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'ok1',
                        type: 'string',
                        example: 'picture added'
                    ),
                ]
            )
        ))]
    #[Response(
        response: 404,
        description: 'Not found',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'error',
                        type: 'string',
                        example: 'Not found Exception 404'
                    ),
                ]
            ))
    )]
    #[Response(
        response: 400,
        description: 'Bad request',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'error',
                        type: 'string',
                        example: 'Bad request Exception 400'
                    ),
                ]
            ))
    )]
    public function addPictures(AddPictureRequest $request): JsonResponse
    {
        try {
            $data = $this->parameterService->addPictures($request);
        } catch (NotFoundException|BadRequestException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        return response()->json([$data]);
    }

    #[Post(
        path: '/updatePictures',
        description: 'Update pictures to parameter',
        summary: 'Update pictures to parameter',
        tags: ['POST']
    )]
    #[RequestBody(
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'id',
                        type: 'integer',
                        example: 1
                    ),
                    new Property(
                        property: 'icon',
                        type: 'image'
                    ),
                    new Property(
                        property: 'icon_gray',
                        type: 'image|null'
                    ),
                ]
            )),
    )]
    #[Response(
        response: 200,
        description: 'Successful',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'ok1',
                        type: 'string',
                        example: 'picture icon updated'
                    ),
                    new Property(
                        property: 'ok2',
                        type: 'string',
                        example: 'picture icon updated and icon_gray empty'
                    ),
                    new Property(
                        property: 'ok3',
                        type: 'string',
                        example: 'picture icon and icon_gray updated'
                    ),
                ]
            )
        ))]
    #[Response(
        response: 404,
        description: 'Not found',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'error',
                        type: 'string',
                        example: 'Not found Exception 404'
                    ),
                ]
            ))
    )]
    #[Response(
        response: 400,
        description: 'Bad request',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'error',
                        type: 'string',
                        example: 'Bad request Exception 400'
                    ),
                ]
            ))
    )]
    public function updatePictures(UpdatePicturePostRequest $request): JsonResponse
    {
        try {
            $data = $this->parameterService->updatePictures($request);
        } catch (NotFoundException|BadRequestException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        return response()->json([$data]);
    }

    #[Delete(
        path: '/deletePictures/{id}',
        description: 'Delete pictures of parameter',
        summary: '{id} - id of parameter',
        tags: ['DELETE']
    )]
    #[Response(
        response: 200,
        description: 'Successful',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'ok',
                        type: 'string',
                        example: 'picture icon deleted'
                    ),
                    new Property(
                        property: 'ok2',
                        type: 'string',
                        example: 'picture icon and icon_gray deleted'
                    ),
                    new Property(
                        property: 'ok3',
                        type: 'string',
                        example: 'picture icon_gray deleted, icon was empty'
                    ),
                    new Property(
                        property: 'ok4',
                        type: 'string',
                        example: 'picture icon was empty, icon_gray deleted'
                    ),
                ]
            )
        ))]
    #[Response(
        response: 404,
        description: 'Not found',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'error',
                        type: 'string',
                        example: 'Not found Exception 404'
                    ),
                ]
            ))
    )]
    #[Response(
        response: 400,
        description: 'Bad request',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [new Property(

                    property: 'error',
                    type: 'string',
                    example: 'Bad request Exception 400'
                ),
                ]
            ))
    )]
    public function deletePictures($id): JsonResponse
    {
        try {
            $data = $this->parameterService->deletePictures($id);
        } catch (NotFoundException|BadRequestException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }

        return response()->json($data);
    }

    #[Get(
        path: '/getCustomList',
        description: 'Get list of custom parameters',
        summary: 'get all the parameters to which you can load images from list of loaded images',
        tags: ['GET']
    )]
    #[Response(
        response: 200,
        description: 'Successful',
        content: new MediaType(JsonResponse::class,
            schema: new Schema(
                properties: [
                    new Property(
                        property: 'dataEmptyPicturesParameterId',
                        type: 'array',
                        items: new Items(
                            type: 'integer',
                            example: [1, 2, 3]
                        ),
                    ),
                    new Property(
                        property: 'dataLoadedPicturesParameter',
                        type: 'array',
                        items: new Items(
                            properties: [
                                new Property(
                                    property: 'id',
                                    type: 'integer',
                                    example: 1
                                ),
                                new Property(
                                    property: 'icon',
                                    type: 'string',
                                    example: 'pictures/fyva_2.jpg'
                                ),
                                new Property(
                                    property: 'icon_gray',
                                    type: 'string',
                                    example: 'pictures/windows-11_2.jpg'
                                ),
                                new Property(
                                    property: 'file_icon_name',
                                    type: 'string',
                                    example: 'fyva_2.jpg'
                                ),
                                new Property(
                                    property: 'file_icon_gray_name',
                                    type: 'string',
                                    example: 'windows-11_2.jpg'
                                ),
                            ]

                        ),
                    ),
                ]
            )),
    )]
    public function getCustomList(): JsonResponse
    {
        $data = $this->parameterService->getCustomList();

        return response()->json($data);
    }
}
