<?php

namespace App\Component\TransactionProcessor;

class TransactionData {
    
    /** @var string */
    public $action = '';

    /** @var float */
    public $amount = 0;

    /** @var string */
    public $entityId = '';

    /** @var string */
    public $transactionId = '';
}