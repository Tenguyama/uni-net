<?php

namespace App\Services;

use App\Http\Requests\Complaint\ComplaintRequest;
use App\Repositories\ComplaintRepository;

class ComplaintService
{
    protected static $instance;

    private readonly ComplaintRepository $repository;

    protected function __construct(){
        $this->repository = ComplaintRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create(ComplaintRequest $request){
        return $this->repository->create($request, null);
    }
    //+ методи обробки і оновлення для адмінки, але то пізніше

}
