package main

import (
	"fmt"
	"os"
	"strconv"
	"strings"
	"unicode"
)

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

	for _, line := range lines {
		var digits []int

		for i := 0; i < len(line); i++ {
			if unicode.IsDigit(rune(line[i])) {
				digit, _ := strconv.Atoi(string(rune(line[i])))

				digits = append(digits, digit)
			}
		}

		concatenated := strconv.Itoa(digits[0]) + strconv.Itoa(digits[len(digits)-1])
		result, _ := strconv.Atoi(concatenated)

		sum += result
	}

	return strconv.Itoa(sum)
}

func part2(input string) string {
	lines := strings.Split(input, "\n")
	numbers := []string{"zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine"}
	sum := 0

	for _, line := range lines {
		var digits []int

		for i := 0; i < len(line); i++ {
			if unicode.IsDigit(rune(line[i])) {
				digit, _ := strconv.Atoi(string(rune(line[i])))

				digits = append(digits, digit)

				continue
			}

			str := line[i:]
			for ii, num := range numbers {
				if strings.HasPrefix(str, num) {
					digits = append(digits, ii)
				}
			}
		}

		concatenated := strconv.Itoa(digits[0]) + strconv.Itoa(digits[len(digits)-1])
		result, _ := strconv.Atoi(concatenated)

		sum += result
	}

	return strconv.Itoa(sum)
}
