<?php


namespace App\Actions\Transaction;


class GetTransactionDataAction {


    private $onlineData;
    private  $walkInData;
    private $totalOnline;
    private $totalWalkIn;

    public function handle($onlineData, $walkInData)
    {
        $this->onlineData = $onlineData;
        $this->walkInData = $walkInData;
        $this->totalOnline = $onlineData->count();
        $this->totalWalkIn = $walkInData->count();

        return $this->toArray();
    }
    public function toArray() {

        return [
            'online' => [
                'transaction' => $this->onlineData,
                'total' => $this->totalOnline
            ],
            'walkIn' => [
                'transaction' => $this->walkInData,
                'total' => $this->totalWalkIn
            ]
        ];
    }
}
