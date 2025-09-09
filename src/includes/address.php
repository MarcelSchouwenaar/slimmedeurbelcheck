define('MAPBOX_ACCESS_TOKEN', 'pk.eyJ1IjoibWFyY2Vsc2NoIiwiYSI6ImNra3NzZGRrMzE5aDAybnMxNTV2OGpzdWwifQ.qJ5pcU95AMQ9DWddfnNPIg');

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-fill the address control field with a random value
        const addressControl = document.getElementById('adres-controle');
        const zipcode = document.getElementById('zipcode');
        const houseNumber = document.getElementById('houseNumber');
        const addition = document.getElementById('addition');

        addressControl.style.backgroundColor = '#f0f0f0'; // Light gray to indicate it's read-only

        function zipcodeCompleteAndValid(zip) {
            const _zip = zip.replace(/\s/g, '').toUpperCase();
            const zipregex = /^[1-9][0-9]{3}[A-Z]{2}$/; // Dutch zip code format
            if(_zip.length < 6 || !zipregex.test(_zip)) {
                return false;
            }
            return _zip;
        }
        function houseNumberComplete(number) {
            return number && !isNaN(number) && number > 0;
        }
        let addressControlDebounce;
        function debounceAddressControl() {
            clearTimeout(addressControlDebounce);
            addressControl.value = 'moment...';
            addressControlDebounce = setTimeout(updateAddressControl, 1000);
        }

        function updateAddressControl() {
            console.log('Updating address control...');
            if (zipcodeCompleteAndValid(zipcode.value) && houseNumberComplete(houseNumber.value)) {
                console.log('Fetching address for:', zipcode.value, houseNumber.value, addition.value);
                // addressControl.value = zipcode.value + ' ' + houseNumber.value + (addition.value ? ' ' + addition.value : '');
                fetch("includes/address.php?zipcode=" + encodeURIComponent(zipcode.value) + "&housenumber=" + encodeURIComponent(houseNumber.value) + "&addition=" + encodeURIComponent(addition.value))
                    .then(response => response.json())
                    .then(data => {
                        console.log('Address fetched:', data);
                        if (data.address) {
                            addressControl.value = data.address;
                        } else if (data.error) {
                            addressControl.value = data.error;
                        } else {
                            addressControl.value = 'Adres niet gevonden';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching address:', error);
                        addressControl.value = 'Fout bij het ophalen van adresgegevens';
                    });

            } else {
                addressControl.value = '...';
            }
            
        }


        zipcode.addEventListener('input',debounceAddressControl);
        houseNumber.addEventListener('input',debounceAddressControl);
        addition.addEventListener('input',debounceAddressControl);

        // addressControl.value = '<?php echo $randomZip . ' ' . $randomNumber; ?>';
        addressControl.style.backgroundColor = '#f0f0f0'; // Light gray to indicate it's read-only


    });
</script>

<?php
require_once 'env.php';

/*
1. Use the Correct Address Format

When using the Mapbox Geocoding API, ensure that your address is formatted correctly. The recommended format is:
{house number} {postcode}
Example:
If you have house number 123, zipcode 02111, and country US, your query string would be:
123 02111

2. Use the country Parameter

Instead of including the country in the search text, use the country parameter to limit results to that country.

3. Example API Call

https://api.mapbox.com/search/geocode/v6/forward?q=123%2002111&country=US&access_token=YOUR_MAPBOX_ACCESS_TOKEN

Replace 123 with your house number.
Replace 02111 with your zipcode.
Replace US with your country code (ISO 3166-1 alpha-2).
Replace YOUR_MAPBOX_ACCESS_TOKEN with your actual Mapbox access token.

4. Response

The API will return a JSON response containing the most relevant address matches, including the street address if available.
*/

$country = 'NL'; // Default country code for the Netherlands

function lookupAddress($zipcode, $houseNumber, $addition = '') {
    global $country;

    /*
    // Build the query string: "houseNumber addition zipcode"
    $query = trim($houseNumber . ' ' . ($addition ? $addition . ' ' : '') . $zipcode);

    // Build the API URL
    $url = "https://api.mapbox.com/search/geocode/v6/forward?q=" . urlencode($query)
         . "&country=" . urlencode($country)
         . "&access_token=" . urlencode(MAPBOX_ACCESS_TOKEN);
    */

    $url = "https://api.mapbox.com/search/geocode/v6/forward?num=" . urlencode($houseNumber)
        . "&postcode=" . urlencode($zipcode)
        . "&country=" . urlencode($country)
        . "&access_token=" . urlencode(MAPBOX_ACCESS_TOKEN);


    // Make the API request
    $response = @file_get_contents($url);
    if ($response === FALSE) {
        return null; // Handle error appropriately
    }

    // Decode the JSON response
    $data = json_decode($response, true);


    // Attempt to extract the street name and full address
    if (isset($data['features'][0]['properties']['street'])) {
        $street = $data['features'][0]['properties']['street'];
    } elseif (isset($data['features'][0]['text'])) {
        $street = $data['features'][0]['text'];
    } else {
        $street = null;
    }

    if (isset($data['features'][0]['properties']['full_address'])) {
        $fullAddress = $data['features'][0]['properties']['full_address'];
    } else {
        $fullAddress = null;
    }
    return $data['features'][0];
    // if ($street && $fullAddress) {
    //     return "$street, $fullAddress (sf)";
    // } elseif ($fullAddress) {
    //     return "$fullAddress (f)";
    // } else {
    //     return "No address found for the given input.";
    // }
}

// --- Accept GET requests only from form.php ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check referer to allow only requests from form.php on this server
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    $host = $_SERVER['HTTP_HOST'];
    if (
        strpos($referer, $host) !== false &&
        strpos($referer, 'form.php') !== false
    ) {
        // Get and sanitize GET parameters
        $zipcode = isset($_GET['zipcode']) ? trim($_GET['zipcode']) : '';
        $houseNumber = isset($_GET['housenumber']) ? trim($_GET['housenumber']) : '';
        $addition = isset($_GET['addition']) ? trim($_GET['addition']) : '';

        if ($zipcode && $houseNumber) {
            $result = lookupAddress($zipcode, $houseNumber, $addition);
            header('Content-Type: application/json');
            if ($result !== null) {
                // echo json_encode(['address' => $result]);
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Adres niet gevonden voor de opgegeven invoer.']);
            }
            exit;
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing parameters']);
            exit;
        }
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}