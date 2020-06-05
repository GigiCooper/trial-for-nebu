<?php

declare (strict_types = 1);

/**
 * Name: Trial for Nebu
 * Description: A test task to implement beautifully.
 * Version: 1.0.0
 * Author: Agnes Varga
 * Author URI: https://www.facebook.com/vaga.agnes
 * Compatible: PHP 7.3
 */

class App
{
    /**
     * Data posted
     * @var string
     */
    private $userInput = '';

    public function __construct()
    {
        session_start();

        if ($this->calculationNeeded()) {

            $this->userInput = $_POST['array'];

            $this->saveUserlInput();

            $input = $this->inputStringToArray($this->userInput);

            try {
                $this->validateInputArray($input);
            } catch (Exception $e) {

                unset($_SESSION['array']);

                $_SESSION['message'] = $e->getMessage();

                $this->createView();
            }

            $groupAreas = $this->calculateGroupAreas($input);

            $this->saveResultString($groupAreas);

            $this->redirectToResultView();

        } else {

            unset($_SESSION['message']);

            $this->createView();
        }
    }

    /**
     * Answers the question weather we need calculations
     * @return boolean
     */
    private function calculationNeeded(): bool
    {

        if ('POST' === $_SERVER['REQUEST_METHOD'] && !isset($_POST['newTry'])) {
            return true;
        }

        return false;
    }

    /**
     * Saves input string to session
     * @return void
     */
    private function saveUserlInput(): void
    {
        $_SESSION['array'] = $this->userInput;
    }

    /**
     * Creates array from input string
     * @param  string $input
     * @return array
     */
    private function inputStringToArray(string $input): array
    {

        $input = preg_replace('/\s+/', '', $input);

        $lastChar = substr($input, -1);

        if ("," === $lastChar) {
            $input = substr($input, 0, -1);
        }

        $inputRowStrings = explode('],[', $input);

        $formattedInput = [];

        foreach ($inputRowStrings as $inputRowString) {

            $inputRowString = str_replace(["[", "]"], "", $inputRowString);

            $formattedInput[] = explode(',', $inputRowString);
        }

        return $formattedInput;
    }

    /**
     * Validate array as a matrix of 1-s and 0-s
     * @param  array  $input
     * @return void
     * @throws Exception
     */
    private function validateInputArray(array $input): void
    {
        foreach ($input as &$row) {

            if (!isset($columnSize)) {
                $columnSize = count($row);
            }

            if (count($row) !== $columnSize) {
                throw new Exception(
                    'Row sizes are different. Invalid array given:<br>' .
                    $this->userInput
                );
            }

            foreach ($row as &$element) {

                $element = (int) $element;

                if (1 !== $element && 0 !== $element) {
                    throw new Exception(
                        'Unacceptable character or wrong structure. ' .
                        'Invalid array given:<br>' .
                        $this->userInput
                    );
                }
            }
        }
    }

    /**
     * Add template parts
     * @return void
     */
    private function createView(): void
    {
        require_once 'view/template-parts/main.php';
    }

    /**
     * Calculates group areas
     * @param  array  $input
     * @return array $groupAreas
     */
    private function calculateGroupAreas(array $input): array
    {
        $groups = [];

        foreach ($input as $rowIndex => &$row) {
            foreach ($row as $columnIndex => &$element) {

                $element = (int) $element;

                if (1 !== $element) {
                    continue;
                }

                $processedMatchingNeighbours = [];

                // Check the neighbour above of the element
                if (@$input[$rowIndex - 1][$columnIndex] === 1) {
                    $processedMatchingNeighbours[] = [
                        'row' => $rowIndex - 1,
                        'col' => $columnIndex,
                    ];
                }

                // Check the neighbour left side of the element
                if (@$input[$rowIndex][$columnIndex - 1] === 1) {
                    $processedMatchingNeighbours[] = [
                        'row' => $rowIndex,
                        'col' => $columnIndex - 1,
                    ];
                }

                // Open new group
                if (empty($processedMatchingNeighbours)) {
                    $groups[][] = [$rowIndex, $columnIndex];
                    continue;
                }

                // Concatenate to existing group

                $matchingGroups = [];

                foreach ($processedMatchingNeighbours as $neighbour) {

                    foreach ($groups as $groupId => $group) {

                        $neighbourFoundInGroup = in_array(
                            [
                                $neighbour['row'],
                                $neighbour['col'],
                            ],
                            $group
                        );

                        if ($neighbourFoundInGroup) {
                            $matchingGroups[] = $groupId;
                        }
                    }

                    $matchingGroups = array_unique($matchingGroups);

                    $elementIsBridgeBetweenGroups = count($matchingGroups) === 2;

                    if ($elementIsBridgeBetweenGroups) {

                        $groups[$matchingGroups[0]] = array_merge(
                            $groups[$matchingGroups[0]],
                            $groups[$matchingGroups[1]]
                        );

                        unset($groups[$matchingGroups[1]]);
                    }

                    // Add element to the group
                    $groups[$matchingGroups[0]][] = [$rowIndex, $columnIndex];
                }
            }
        }

        foreach ($groups as &$group) {
            $group = array_map(
                "unserialize",
                array_unique(
                    array_map(
                        "serialize",
                        $group
                    )
                )
            );
        }

        $groupAreas = [];

        foreach ($groups as &$group) {
            $groupAreas[] = count($group);
        }

        return $groupAreas;
    }

    /**
     * Convert vector into readable string
     * @param  array  $vector [1 dimensional array]
     * @return string
     */
    private function getReadableArray(array $vector): string
    {
        return '[' . implode(', ', $vector) . ']';
    }

    /**
     * save readable result to session
     * @param  array  $groupAreas
     * @return void
     */
    private function saveResultString(array $groupAreas): void
    {
        $resString = $this->getReadableArray($groupAreas);

        $_SESSION['result'] = $resString;
    }

    /**
     * Redirects to result section
     * @return void
     */
    private function redirectToResultView(): void
    {
        header("Location: " . $_SERVER['PHP_SELF'] . '#result');
    }
}
