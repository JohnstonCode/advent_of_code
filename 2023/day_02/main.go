package main

import (
	"fmt"
	"math"
	"os"
	"strconv"
	"strings"
)

var bag = map[string]int{
	"red":   12,
	"green": 13,
	"blue":  14,
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
	sum := 0

Loop:
	for i, line := range lines {
		gameParts := strings.Split(line, ": ")
		turns := gameParts[1]

		for _, turn := range strings.Split(turns, "; ") {
			for _, cube := range strings.Split(turn, ", ") {
				parts := strings.Split(cube, " ")
				amount, color := parts[0], parts[1]
				count, _ := strconv.Atoi(amount)

				if count > bag[color] {
					continue Loop
				}
			}
		}

		sum += i + 1
	}

	return strconv.Itoa(sum)
}

func part2(input string) string {
	lines := strings.Split(input, "\n")
	sum := 0

	for _, line := range lines {
		gameParts := strings.Split(line, ": ")
		turns := gameParts[1]
		var maxColors = map[string]int{
			"red":   0,
			"green": 0,
			"blue":  0,
		}

		for _, turn := range strings.Split(turns, "; ") {
			for _, cube := range strings.Split(turn, ", ") {
				parts := strings.Split(cube, " ")
				amount, color := parts[0], parts[1]
				count, _ := strconv.Atoi(amount)

				maxColors[color] = int(math.Max(float64(maxColors[color]), float64(float64(count))))
			}
		}

		product := 1
		for _, val := range maxColors {
			product *= val
		}

		sum += product
	}

	return strconv.Itoa(sum)
}
