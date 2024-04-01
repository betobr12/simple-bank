<?php

namespace App\Modules\CardTransaction\Services;

use Illuminate\Http\JsonResponse;
use App\Modules\Card\Repositories\Contracts\CardRepositoryInterface;
use App\Modules\Account\Repositories\Contracts\AccountRepositoryInterface;
use App\Modules\CardTransaction\Services\Contracts\CardTransactionServiceInterface;
use App\Modules\CardTransaction\Repositories\Contracts\CardTransactionRepositoryInterface;

class CardTransactionService implements CardTransactionServiceInterface
{

    private AccountRepositoryInterface $accountRepository;
    private CardRepositoryInterface $cardRepository;
    private CardTransactionRepositoryInterface $cardTransactionRepository;

    /**
     * @param AccountRepositoryInterface $accountRepository
     * @param CardRepositoryInterface $cardRepository
     * @param CardTransactionRepositoryInterface $cardTransactionRepository
     */
    public function __construct(
        AccountRepositoryInterface $accountRepository,
        CardRepositoryInterface $cardRepository,
        CardTransactionRepositoryInterface $cardTransactionRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->cardRepository = $cardRepository;
        $this->cardTransactionRepository = $cardTransactionRepository;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handler($data): JsonResponse
    {
        return $this->cardTransection($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function cardTransection($data): JsonResponse
    {
        $user = auth()->user();

        if (!$account = $this->accountRepository->firstAccount((object) [
            'user_id', '=', $user->id]
        )) {
            return response()->json(["error" => "Usuário não possui uma conta"]);
        }

        if (!$card = $this->cardRepository->firstCard((object) [
            'user_id', '=', $user->id,
            'account_id', '=', $account->id
        ])) {
            return response()->json(["error" => "Usuário não possui um cartão"]);
        }

        $cardTransaction = (object) [];
        $cardTransaction->account_id = $account->id;
        $cardTransaction->start_date = \Carbon\Carbon::now();
        $balanceCard = (object) $this->cardTransactionRepository->balance($cardTransaction);

        //you will can set the limit amount for transaction

        if ($cardTrans = $this->cardTransactionRepository->create([
            'account_id' => $account->id,
            'user_id' => $user->id,
            'card_id' => $card->id,
            'value' => -$data->value,
            'balance' => $balanceCard->balance - $data->value,
            'store' => $data->store,
            'description' => $data->description,
            'date' => \Carbon\Carbon::now(),
            'created_at' => \Carbon\Carbon::now()
        ])) {
            return response()->json(["success" => "Pagamento de conta feito com sucesso", "dataTransaction" => $cardTrans]);
        }
        return response()->json(["error" => "Occorreu um erro ao realizar o pagamento"]);
    }
}
