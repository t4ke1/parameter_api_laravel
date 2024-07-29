<?php

namespace App\Service;

use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Http\Requests\AddPictureRequest;
use App\Http\Requests\UpdatePicturePostRequest;
use App\Models\parameter;
use Illuminate\Support\Facades\Storage;

class ParameterService
{
    /**
     * @throws NotFoundException
     */
    public function getUpLoadableParameters(): array
    {
        $parameters1 = parameter::where('icon', null)
            ->where('type', 1)
            ->get();
        $parameters2 = Parameter::where(function ($query) {
            $query->where('icon', null)
                ->orWhere('icon_gray', null);
        })
            ->where('type', 2)
            ->get();
        $data = $parameters1->merge($parameters2);
        if ($data->isEmpty()) {
            throw new NotFoundException;
        }
        $dataParameter = [];
        $i = 0;
        foreach ($data as $parameter) {
            $i++;
            $dataParameter[] = [
                "parameter №$i" => $parameter,
            ];
        }

        return $dataParameter;
    }

    /**
     * @throws NotFoundException
     */
    public function getLoadableParameters(): array
    {
        $parameter1 = parameter::where('type', 1)
            ->whereNotnull('icon')
            ->where('icon', '!=', '')
            ->get();
        $parameter2 = parameter::where('type', 2)
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereNotNull('icon')
                        ->where('icon', '!=', '');
                })
                    ->orWhere(function ($query) {
                        $query->whereNotNull('icon_gray')
                            ->where('icon_gray', '!=', '');
                    });
            })
            ->get();
        $dataParameter = $parameter1->merge($parameter2);
        if ($dataParameter->isEmpty()) {
            throw new NotFoundException;
        }
        $data = [];
        $i = 0;
        foreach ($dataParameter as $parameter) {
            $i++;
            $data[] = [
                "parameter №$i" => $parameter,
            ];
        }

        return $data;
    }

    /**
     * @throws NotFoundException
     */
    public function findByIdOrTitle($key): parameter
    {
        if (is_numeric($key)) {
            $parameter = parameter::where('id', $key)->first();

        } else {
            $parameter = parameter::where('title', $key)->first();
        }
        if ($parameter === null) {
            throw new NotFoundException;
        }

        return $parameter;

    }

    /**
     * @throws NotFoundException
     * @throws BadRequestException
     */
    public function addPictures(AddPictureRequest $request): array
    {
        $validated = $request->validated();
        $id = $validated['id'];
        $icon = $validated['icon'];

        $parameter = parameter::find($id);
        if ($parameter === null) {
            throw new NotFoundException;
        }
        if (! empty($parameter->icon)) {
            throw new BadRequestException;
        }

        $iconName = nameGenerate(pathinfo($icon->getClientOriginalName()), $id);
        $filePathIcon = $icon->storeAs('pictures', $iconName, 'public');
        parameter::where('id', $id)->update(
            [
                'icon' => $filePathIcon,
                'file_name_icon' => $iconName,
            ]
        );

        if ($parameter->type === 1) {
            return ['ok' => 'picture icon added'];
        } elseif ($parameter->type === 2) {
            if (! isset($validated['icon_gray'])) {
                return ['ok' => 'picture icon added and icon_gray empty'];
            }
            if (! empty($parameter->icon_gray)) {
                throw new BadRequestException;
            }

            $iconGray = $validated['icon_gray'];
            $iconNameGray = nameGenerate(pathinfo($iconGray->getClientOriginalName()), $id);
            $filePathIconGray = $iconGray->storeAs('pictures', $iconNameGray, 'public');
            parameter::where('id', $id)->update(
                [
                    'icon_gray' => $filePathIconGray,
                    'file_name_icon_gray' => $iconNameGray,
                ]
            );

            return ['ok' => 'picture icon and icon_gray added'];
        }

        return ['ok' => 'picture added'];
    }

    /**
     * @throws NotFoundException
     * @throws BadRequestException
     */
    public function updatePictures(UpdatePicturePostRequest $request): array
    {
        $validated = $request->validated();
        $id = $validated['id'];

        $parameter = parameter::find($id);
        if ($parameter === null) {
            throw new NotFoundException;
        }
        if (! isset($validated['icon'])) {
            throw new BadRequestException;
        }
        $type = $parameter->type;
        $icon = $validated['icon'];
        $iconName = nameGenerate(pathinfo($icon->getClientOriginalName()), $id);

        $filePathIcon = $icon->storeAs('pictures', $iconName, 'public');

        $oldIcon = parameter::where('id', $id)->value('icon');

        parameter::where('id', $id)->update(
            [
                'icon' => $filePathIcon,
                'file_name_icon' => $iconName,
            ]
        );

        Storage::disk('public')->delete($oldIcon);

        if ($type === 1) {
            return ['ok' => 'picture icon updated'];
        } elseif ($type === 2) {
            if (! isset($validated['icon_gray'])) {
                return ['ok' => 'picture icon updated, icon_gray empty'];
            }
            $iconGray = $validated['icon_gray'];
            $iconNameGray = nameGenerate(pathinfo($iconGray->getClientOriginalName()), $id);

            $filePathIconGray = $iconGray->storeAs('pictures', $iconNameGray, 'public');

            $oldIconGray = parameter::where('id', $id)->value('icon_gray');
            parameter::where('id', $id)->update(
                [
                    'icon_gray' => $filePathIconGray,
                    'file_name_icon_gray' => $iconNameGray,
                ]
            );

            Storage::disk('public')->delete($oldIconGray);

            return ['ok' => 'picture icon and icon_gray updated'];
        }

        return ['ok' => 'picture added'];
    }

    /**
     * @throws NotFoundException|BadRequestException
     */
    public function deletePictures($id): array
    {
        $parameter = parameter::find($id);
        if ($parameter === null) {
            throw new NotFoundException;
        }
        $type = $parameter->type;
        $icon = $parameter->icon;
        switch ($type) {
            case 1:
                if ($icon === null) {
                    throw new BadRequestException;
                } else {
                    Storage::disk('public')->delete($icon);
                    parameter::where('id', $id)->update(
                        [
                            'icon' => null,
                            'file_name_icon' => null,
                        ]
                    );

                    return ['ok' => 'picture icon deleted'];
                }

            case 2:
                if ($icon === null) {
                    $iconGray = $parameter->icon_gray;
                    if ($iconGray === null) {
                        throw new BadRequestException;
                    } else {
                        Storage::disk('public')->delete($iconGray);
                        parameter::where('id', $id)->update(
                            [
                                'icon_gray' => null,
                                'file_name_icon_gray' => null,
                            ]);

                        return ['ok' => 'picture icon was empty and icon_gray deleted'];
                    }
                } else {
                    $iconGray = $parameter->icon_gray;
                    if ($iconGray === null) {
                        Storage::disk('public')->delete($icon);
                        parameter::where('id', $id)->update(
                            [
                                'file_name_icon' => null,
                                'icon' => null,
                            ]
                        );

                        return ['ok' => 'picture icon deleted, icon_gray was empty'];
                    } else {
                        Storage::disk('public')->delete($icon);
                        parameter::where('id', $id)->update(
                            [
                                'file_name_icon' => null,
                                'icon' => null,
                            ]
                        );
                        Storage::disk('public')->delete($iconGray);
                        parameter::where('id', $id)->update(
                            [
                                'file_name_icon_gray' => null,
                                'icon_gray' => null,
                            ]
                        );

                        return ['ok' => 'picture icon and icon_gray deleted'];
                    }
                }
        }

        return ['ok' => 'picture icon and icon_gray deleted'];
    }

    public function getCustomList(): array
    {
        $emptyPicturesParameters1 = parameter::where('icon', null)
            ->where('type', 1)
            ->get();
        $emptyPicturesParameters2 = Parameter::where(function ($query) {
            $query->where('icon', null)
                ->orWhere('icon_gray', null);
        })
            ->where('type', 2)
            ->get();

        $emptyPicturesParameterMerge = $emptyPicturesParameters1->merge($emptyPicturesParameters2);
        $dataEmptyPicturesParameterId = [];
        foreach ($emptyPicturesParameterMerge as $parameter) {
            $dataEmptyPicturesParameterId[] = $parameter->id;
        }

        $loadedPicturesParameter = Parameter::where(function ($query) {
            $query->where('icon', '!=', null)
                ->orWhere('icon_gray', '!=', null);
        })
            ->get();

        $dataLoadedPicturesParameter = [];
        foreach ($loadedPicturesParameter as $parameter) {
            $dataLoadedPicturesParameter[] = [
                'id' => $parameter->id,
                'icon' => $parameter->icon,
                'icon_gray' => $parameter->icon_gray,
                'file_name_icon' => $parameter->file_name_icon,
                'file_name_icon_gray' => $parameter->file_name_icon_gray,
            ];
        }

        return [
            'dataEmptyPicturesParameterId' => $dataEmptyPicturesParameterId,
            'dataLoadedPicturesParameter' => $dataLoadedPicturesParameter,
        ];
    }
}
