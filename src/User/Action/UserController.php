<?php


namespace App\User\Action;

//use App\Common\AppController;
use App\Common\AppController;
use App\Common\GoogleApi;
use App\User\Action\Register\RegisterRequest;
use App\User\Model\User;
use App\User\Model\UserRepository;
use Google_Client;
use Google_Service_Sheets;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AppController
{
    private $users;

    private $sheets;

    private $tableId;

    const DEFAULT_PARAMS = [
        'valueInputOption' => 'RAW',
    ];

    public function __construct(UserRepository $users, Google_Service_Sheets $sheets, string $tableId)
    {
        $this->users = $users;
        $this->sheets = $sheets;
        $this->tableId = $tableId;
    }

    /**
     * @Route(
     *     "/bar"
     * )
     */
    public function bar(Request $request): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route(
     *     "/foo"
     * )
     */
    public function foo(Request $request): Response
    {
        $file = $request->files->get('file');

        return new JsonResponse();
    }

    /**
     * @Route("/register", methods={"GET", "POST"})
     */
    public function register(Request $request): Response
    {
        $data = [
            'test_field' => 'test data',
        ];

        $body = new \Google_Service_Sheets_ValueRange([
            'values' => [
                array_values($data),
            ],
        ]);

        $this->sheets->spreadsheets_values->append($this->tableId, 'foo', $body, static::DEFAULT_PARAMS);


//        $user = User::registered($register);
//        $this->users->add($user);
//        $this->flush();

//        return $this->successJson(['id' => $user->getId()]);
    }
}
