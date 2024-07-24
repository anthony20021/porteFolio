<?php

namespace App\Http\Controllers;
use App\Classes\Fonctions;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Symfony\Component\Mailer\Exception\TransportException;
use Illuminate\Support\Facades\Auth;

class SendMailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'SendMailForLostPassword'
        ]);
    }


    //il faut un subject, un to, et un content.
    public function sendEmail(Request $request){
        $result = [
            'statut' => 'ok',
            'message' => 'Le message a bien été envoyé'
        ];
        try {
            $to = $request->to;

            $user = Auth::user();

            if(empty($user)){
                $result = [
                    'statut' => 'ko',
                    'message' => 'Vous devez vous connecter pour envoyer un message'
                ];
                return response($result, 500);
            }

            $userEmail = $user->email;

            $data = [
                'from' => 'contact@anthonygonzalez.website',
                'reply_to' => 'contact@anthonygonzalez.website',
                'to' => $to,
                'sujet' => $request->sujet,
                'content' => $request->content
            ];

            Mail::send('mails.contact', $data, function ($message) use ($data) {
                $message->subject($data['sujet']);
                $message->from($data['from']);
                $message->replyTo($data['reply_to']);
                $message->to($data['to']);
                $message->sender($data['from']);
                $message->bcc(['anthony20021@gmail.com'], 'Support');
                $message->priority(2);
            });
            return $result;
        }

        catch (Exception $e) {
            $result = [
                'statut' => 'ko',
                'message' => 'Une erreur s\'est produite pendant l\'envoi du mail. ' . $e
            ];

            return response($result, 500);
        }
    }


    public static function SendMailForContact($data_mail)
    {
        $result = [
            'statut' => 'ok',
            'message' => 'Le message a bien été envoyé'
        ];
    
        try {
            // Vérification de la validité des arguments
            $name = $data_mail['name'];
            $firstname = $data_mail['firstname'];
            $email = $data_mail['email'];
            $sujet = $data_mail['sujet'];
            $themessage = $data_mail['message'];
            $data = [
                'from' => 'contact@anthonygonzalez.website',
                'to' => 'anthony20021@gmail.com',
                'sujet' => 'Prise de contact '. $sujet,
                'themessage' => $themessage,
                'name' => $name,
                'firstname' => $firstname,
                'email' => $email,
            ];

    
            Mail::send('mails.notif', $data, function ($message) use ($data) {
                // Le sujet du mail
                $message->subject($data['sujet']);
    
                // Le FROM et le SENDER doivent avoir exactement les mêmes valeurs
                // pour éviter de se retrouver dans les spams
                $message->from($data['from'], 'Anthony Gonzalez');
                $message->sender($data['from'], 'Anthony Gonzalez');
                $message->to($data['to']);
    
                // Copie Carbone Invisible
                $message->bcc(['anthony20021@gmail.com'], 'Support');
    
                // Priorité du message (1: HIGHEST, 2: HIGH, 3: NORMAL, 4: LOW, 5: LOWEST)
                $message->priority(2);
            });
            return $result;
        } catch (TransportException $e) {
            $result = [
                'statut' => 'ko',
                'message' => $e->getMessage() . ' Line ' .  $e->getLine()
            ];
            return $result;
        }
    }


}
