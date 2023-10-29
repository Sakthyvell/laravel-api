<?php

namespace App\Http\Controllers;

use App\Component\EntityManager\EntityData;
use App\Component\EntityManager\EntityManager;
use App\Component\TransactionProcessor\TransactionData;
use App\Component\TransactionProcessor\TransactionProcessor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;

class TransactionController extends Controller
{

     public function createTransaction(Request $request) : Response
     {
      try{
         $validator = Validator::make($request->all(),[
            'entity_id' => ['required'],
         ]);
         
         if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return Response(["Error" => "Invalid Schema"], 400);
         }
        
         $requestData = $request->post();

         $entityData = new EntityData();
         $entityData->id = $requestData['entity_id'];
         $entity = new EntityManager($entityData);
         $entityData = $entity->getEntity();

         $transactionData = new TransactionData();
         $transactionData->entityId = $entityData->id;
         $transaction = new TransactionProcessor($transactionData);

         $key = $transaction->createTransaction();

         return Response(['key' => $key]);

     }catch(Exception $e){
         return Response(["Error" => $e->getMessage()], $e->getCode());
      }
   }
         

     public function updateTransaction(Request $request)
     {
         $validator = Validator::make($request->all(),[
            'action' => ['required', 'in:withdraw,deposit'],
            'amount' => ['required', 'numeric'],
         ]);
         
         if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return Response(["Error" => "Invalid Schema"], 400);
         }
         
         try{
            $requestData = $request->post();

            $transactionData = new TransactionData();
            $transactionKey = $request->header('idempotent-key');
            if(empty($transactionKey)) return Response(["Error" => "Missing key"], 400);
            $transactionData->action = $requestData['action'];
            $transactionData->amount = $requestData['amount'];
            $transactionData->transactionId = $transactionKey;
   
            $dbHelper = new DB();
            $dbHelper::beginTransaction();
            $transaction = new TransactionProcessor($transactionData, $dbHelper, $transactionKey);
            $transaction->completeTransaction();
            $dbHelper::commit();

            return Response([]);
         }catch(Exception $e){
            return Response(["Error" => $e->getMessage()], $e->getCode());
         }
     }
}
