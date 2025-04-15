<?php

namespace App\Http\Controllers;

use App\Services\DashboardServices;
use App\Traits\ResponseTraits;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ResponseTraits;

    public function __construct()
    {
        $this->service = new DashboardServices;
    }

    public function loadDaily(Request $request)
    {
        $result = $this->successResponse('Data loaded successfully!');
        try {
            $result["data"] = $this->service->getDaily($request->all());
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function loadWeekly(Request $request)
    {
        $result = $this->successResponse('Data loaded successfully!');
        try {
            $result["data"] = $this->service->getWeekly($request->all());
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function loadMonthly(Request $request)
    {
        $result = $this->successResponse('Data loaded successfully!');
        try {
            $result["data"] = $this->service->getMonthly($request->all());
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }

    public function loadYearly(Request $request)
    {
        $result = $this->successResponse('Data loaded successfully!');
        try {
            $result["data"] = $this->service->getYearly($request->all());
        } catch (\Throwable $th) {
            $result = $this->errorResponse($th);
        }

        return $this->returnResponse($result);
    }
}
