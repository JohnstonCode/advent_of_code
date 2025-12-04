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

	banks := strings.Split(string(input), "\n")

	part1(banks)
	part2(banks)
}

func part1(banks []string) {
	part1 := 0

	for _, bank := range banks {
		nums := strings.Split(bank, "")

		m := 0

		for i := 0; i < len(nums); i++ {
			for j := i + 1; j < len(nums); j++ {
				n, _ := strconv.Atoi(nums[i] + nums[j])

				m = Max(m, n)
			}
		}

		part1 += m
	}

	fmt.Println(part1)
}

func part2(banks []string) {
	total := 0

	for _, bank := range banks {
		snums := strings.Split(bank, "")
		nums := make([]int, len(snums))

		for i, s := range snums {
			num, _ := strconv.Atoi(s)
			nums[i] = num
		}

		maxNums := make([]int, 0)

		total += findHighestJolt(0, nums, maxNums)
	}

	fmt.Println(total)
}

func Max(a, b int) int {
	if a > b {
		return a
	}
	return b
}

func findHighestJolt(start int, nums []int, highNums []int) int {
	m := 0
	l := len(nums) - ((12 - 1) - len(highNums))

	for i := start; i < l; i++ {
		current := nums[i]
		if current > m {
			m = current
			start = i
		}
	}

	highNums = append(highNums, m)
	if len(highNums) < 12 {
		return findHighestJolt(start+1, nums, highNums)
	}

	strs := make([]string, len(highNums))
	for i, j := range highNums {
		strs[i] = strconv.Itoa(j)
	}

	num := strings.Join(strs, "")
	res, _ := strconv.Atoi(num)

	return res
}
