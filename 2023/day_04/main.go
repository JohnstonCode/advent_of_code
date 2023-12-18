package main

import (
	"fmt"
	"os"
	"strconv"
	"strings"
)

type Game struct {
	gameNumber     int
	winningNumbers []int
	numbers        []int
}

func main() {
	content, _ := os.ReadFile("./input.txt")
	part1 := part1(string(content))
	part2 := part2(string(content))

	fmt.Printf("Part1: %s\n", part1)
	fmt.Printf("Part2: %s\n", part2)
}

func part1(input string) string {
	lines := strings.Split(input, "\n")
	result := 0

	for _, line := range lines {
		parts := strings.Split(line, ": ")
		numberParts := strings.Split(parts[1], " | ")
		winningNumbers := processNumbers(numberParts[0])
		numbers := processNumbers(numberParts[1])
		winners := 0

		for _, num := range numbers {
			if contains(winningNumbers, num) {
				winners++
			}
		}

		points := 0

		if winners > 0 {
			points = calculatePoints(winners)
		}

		result += points
	}

	return strconv.Itoa(result)
}

func part2(input string) string {
	lines := strings.Split(input, "\n")
	var games []Game

	for i, line := range lines {
		parts := strings.Split(line, ": ")
		numberParts := strings.Split(parts[1], " | ")
		winningNumbers := processNumbers(numberParts[0])
		numbers := processNumbers(numberParts[1])

		games = append(games, Game{
			gameNumber:     i + 1,
			winningNumbers: winningNumbers,
			numbers:        numbers,
		})
	}

	scratchcards := playGames(games, games)

	return strconv.Itoa(scratchcards)
}

func playGames(gamesToPlay, games []Game) int {
	scratchcards := 0

	for _, game := range gamesToPlay {
		scratchcards++

		winners := 0

		for _, num := range game.numbers {
			if contains(game.winningNumbers, num) {
				winners++
			}
		}

		if winners == 1 {
			scratchcards += playGames([]Game{games[game.gameNumber]}, games)
		} else if winners > 1 {
			scratchcards += playGames(games[game.gameNumber:game.gameNumber+winners], games)
		}
	}

	return scratchcards
}

func processNumbers(numString string) []int {
	var numbers []int

	for _, n := range strings.Split(numString, " ") {
		if n == "" {
			continue
		}

		i, _ := strconv.Atoi(n)
		numbers = append(numbers, i)
	}

	return numbers
}

func contains(nums []int, num int) bool {
	for _, n := range nums {
		if n == num {
			return true
		}
	}

	return false
}

func calculatePoints(winners int) int {
	points := 1

	for i := 1; i < winners; i++ {
		points *= 2
	}

	return points
}
