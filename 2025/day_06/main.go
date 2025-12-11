package main

import (
	"bufio"
	"fmt"
	"log"
	"os"
	"slices"
	"strconv"
	"strings"
)

func main() {
	input, ops, err := parseInput("input.txt")
	if err != nil {
		log.Fatal(err)
	}

	fmt.Println(part1(input, ops))
	fmt.Println(part2(input, ops))
}

func parseInput(path string) ([][]string, []string, error) {
	file, err := os.Open(path)
	if err != nil {
		return nil, nil, fmt.Errorf("could not open %s: %v", path, err)
	}
	defer file.Close()

	var input [][]string

	scanner := bufio.NewScanner(file)

	for scanner.Scan() {
		line := scanner.Text()
		input = append(input, strings.Split(line, ""))
	}

	var ops []string
	for _, char := range input[len(input)-1] {
		if char != " " {
			ops = append(ops, char)
		}
	}

	input = input[:len(input)-1]

	return input, ops, nil
}

func part1(input [][]string, ops []string) int {
	problems := [][]int{}

	for _, line := range input {
		nums := sliceToInts(line)
		for i, num := range nums {
			if len(problems) <= i {
				problems = append(problems, []int{})
			}

			problems[i] = append(problems[i], num)
		}
	}

	total := 0

	for i, op := range ops {
		nums := problems[i]

		total += processOp(op, nums)
	}

	return total
}

func sliceToInts(slice []string) []int {
	var ints []int
	var current string

	for _, v := range slice {
		if v == " " {
			if current != "" {
				n, _ := strconv.Atoi(current)
				ints = append(ints, n)
				current = ""
			}

			continue
		}

		current += v
	}

	if current != "" {
		n, _ := strconv.Atoi(current)
		ints = append(ints, n)
	}

	return ints
}

func part2(input [][]string, ops []string) int {
	slices.Reverse(ops)

	var nums []int

	problems := rotateAntiClockwise(input)
	opPos := 0
	total := 0

	for _, problem := range problems {
		if strings.TrimSpace(strings.Join(problem, "")) == "" {
			op := ops[opPos]
			total += processOp(op, nums)

			opPos++
			nums = []int{}

			continue
		}

		nums = append(nums, format(problem))
	}

	if len(nums) > 0 {
		total += processOp(ops[opPos], nums)
	}

	return total
}

func processOp(op string, nums []int) int {
	if op == "+" {
		return sum(nums)
	} else if op == "*" {
		return multiply(nums)
	}

	return 0
}

func format(slice []string) int {
	str := strings.TrimSpace(strings.Join(slice, ""))

	num, _ := strconv.Atoi(str)

	return num
}

func sum(nums []int) int {
	s := 0
	for _, num := range nums {
		s += num
	}

	return s
}

func multiply(nums []int) int {
	s := 1
	for _, num := range nums {
		s *= num
	}

	return s
}

func rotateAntiClockwise(matrix [][]string) [][]string {
	rows := len(matrix)
	cols := len(matrix[0])

	rotated := make([][]string, cols)
	for i := range rotated {
		rotated[i] = make([]string, rows)
	}

	for i := 0; i < rows; i++ {
		for j := 0; j < cols; j++ {
			rotated[cols-1-j][i] = matrix[i][j]
		}
	}

	return rotated
}
