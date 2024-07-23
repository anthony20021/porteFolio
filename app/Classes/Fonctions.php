<?php

/*
* Classe qui contient des fonctions générales
*/

namespace App\Classes;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Nom_campagne;
use Illuminate\Support\Carbon;
use PDO;

abstract class Fonctions
{
    // private const PASSWORD_REGEX = "/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!.@#$%]{7,}$/"; //regex différente du JS
    private const PASSWORD_REGEX = "/(?=^.{8,}$)(?=.*\d)(?=.*[@$!%.#]+)(?=.*[A-Z])(?=.*[a-z]).*$/";
    private const EMAIL_REGEX = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,10})$/";


    public static function PasswordIsValid($password)
    {
        return preg_match(self::PASSWORD_REGEX, $password);
    }

    /* return @boolean
    * retourne true si la chaine de caractère est un email valide (bien formé)
    */
    public static function EmailIsValid($email)
    {
        return preg_match(self::EMAIL_REGEX, $email);
    }


    //renvoie l'équivalent de dd(toSql()) avec les bindings
    public static function ddq(Builder $query)
    {
        $sql = $query->toSql();
        foreach ($query->getBindings() as $key => $binding) {
            $sql = preg_replace('/\?/', "'$binding'", $sql, 1);
        }
        dd($sql);
    }

    //renvoie l'équivalent de dump(toSql()) avec les bindings
    public static function dumpq(Builder $query)
    {
        $sql = $query->toSql();
        foreach ($query->getBindings() as $key => $binding) {
            $sql = preg_replace('/\?/', "'$binding'", $sql, 1);
        }
        dump($sql);
    }

    /**
     * Retourne l'année de campagne correspondant à la date en paramètre
     * @param mixed $date
     *
     * @return string
     */
    // public static function dateToCampagne($date)
    // {
    //     $annee = date('Y') - 1;
    //     $date_min_campagne = \DateTime::createFromFormat("Y-m-d", $annee . '-07-01')->format("Y-m-d");
    //     $date_max_campagne = \DateTime::createFromFormat("Y-m-d", $annee + 1 . '-06-30')->format("Y-m-d");
    //     $date = \DateTime::createFromFormat("Y-m-d", $date)->format("Y-m-d");

    //     if (($date >= $date_min_campagne) && $date <= $date_max_campagne) {
    //         return intval($annee);
    //     } else {
    //         return $annee + 1;
    //     }
    // }

    /**
     * Retourne l'année de campagne correspondant à la date en paramètre
     * @param mixed $date
     *
     * @return string
     */
   /**
     * Retourne les dates de la campagne actuelle du 01/10/N-1 au 30/09/N
     * @param mixed $date
     *
     * @return DateTime[]
     */
    /*
    * Convertit une donnée geojson Polygon en geometry postgis 2154
    * Retourne une string
    */

    /**
     * Convertit un tableau de données en éléments de menu déroulant.
     * @param array $data Le tableau de données
     * @param string $value L'index de la ligne du tableau correspondant à la valeur de l'élément 'option'
     * @param string $title Le texte à afficher dans l'élément 'option>'
     * @param bool $return Retourner le résultat en tant que valeur au lieu de l'imprimer (FALSE par défaut)
     * @param bool $option_header Ajouter ou non des 'headers' représentant la première lettre du résultat (intercalaires alphabétiques)
     * @param string $bg_color La couleur de la ligne en-tête au format hexadécimal.
     * @param string $text_color La couleur du texte au format hexadécimal.
     * @return void
     */
    public static function dataToSelectOption($data, $value, $title, $datavalue = '', $return = false, $option_header = false, $selected = '', $bg_color = '#00c453', $text_color = '#fff')
    {
        if (!empty($data)) {
            $result = '';
            if ($option_header) {
                $letter = '';
                foreach ($data as $index => $row) {
                    $sel = ($selected === $row[$value] ? 'selected' : '');
                    $clean_value = preg_replace('!\s+!', ' ', trim($row[$value]));
                    if (!empty($clean_value) && !empty($row[$title])) {
                        $datavalue = (!empty($datavalue) ? 'data-value="' . $row[$datavalue] . '"' : '');
                        if (empty($letter)) {
                            $letter = substr($row[$title], 0, 1);
                            if (!$return) {
                                echo ("<option value=\"\" style=\"background-color: $bg_color;color: $text_color;font-weight: bold;font-family: 'Arial';\" disabled=\"\">- $letter -</option>");
                            } else {
                                $result .= "<option value=\"\" style=\"background-color: $bg_color;color: $text_color;font-weight: bold;font-family: 'Arial';\" disabled=\"\">- $letter -</option>";
                            }
                        } else {
                            if (substr($row[$title], 0, 1) != $letter) {
                                $letter = substr($row[$title], 0, 1);
                                if (!$return) {
                                    echo ("<option value=\"\" style=\"background-color: $bg_color;color: $text_color;font-weight: bold;font-family: 'Arial';\" disabled=\"\"  >- $letter -</option>");
                                } else {
                                    $result .= "<option value=\"\" style=\"background-color: $bg_color;color: $text_color;font-weight: bold;font-family: 'Arial';\" disabled=\"\" >- $letter -</option>";
                                }
                            }
                        }

                        if (!$return) {
                            echo ('<option ' . $datavalue . ' value=' . $clean_value . ' ' .  $sel . '>' . $row[$title] . '</option>');
                        } else {
                            $result .= '<option ' . $datavalue . ' value=' . $clean_value . ' ' . $sel . '>' . $row[$title] . '</option>';
                        }
                    }
                }
            } else {
                foreach ($data as $row) {
                    $sel = ($selected === $row[$value] ? 'selected' : '');
                    $clean_value = preg_replace('!\s+!', ' ', trim($row[$value]));
                    if (!empty($clean_value) && !empty($row[$title])) {
                        $datavalue = (!empty($datavalue) ? 'data-value="' . $row[$datavalue] . '"' : '');
                        if (!$return) {
                            echo ('<option ' . $datavalue . ' value=' . $clean_value . ' ' . $sel . '>' . $row[$title] . '</option>');
                        } else {
                            $result .= '<option ' . $datavalue . ' value=' . $clean_value . ' ' . $sel . '>' . $row[$title] . '</option>';
                        }
                    }
                }
            }
            if ($return) {
                return $result;
            }
        } else {
            return '';
        }
    }

    /**
     * Indique si une string contient des nombres ou non.
     * @param string $string
     * @return bool
     */
    public static function ContainsNumbers(string $string)
    {
        return preg_match('/\\d/', $string) > 0;
    }

    /**
     * Retourne une string de caractères aléatoires de la longueur souhaitée. (10 PAR DEFAUT)
     * @param integer $length La longueur de la chaîne.
     * @return string
     */
    public static function RandomString($length = 10)
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    }

    /**
     * @param mixed $date Y-m-d
     *
     * @return array of date Y-m-d
     */
    public static function getFirstToLastDayOfWeek($date){
        $custom_date = strtotime( date('Y-m-d', strtotime($date)) );
        $week_start = date('Y-m-d', strtotime('this week', $custom_date));
        $week_end = date('Y-m-d', strtotime('this week next sunday', $custom_date));
        $week = [$week_start,$week_end];
        return $week;
    }
}
