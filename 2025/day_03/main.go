package main

import (
	"bufio"
	"fmt"
	"log"
	"os"
	"strings"
)

func main() {
	banks, err := parseInput("input.txt")
	if err != nil {
		log.Fatal(err)
	}

	fmt.Println(part1(banks))
	fmt.Println(part2(banks))
}

func parseInput(path string) ([]string, error) {
	f, err := os.Open(path)
	if err != nil {
		return nil, err
	}
	defer f.Close()

	var banks []string

	scanner := bufio.NewScanner(f)
	for scanner.Scan() {
		line := strings.TrimSpace(scanner.Text())
		if line == "" {
			continue
		}

		banks = append(banks, line)
	}

	if err := scanner.Err(); err != nil {
		return nil, err
	}

	return banks, nil
}

func part1(banks []string) int {
	total := 0

	for _, bank := range banks {
		maxPair := 0

		for i := 0; i < len(bank); i++ {
			for j := i + 1; j < len(bank); j++ {
				d1 := int(bank[i] - '0')
				d2 := int(bank[j] - '0')

				n := d1*10 + d2
				if n > maxPair {
					maxPair = n
				}
			}
		}

		total += maxPair
	}

	return total
}

func part2(banks []string) int {
	total := 0

	for _, bank := range banks {
		digits := toDigits(bank)
		total += highestJoltNumber(digits, 12)
	}

	return total
}

func toDigits(s string) []int {
	digits := make([]int, len(s))
	for i := range s {
		digits[i] = int(s[i] - '0')
	}

	return digits
}

func highestJoltNumber(nums []int, count int) int {
	start := 0
	result := 0

	for picked := 0; picked < count; picked++ {
		limit := len(nums) - (count - picked) + 1

		maxDigit := -1
		maxIndex := start

		for i := start; i < limit; i++ {
			if nums[i] > maxDigit {
				maxDigit = nums[i]
				maxIndex = i
			}
		}

		result = result*10 + maxDigit
		start = maxIndex + 1
	}

	return result
}
