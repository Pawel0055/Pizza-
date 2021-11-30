<?php

class Crawler extends Controller
{
    public function index()
    {
        echo 'Biesiadowo crawler<br><br>';

        $toppings = $this->curl();
        $topping = $this->model('Topping');

        // Add 'none' topping if it's not in the database
        $newTopping = array(
            'name' => 'none',
            'cost' => 0,
            'ingredients' => array(''),
            'pizzeriaId' => 1);
        $topping->addTopping($newTopping);

        // Add found toppings to the database
        foreach ($toppings as $item) {
            $topping->addTopping($item);
        }
    }

    public function napoli()
    {
        echo 'Napoli crawler<br><br>';

        $toppings = $this->test();
        $topping = $this->model('Topping');

        // Add found toppings to the database
        foreach ($toppings as $item) {
            $topping->addTopping($item);
        }
    }

    public function curl()
    {
        // Initialize curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://www.biesiadowo.pl/restauracja/menu/szczecin-al-wojska-polskiego");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // Execute curl
        $result = curl_exec($curl);
        curl_close($curl);

        // Find all <tr> tags
        preg_match_all('/<(tr)[^>]*>(.*?)<\/\1>/s', $result, $matches, PREG_SET_ORDER);

        $array = array();

        foreach ($matches as $val) {
            // Find topping name inside <strong> tag
            preg_match('/<strong>(.+?)<\/strong>/', $val[2], $name);

            // Find ingredients inside <small> or <span class="small"> tag
            preg_match('/(<small>|<span class="small">)(.+?)(<\/small>|<\/span>)/s', $val[2], $ingredients);

            // Find topping cost
            preg_match_all('/(\d\d,\d\d)/', $val[2], $cost);

            if (isset($name[1]) && isset($ingredients[2]) && isset($cost[0][2])) {
                $nameResult = $this->remove_polish($name[1]);
                $nameResult = ucfirst(strtolower(trim($nameResult)));

                $costResult = str_replace(',', '.', $cost[0][2]);

                // Create an array with ingredients
                $ingredResult = array();
                $ingredientsArray = explode(',', strip_tags($ingredients[2]));
                foreach ($ingredientsArray as $item) {
                    $ingr = explode('-', $item);
                    foreach ($ingr as $row) {
                        $ingr2 = explode('+', $row);
                        foreach ($ingr2 as $row2) {
                            array_push($ingredResult, $this->remove_polish(trim($row2)));
                        }
                    }
                }

                // Add new topping to array
                array_push($array, array(
                    'name' => $nameResult,
                    'cost' => $costResult,
                    'ingredients' => $ingredResult,
                    'pizzeriaId' => 2));
            }
        }
        return $array;
    }

    public function remove_polish($text)
    {
        $in = array('ą', 'Ą', 'ć', 'Ć', 'ł', 'Ł', 'ó', 'Ó', 'ś', 'Ś', 'ę', 'Ę', 'ń', 'Ń', 'ż', 'Ż', 'ź', 'Ź');
        $out = array('a', 'A', 'c', 'C', 'l', 'L', 'o', 'O', 's', 'S', 'e', 'E', 'n', 'N', 'z', 'Z', 'z', 'Z');
        return str_replace($in, $out, $text);
    }

    public function test()
    {
        // Initialize curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://www.pizzerianapoli.com.pl/pizza.html");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // Execute curl
        $result = curl_exec($curl);
        curl_close($curl);

        // Find all <tr> tags
        preg_match_all('/<(tr)[^>]*>(.*?)<\/\1>/s', $result, $matches, PREG_SET_ORDER);

        $array = array();

        foreach ($matches as $val) {
            // Name
            preg_match('/<b>([^-]+).*<\/b>/', $val[2], $name);

            // Ingredients
            preg_match('/(<\/b>)([^<]*?)(<\/td>)/s', $val[2], $ingredients);

            // Cost
            preg_match_all('/(\d\d,\d\d)/', $val[2], $cost);

            if (isset($name[1]) && isset($ingredients[2]) && isset($cost[0][2])) {
                $nameResult = strip_tags($name[1]);
                $nameResult = $this->remove_polish($nameResult);
                $nameResult = str_replace("&nbsp;", '', $nameResult);
                $nameResult = ucfirst(strtolower(trim($nameResult)));

                $costResult = str_replace(',', '.', $cost[0][2]);

                // Create an array with ingredients
                $ingredResult = array();

//                array_push($ingredResult, 'sos pomidorowy', 'mozzarella', 'oregano');

                $ingredients[2] = preg_replace('/\(.*\)/s', '', $ingredients[2]);

                $ingredientsArray = explode(',', strip_tags($ingredients[2]));
                foreach ($ingredientsArray as $item) {
                    $ingr = explode('-', $item);
                    foreach ($ingr as $row) {
                        $ingr2 = explode('+', $row);
                        foreach ($ingr2 as $row2) {
                            array_push($ingredResult, $this->remove_polish(trim($row2)));
                        }
                    }
                }

                // Add new topping to array
                array_push($array, array(
                    'name' => $nameResult,
                    'cost' => $costResult,
                    'ingredients' => $ingredResult,
                    'pizzeriaId' => 3));
            }
        }
        return $array;
    }

//    public function abc()
//    {
//        header('Content-Type: text/html; charset=utf-8');
//
//        // Initialize curl
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, "http://www.pizzerianapoli.com.pl/pizza.html");
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//
//        // Execute curl
//        $result = curl_exec($curl);
//        curl_close($curl);
//
//        $doc = new DOMDocument();
//        libxml_use_internal_errors(true);
//        $doc->loadHTML($result);
//        libxml_clear_errors();
//
//        $tableRows = $doc->getElementsByTagName('tr');
//        foreach ($tableRows as $row) {
//            echo $row->nodeName . ': ' . $row->nodeValue . '<br><br>';
//        }
//    }
}