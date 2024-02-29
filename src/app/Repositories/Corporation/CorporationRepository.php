<?php

namespace App\Repositories\Corporation;

use App\Models\Corporation;
use App\Models\CorporationApplication;
use App\Models\CorporationSetting;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class CorporationRepository implements CorporationRepositoryInterface
{
    public function findByCorporationId(int $corporationId): Corporation
    {
        return Corporation::find($corporationId);
    }

    public function findByEmailHash(string $emailHash): ?Corporation
    {
        return Corporation::where('email_hash', $emailHash)->first();
    }

    public function updateEmail(int $corporationId, string $email, string $emailHash): void
    {
        $user = Corporation::find($corporationId);
        $user->email = $email;
        $user->email_hash = $emailHash;
        $user->save();
    }

    public function updatePassword(int $corporationId, string $password): void
    {
        $user = Corporation::find($corporationId);
        $user->password = Hash::make($password);
        $user->save();
    }

    public function getCorporationSettingByCorporationId(int $corporationId): CorporationSetting
    {
        return CorporationSetting::where('corporation_id', $corporationId)
            ->first();
    }

    public function updateCorporationSettingBySettingId(int $settingId, array $param): void
    {
        $corporationSetting = CorporationSetting::find($settingId);
        $corporationSetting->fill($param);
        $corporationSetting->save();
    }

    public function getAllCorporations(): Collection
    {
        return Corporation::get();
    }

    public function getAllPaginateCorporations(): LengthAwarePaginator
    {
        return Corporation::with('businessOperators')->paginate(10);
    }

    public function searchPaginateCorporationByRequest(Request $request): LengthAwarePaginator
    {
        $query = Corporation::with('businessOperators');

        if ($request->filled('corporationName')) {
            $query->where('corporation_name', 'like', '%' . $request->corporationName . '%');
        }

        return $query->paginate(10)->appends($request->all());
    }

    public function createCorporationApplicationByRequest(array $request): CorporationApplication
    {
        return CorporationApplication::create($request);
    }

    public function createCorporationByRequest(array $request): Corporation
    {
        return Corporation::create($request);
    }

    public function createCorporationSettingByRequest(array $request): void
    {
        CorporationSetting::create($request);
    }

    public function getCorporationDetailsByCorporationId(int $corporationId): Corporation
    {
        return Corporation::with('corporationSettings', 'businessOperators')->find($corporationId);
    }

    public function getCorporationApplicationByApplicationId(int $applicationId): CorporationApplication
    {
        return CorporationApplication::where('corporation_application_id', $applicationId)->first();
    }

    public function updateCorporationApplicationByRequest(array $request): void
    {
        $corporationApplication = CorporationApplication::find($request['corporation_application_id']);
        $corporationApplication->fill($request);
        $corporationApplication->save();
    }

    public function updateCorporationByRequest(array $request): void
    {
        $corporation = Corporation::find($request['corporation_id']);
        $corporation->fill($request);
        $corporation->save();
    }
}
