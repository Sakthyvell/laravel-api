<?php

namespace App\Http\Controllers;

use App\Component\EntityManager\EntityData;
use App\Component\EntityManager\EntityManager;
use App\Http\Resources\LegalEntityResource;
use Exception;
use Illuminate\Http\Response;

class LegalEntityController extends Controller
{
    // Get list of all legal entity
    public function index()
    {
        try{
            $entity = new EntityManager();
            return LegalEntityResource::collection($entity->getEntityList());
        }catch(Exception $e){
            return Response(["Error" => $e->getMessage()], $e->getCode());
        }
    }

    // Checking legal entities bank balance
    public function show(string $id) : Response
    {
        try{
            $entityData = new EntityData();
            $entityData->id = $id;
            $entity = new EntityManager($entityData);
            $entityData = $entity->getEntity();
            return Response(json_decode(json_encode($entityData), true));
        }catch(Exception $e){
            return Response(["Error" => $e->getMessage()], $e->getCode());
        }
    }
}
