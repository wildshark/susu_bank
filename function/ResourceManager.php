<?php
function ResourceManager(
        string $location = 'https://rabbitlite.iquipedigital.com/servfx/server.php',
        string $uri = 'https://rabbitlite.iquipedigital.com/servfx/',
        int $timeout = 5
    ): \SoapClient|false { // Use \SoapClient in type hint for clarity

        // 1. Check if the server location is accessible
        $context = stream_context_create(['http' => ['timeout' => $timeout]]);
        $headers = @get_headers($location, false, $context);

        if ($headers === false || strpos($headers[0], '200 OK') === false) {
            $status = $headers === false ? 'unreachable' : $headers[0];
            handle_fatal_error("servFx Error: SOAP server location '{$location}' is not accessible or did not return a 200 OK status. Status: {$status}", getCurrentErrorFile(), getCurrentErrorLine());
            return false;
        }

        // 2. If accessible, try to initialize the SOAP client
        try {
            // Use \SoapClient directly here
            $client = new \SoapClient(
                null,
                [
                    'location' => $location,
                    'uri'      => $uri,
                    'trace'    => 1,
                    'connection_timeout' => $timeout,
                    'exceptions' => true
                ]
            );
            return $client;
        } catch (\SoapFault $e) { // Use \SoapFault directly here
            handle_fatal_error("servFx SOAP Error: Failed to create SoapClient for URI '{$uri}' at location '{$location}'. Message: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return false;
        } catch (\Exception $e) { // Use \Exception directly here
            handle_fatal_error("servFx General Error: Failed to create SoapClient for URI '{$uri}' at location '{$location}'. Message: " . $e->getMessage(), getCurrentErrorFile(), getCurrentErrorLine());
            return false;
        }
    }