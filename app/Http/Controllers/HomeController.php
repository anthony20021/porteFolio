<?php

namespace App\Http\Controllers;

use App\Classes\Fonctions;
use App\Models\Codes;
use App\Models\Cv;
use App\Models\Experiences;
use App\Models\Messages;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Mailer\Exception\TransportException;

class HomeController extends Controller
{
    public static function verifyCaptcha($token) {
        $secret_key = Fonctions::getApiKey();
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $data = [
            'secret' => $secret_key,
            'response' => $token
        ];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($http_code != 200) {
            return [
                'success' => false,
                'message' => 'Erreur de connexion au service reCAPTCHA'
            ];
        }

        $result = json_decode($response, true);

        return $result;
    }

    public function index()
    {
        $projects = Projects::selectRaw('*,FALSE AS show_description')->with('documents')->where('front_page', true)->get();
        $codes = Codes::selectRaw('*,FALSE AS show_description')->with('documents')->with('file')->where('front_page', true)->get();
        $cv = Cv::selectRaw('*,FALSE AS show_description')->with('file')->where('front_page', true)->get();
        $experiences = Experiences::selectRaw('*,FALSE AS show_description')->where('front_page', true)->get();
        return view('home', ["projects" => $projects, "codes" => $codes, "cv" => $cv, "experiences" => $experiences]);
    }
    
    public function contact(Request $request)
    {
        $email = $request->email;
        $sujet = $request->sujet;
        $message = $request->message;
        $name = $request->name;
        $captcha = $request->captcha;
        $firstname = $request->firstname;
        $date = date('Y-m-d H:i:s');
        $data = [
            'name' => $name,
            'firstname' => $firstname,
            'email' => $email,
            'sujet' => $sujet,
            'message' => $message,
            'date' => $date,
        ];
    
        // Vérifier que l'email est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['statut' => 'error', 'message' => 'Adresse email invalide'], 400);
        }
    
        try {
            $captchaResult = HomeController::verifyCaptcha($captcha);
    
            if (!$captchaResult['success'] || $captchaResult['score'] < 0.2) {
                return response()->json(['statut' => 'error', 'message' => 'Erreur CAPTCHA ('.$captchaResult['score'].')'], 500);
            }
    
            DB::beginTransaction();
            Messages::create($data);
    
            $result = SendMailController::SendMailForContact($data);
            
            if (is_array($result) && isset($result['statut'])) {
                if ($result['statut'] == 'ok') {
                    DB::commit();
                    return response()->json(['statut' => 'ok', 'message' => "Le message a bien été envoyé"], 200);
                } else {
                    DB::rollBack();
                    return response()->json(['statut' => 'error', 'message' => $result['message']], 400);
                }
            } else {
                DB::rollBack();
                return response()->json(['statut' => 'error', 'message' => 'Format de réponse inattendu de SendMailForContact'], 400);
            }
        } catch (TransportException $e) {
            return response()->json(['statut' => 'error', 'message' => $e->getMessage() . ' Line ' .  $e->getLine()], 400);
        }
    }

}
