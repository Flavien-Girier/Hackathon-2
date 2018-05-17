<?php

namespace AppBundle\Controller;

use GuzzleHttp\Client;

class Characters
{
    const EMPIRE = 'Empire';
    const REPUBLIC = 'Republic';

    private $characters = [];

    public function __toString ()
    {
        return $this->characters['id'] . " - " . $this->characters['name'];
    }

    public function getAll() : array
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://akabab.github.io/starwars-api/api/',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        $response = $client->request('GET', 'all.json');
        $body = $response->getBody();
        $assoc = true;
        $this->characters = json_decode($body,$assoc);

        return $this->characters;
    }

    public function getOneByID($id) : array
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://akabab.github.io/starwars-api/api/id/',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        $url = $id . '.json';
        $response = $client->request('GET', $url);
        $body = $response->getBody();
        $assoc = true;
        $this->characters = json_decode($body,$assoc);

        return $this->characters;
    }

    /**
     * return all possible characters for of the affiliations 'Republic' or 'Empire'
     *
     * @param array $characters
     * @param string $affiliation
     *
     * @return array
     */
    public function getExtractByAffiliation(array $characters, string $affiliation) : array
    {
        $newCharacters = [];
        if ($affiliation == $this::EMPIRE) {
            $ennemy = $this::REPUBLIC;
        } else {
            $ennemy = $this::EMPIRE;
        }
        foreach ($characters as $id => $character) {
            if (isset($character['affiliations'])) {
                for ($i = count($character['affiliations']) - 1; $i >= 0; $i--) {
                    if (strpos($character['affiliations'][$i],$ennemy) !== false) {
                        break;
                    }
                    if (strpos($character['affiliations'][$i],$affiliation) !== false) {
                        $newCharacters[] = $character;
                        break;
                    }
                }
            }
        }
        return $newCharacters;
    }

    /**
     * return all possible characters that have a specific string value
     *      for a specified first level paramater
     *
     * @param array $characters
     * @param string $parameter
     * @param string $value
     *
     * @return array
     */
    public function getExtractByParameterAndValue(array $characters, string $parameter, string $value) : array
    {
        $newCharacters = [];
        foreach ($characters as $id => $character) {
            if (isset($character[$parameter])) {
                if (is_array($character[$parameter])) {
                    for ($i = count($character[$parameter]) - 1; $i >= 0; $i--) {
                        if (strpos($character[$parameter][$i], $value) !== false) {
                            $newCharacters[] = $character;
                        }
                    }
                } else {
                    if (strpos($character[$parameter], $value) !== false) {
                        $newCharacters[] = $character;
                    }
                }
            }
        }
        return $newCharacters;
    }

    /**
     * return all possible characters that have a specific string value
     *      for a specified first level paramater
     *
     * @param array $characters
     * @param string $parameter
     * @param string $value
     *
     * @return array
     */
    public function getExtractByParameterAndInverseOfValue(array $characters, string $parameter, string $value) : array
    {
        $newCharacters = [];
        foreach ($characters as $id => $character) {
            if (isset($character[$parameter])) {
                if (is_array($character[$parameter])) {
                    for ($i = count($character[$parameter]) - 1; $i >= 0; $i--) {
                        if (strpos($character[$parameter][$i], $value) === false) {
                            $newCharacters[] = $character;
                        }
                    }
                } else {
                    if (strpos($character[$parameter], $value) === false) {
                        $newCharacters[] = $character;
                    }
                }
            }
        }
        return $newCharacters;
    }

    /**
     * add more characters to the existing array if they are not duplicates
     *
     * @param array $characters
     * @param array $moreCharacters
     *
     * @return array
     */
    public function setExtractByDuplicates(array $characters, array $moreCharacters) : array {
        $ids = [];
        if (count($characters) !== 0) {
            foreach ($characters as $character) {
                $ids[] = $character['id'];
            }
        }

        $characters = [];

        if (count($moreCharacters) > 1) {
            foreach ($moreCharacters as $moreCharacter) {
                if (!in_array($moreCharacter['id'],$ids) || $ids == []) {
                    $characters[] = $moreCharacter;
                }
            }
        } elseif (count($moreCharacters) == 1) {
            if (!in_array($moreCharacters['id'],$ids) || $ids == []) {
                $characters[] = $moreCharacters;
            }
        }

        return $characters;
    }

    /**
     * return all possible characters that have at least one of specifics string values
     *      for at least one of the specified first level paramaters
     *
     * @param array $characters
     * @param mixed $parameters
     * @param mixed $values
     *
     * @return array
     */
    public function getExtractByParametersAndValues(array $characters, $parameters, $values) : array
    {
        $charactersManager = new Characters();
        $newCharacters = $characters;
        if (is_array($parameters)) {
            foreach ($parameters as $parameter) {
                if (is_array($values)) {
                    foreach ($values as $value) {
                        $tempCharacters = $charactersManager->getExtractByParameterAndValue($newCharacters, $parameter, $value);
                        $newCharacters = $charactersManager->setExtractByDuplicates($newCharacters, $tempCharacters);
                    }
                } else {
                    $tempCharacters = $charactersManager->getExtractByParameterAndValue($newCharacters, $parameter, $values);
                    $newCharacters = $charactersManager->setExtractByDuplicates($newCharacters, $tempCharacters);
                }
            }
        } else {
            if (is_array($values)) {
                foreach ($values as $value) {
                    $tempCharacters = $charactersManager->getExtractByParameterAndValue($newCharacters, $parameters, $value);
                    $newCharacters = $charactersManager->setExtractByDuplicates($newCharacters, $tempCharacters);
                }
            } else {
                $tempCharacters = $charactersManager->getExtractByParameterAndValue($newCharacters, $parameters, $values);
                $newCharacters = $charactersManager->setExtractByDuplicates($newCharacters, $tempCharacters);
            }
        }

        return $newCharacters;
    }

}
