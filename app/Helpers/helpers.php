<?php

if (!function_exists('generateRandomCode')) {
    /**
     * Generate a random code.
     *
     * @param int $length Length of the code
     * @return string
     */
    function generateRandomCode($length = 8) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomCode = '';
        for ($i = 0; $i < $length; $i++) {
            $randomCode .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomCode;
    }
}
if (!function_exists('generateTemporaryPassword')) {
    function generateTemporaryPassword($membershipId,$fullName) {
        // Generate a random 5-digit number
        $membershipPart = substr($membershipId, 0, 5);

        // Split the full name by spaces
        $nameParts = explode(' ', trim($fullName));

        // If the name has only two parts (first and last name)
        if (count($nameParts) == 2) {
           
                $firstName = $nameParts[0];
                $lastName = $nameParts[1];
           
        }
    
        // If the name has more than two parts
        $firstName = $nameParts[0];  // First name is the first element
        $lastName = $nameParts[count($nameParts) - 1];  // Last name is the last element
    
        // Get the first character of the first and last name, and convert to lowercase
        $firstInitial = strtolower(substr($firstName, 0, 1));
        $lastInitial = strtolower(substr($lastName, 0, 1));

        // Create the temporary password
        $temporaryPassword = $membershipPart . $firstInitial . $lastInitial;

        return $temporaryPassword;
    }
}
