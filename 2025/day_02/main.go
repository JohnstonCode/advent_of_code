package main

import (
	"fmt"
	"os"
	"strconv"
	"strings"
)

func main() {
	input, err := os.ReadFile("input.txt")
	if err != nil {
		panic(err)
	}

	rng := strings.Split(string(input), ",")

	part1(rng)
	part2(rng)
}

func part1(ranges []string) {
	var nums []int

	for _, r := range ranges {
		ids := strings.Split(r, "-")
		from, _ := strconv.Atoi(ids[0])
		to, _ := strconv.Atoi(ids[1])

		for i := from; i <= to; i++ {
			if i == 101 {
				continue
			}

			if !hasEvenDigits(i) {
				continue
			}

			if halvesAreEqual(i) {
				nums = append(nums, i)
			}
		}
	}

	fmt.Println(sum(nums))
}

func part2(ranges []string) {
	var nums []int

	for _, r := range ranges {
		ids := strings.Split(r, "-")
		from, _ := strconv.Atoi(ids[0])
		to, _ := strconv.Atoi(ids[1])

		for i := from; i <= to; i++ {
			if i == 101 {
				continue
			}

			if atLeastTwice(i) {
				nums = append(nums, i)
			}
		}
	}

	fmt.Println(sum(nums))
}

func hasEvenDigits(n int) bool {
	digits := len(strconv.Itoa(n))
	return digits%2 == 0
}

func halvesAreEqual(n int) bool {
	s := strconv.Itoa(n)
	length := len(s)

	half := length / 2
	left := s[:half]
	right := s[half:]

	return left == right
}

func sum(nums []int) int {
	sum := 0
	for _, n := range nums {
		sum += n
	}

	return sum
}

func atLeastTwice(num int) bool {
	n := strconv.Itoa(num)

	doubled := n + n
	doubled = doubled[1 : len(doubled)-1]

	return strings.Contains(doubled, n)
}
