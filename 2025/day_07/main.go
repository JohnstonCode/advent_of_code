package main

import (
	"bufio"
	"fmt"
	"log"
	"os"
)

func main() {
	grid, start, err := parseInput("input.txt")
	if err != nil {
		log.Fatal(err)
	}

	fmt.Println(part1(grid, start))
	fmt.Println(part2(grid, start))
}

func part1(grid []string, start int) int {
	beams := make(map[int]struct{})
	beams[start] = struct{}{}

	count := 0
	for _, row := range grid {
		next := make(map[int]struct{})

		for x := range beams {
			if row[x] == '.' {
				next[x] = struct{}{}

				continue
			}

			next[x-1] = struct{}{}
			next[x+1] = struct{}{}
			count++
		}

		beams = next
	}

	return count
}

func part2(grid []string, start int) int {
	beams := make(map[int]int)
	beams[start] = 1

	count := 0
	for _, row := range grid {
		next := make(map[int]int)

		for x, n := range beams {
			if row[x] == '.' {
				next[x] += n

				continue
			}

			next[x-1] += n
			next[x+1] += n
			count++
		}

		beams = next
	}

	sum := 0
	for _, b := range beams {
		sum += b
	}

	return sum
}

func parseInput(input string) ([]string, int, error) {
	file, err := os.Open(input)
	if err != nil {
		return nil, 0, fmt.Errorf("could not open %s: %v", input, err)
	}
	defer file.Close()

	var grid []string
	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		line := scanner.Text()
		grid = append(grid, line)
	}

	start := 0
	for x, char := range grid[0] {
		if char == 'S' {
			start = x
		}
	}

	grid = grid[1:]

	return grid, start, nil
}
