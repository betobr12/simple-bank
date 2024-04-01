<?php

namespace App\Modules\Card\Services;

use App\Libraries\GenerateCard;
use Illuminate\Http\JsonResponse;
use App\Modules\Card\Services\Contracts\CardServiceInterface;
use App\Modules\Card\Repositories\Contracts\CardRepositoryInterface;
use App\Modules\Account\Repositories\Contracts\AccountRepositoryInterface;

class CardService implements CardServiceInterface
{
    private AccountRepositoryInterface $accountRepository;
    private CardRepositoryInterface $cardRepository;

    /**
     * @param AccountRepositoryInterface $accountRepository
     * @param CardRepositoryInterface $cardRepository
     */
    public function __construct(
        AccountRepositoryInterface $accountRepository,
        CardRepositoryInterface $cardRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->cardRepository = $cardRepository;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handler($data): JsonResponse
    {
        return $this->newCard($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function newCard($data): JsonResponse
    {
        $user = auth()->user();

        if (!$account = $this->accountRepository->firstAccount((object) [
            'user_id', '=', $user->id]
        )) {
            return response()->json(["error" => "Usuário não possui uma conta"]);
        }

        if ($cardAccount = $this->cardRepository->firstCard((object) [
            'user_id', '=', $user->id,
            'account_id', '=', $account->id
        ])) {
            return response()->json(["error" => "Você já possui um cartão", "card" => $cardAccount]);
        }

        $card = new GenerateCard();
        $card->account_id = $account->id;
        $card->user_id = $user->id;
        $card->date = \Carbon\Carbon::now();
        $card_number = $card->cardNumber();

        if (!$cardData = $this->cardRepository->create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'number_card' => $card_number->card_number,
            'created_at' => \Carbon\Carbon::now()
        ])) {
            return response()->json(["error" => "Occorreu alguma falha ao criar o cartão, por favor tente acessar mais tarde"]);
        }
        return response()->json(["success" => "Cartão cadastrado com sucesso", "card" => $cardData]);
    }
}
