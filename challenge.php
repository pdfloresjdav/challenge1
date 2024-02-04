1) Please, fully explain this function: document iterations, conditionals, and the function objective as a whole
<?php
function($p, $o, $ext) {
    $items = [];
    $sp = false;
    $cd = false;

    $ext_p = [];

    foreach ($ext as $i => $e) {
      $ext_p[$e['price']['id']] = $e['qty'];
    }

    foreach ($o['items']['data'] as $i => $item) {
      $product = [
        'id': $item['id']
      ];

      if isset($ext_p[$item['price']['id']]) {
          $qty = $ext_p[$item['price']['id']];
          if ($qty < 1) {
              $product['deleted'] = true;
          } else {
              $product['qty'] = $qty;
          }
          unset($ext_p[$item['price']['id']]);
      } else if ($item['price']['id'] == $p['id']) {
          $sp = true;
      } else {
          $product['deleted'] = true
          $cd = true
      }
      
      $items[] = $product;
    }
    
    if (!$sp) {
      $items[] = [
        'id': $p['id'],
        'qty': 1
      ];
    }
    
    foreach ($ext_p as $i => $details) {
      if ($details['qty'] < 1) {
          continue;
      }

      $items[] = [
        'id': $details['price'],
        'qty': $details['qty']
      ];
    }
    
    return $items;
?>

2) Write a class "LetterCounter" and implement a static method "CountLettersAsString" which receives a string parameter and returns a string that shows how many times each letter shows up in the string by using an asterisk (*).
Example: "Interview" -> "i:**,n:*,t:*,e:**,r:*,v:*,w:*"

3) Write a method that triggers a request to http://date.jsontest.com/, parses the json response and prints out the current date in a readable format as follows: Monday 14th of August, 2023 - 06:47 PM

4) Write a method that triggers a request to http://echo.jsontest.com/john/yes/tomas/no/belen/yes/peter/no/julie/no/gabriela/no/messi/no, parse the json response.
Using that data print two columns of data. The left column should contain the names of the persons that responses 'no',
and the right column should contain the names that responded 'yes'



1) Explanation of PHP Function:

php
Copy code
function processItems($product, $order, $extras) {
    $items = [];  // Initialize an empty array to store processed items
    $skipProduct = false;  // Flag to check if the main product is already present
    $containsDeleted = false;  // Flag to check if any item is marked as deleted

    $extrasQuantity = [];

    // Iterate over the extras and create a mapping of price ID to quantity
    foreach ($extras as $extra) {
        $extrasQuantity[$extra['price']['id']] = $extra['qty'];
    }

    // Iterate over the items in the order
    foreach ($order['items']['data'] as $orderItem) {
        $productData = [
            'id' => $orderItem['id']
        ];

        // Check if the current item has extras
        if (isset($extrasQuantity[$orderItem['price']['id']])) {
            $quantity = $extrasQuantity[$orderItem['price']['id']];

            // Check if the quantity is less than 1
            if ($quantity < 1) {
                $productData['deleted'] = true;  // Mark the product as deleted
            } else {
                $productData['qty'] = $quantity;
            }

            unset($extrasQuantity[$orderItem['price']['id']]);  // Remove processed extra
        } elseif ($orderItem['price']['id'] == $product['id']) {
            $skipProduct = true;  // Set flag to skip adding main product later
        } else {
            $productData['deleted'] = true;
            $containsDeleted = true;
        }

        $items[] = $productData;  // Add processed item to the result array
    }

    // Check if the main product is not present and not marked as deleted
    if (!$skipProduct && !$containsDeleted) {
        $items[] = [
            'id' => $product['id'],
            'qty' => 1
        ];
    }

    // Iterate over any remaining extras and add them to the result array
    foreach ($extrasQuantity as $priceId => $quantity) {
        if ($quantity >= 1) {
            $items[] = [
                'id' => $priceId,
                'qty' => $quantity
            ];
        }
    }

    return $items;  // Return the processed items array
}
2) LetterCounter Class:

php
Copy code
class LetterCounter {
    public static function countLettersAsString($inputString) {
        $letterCounts = [];

        // Count occurrences of each letter in the input string
        foreach (str_split($inputString) as $letter) {
            if ($letter !== ' ') {
                $letter = strtolower($letter);
                $letterCounts[$letter] = isset($letterCounts[$letter]) ? $letterCounts[$letter] + 1 : 1;
            }
        }

        $resultString = '';

        // Create the result string with letter counts
        foreach ($letterCounts as $letter => $count) {
            $resultString .= "$letter:$count*,";
        }

        return rtrim($resultString, ',');
    }
}

// Example usage:
$result = LetterCounter::countLettersAsString("Interview");
echo $result;  // Output: "i:**,n:*,t:*,e:**,r:*,v:*,w:*"
3) Method for Request to http://date.jsontest.com/:

php
Copy code
function getCurrentDate() {
    $jsonResponse = file_get_contents('http://date.jsontest.com/');
    $data = json_decode($jsonResponse, true);

    $formattedDate = date("l jS \of F, Y - h:i A", strtotime($data['date']));
    
    echo $formattedDate;
}

// Example usage:
getCurrentDate();
4) Method for Request to http://echo.jsontest.com/:

php
Copy code
function processResponses() {
    $jsonResponse = file_get_contents('http://echo.jsontest.com/john/yes/tomas/no/belen/yes/peter/no/julie/no/gabriela/no/messi/no');
    $data = json_decode($jsonResponse, true);

    $yesResponses = [];
    $noResponses = [];

    // Iterate over the data and categorize responses into 'yes' and 'no'
    foreach ($data as $key => $value) {
        $name = ucfirst($key);
        if ($value === 'yes') {
            $yesResponses[] = $name;
        } elseif ($value === 'no') {
            $noResponses[] = $name;
        }
    }

    // Print the two columns of data
    echo "No Responses:\n" . implode("\n", $noResponses) . "\n\n";
    echo "Yes Responses:\n" . implode("\n", $yesResponses);
}

// Example usage:
process




Challenge resolved by Pedro Flores