<?php

namespace App\Repositories;

use App\Http\Requests\Complaint\ComplaintRequest;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class ComplaintRepository
{
    protected static $instance;

    private readonly Complaint $model;

    protected function __construct(){
        $this->model = new Complaint();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create(ComplaintRequest $request){
        $consumerId = Auth::user()->id;
        $type = $request->input('complaintable_type');
        $params = [
            'complaintable_id' => $request->input('complaintable_id'),
            'complaintable_type' => $type->modelClass(),
            'consumer_id' => $consumerId,
            'description' => $request->input('description'),
        ];
        return $this->model->query()->create($params);
    }

    //+ методи обробки і оновлення для адмінки, але то пізніше

}
